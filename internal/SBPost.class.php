<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaap
 * Date: 11/10/12
 * Time: 1:29 PM
 * To change this template use File | Settings | File Templates.
 */
class SBPost extends SBAbstractContent {

	private $_tags 		= '';
	private $_category	= '';
	private $_timestamp	= 0;
	private $_title		= '';
	private $_pinned	= null;

	public function __construct($id) {
		$result = SBFactory::Database()->selectRows(DB_TABLE_PREFIX.'post', '*', array('id' => $id));
		if (empty($result)) {
			throw new Exception('Post with id '.$id.' does not exist.');
		}

		$this->_id 		  = $result[0]['id'];
		$this->_title 	  = $result[0]['title'];
		$this->_pinned 	  = $result[0]['pinned'];
		$this->_category  = $result[0]['category'];
		$this->_tags 	  = $result[0]['tags'];
		$this->_content	  = $result[0]['content'];
		$this->_timestamp = $result[0]['utime'];
	}

	public static function createPost($title, $content, $category, $tags, $pinned = 0) {
		$database = SBFactory::Database();

		$row = array(
			'title' => $title,
			'pinned' => $pinned,
			'category' => $category,
			'tags' => $tags,
			'content' => $content,
			'utime' => time()
		);

		$eventData = array(
			'title' => &$title,
			'content' => &$content,
			'category' => &$category,
			'tags' => &$tags,
			'pinned' => &$pinned,
			'utime' => time());
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_ADD));

		return $database->insertRow(DB_TABLE_PREFIX.'post', $row);
	}

	public static function deletePost($id) {
		$database = SBFactory::Database();

		$eventData = array('id' => $id);
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_DELETE));

		$filter = array('id' => $id);

		return $database->deleteRows(DB_TABLE_PREFIX.'post', $filter);
	}

	public function getComments() {
		$where = array("post_id" => $this->_id);

		return SBFactory::Database()->selectRows("comment", "*", $where, null, "DESC", "id");
	}

	public static function getCommentById($id) {
		$database = SBFactory::Database();
		$where = array("id" => $id);
		$result = $database->selectRows(DB_TABLE_PREFIX."comment", "*", $where);

		return $result[0];
	}

	public static function addComment($id, $author, $email, $content) {
		$database = SBFactory::Database();

		$eventData = array(
			'post_id' => $id,
			'content' => &$content,
			'author' => &$author,
			'email' => &$email,
			'utime' => time());
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_USER_COMMENT));

		$row = array(
			'post_id' => $id,
			'author'	=>	$author,
			'email' => $email,
			'ip'	=> $_SERVER['REMOTE_ADDR'],
			'content'	=> $content,	//TODO verify content by using DOM extension
			'utime'	=> time()
		);

		return $database->insertRow(DB_TABLE_PREFIX.'comment', $row);
	}

	public static function deleteComment($id) {
		$database = SBFactory::Database();

		SBEventObserver::fire(new SBEvent(array('id' => $id), SBEvent::ON_COMMENT_DELETE));
		$filter = array('id' => $id);

		return $database->deleteRows(DB_TABLE_PREFIX.'comment', $filter);
	}

	public static function getPostsList($page=1, $category=null, $tag=null) {
		$database = SBFactory::Database();
		$queryString = 'SELECT * FROM '.DB_TABLE_PREFIX.'post';

		if ($category !== null) {
			$queryString .= " WHERE `category` = '$category'";
		}
		if ($tag !== null) {
			$queryString .= " AND `tags` LIKE '%,$tag,%'";
		}

		$nr_posts = SBFactory::Settings()->getSetting("no_posts_per_page");
		$queryString .= ' ORDER BY `id` DESC LIMIT '.($page-1).', '.$nr_posts;

		$results = $database->query($queryString);
		if (!$results)
			return false;
		foreach($results as $key => $post) {
			$results[$key]['tags'] = explode(',', $post['tags']);
		}

		return $results;
	}

	/**
	 * Returns all pinned posts.
	 * @return array Associative array with all the pinned posts.
	 */
	public static function getPinnedPostsList() {
		$database = SBFactory::Database();

		$filter = array("pinned" => 1);
		return $database->selectRows(DB_TABLE_PREFIX."post", "*", $filter);
	}

	/**
	 * Gets all the categories.
	 * @return mixed The array with the categories or false if no category found.
	 */
	public static function getCategoriesList() {
		$database = SBFactory::Database();

		$result = $database->selectRows(DB_TABLE_PREFIX."category", "*");
		return count($result) ? $result : false;
	}

	public static function getPostArchives() {
		$database = SBFactory::Database();

		$columns = array("id","title", "utime");
		$archives = $database->selectRows(DB_TABLE_PREFIX."post", $columns,
			null, null, "DESC", "utime");

		$result = array();

		foreach($archives as $post)
			$result[date("Y", $post['utime'])][date("F", $post['utime'])][] = $post;

		return $result;
	}

	public function commit() {
		if ($this->_dirty) {
			$updateSet = array('title' => $this->_title, 'pinned' => $this->_pinned, 'category' => $this->_category,
				'content' => $this->_content, 'tags' => $this->_tags);

			$result = SBFactory::Database()->updateRows(DB_TABLE_PREFIX.'post', array('id' => $this->_id), $updateSet);

			if ($result) {
				$this->_dirty = false;
			}

			return $result;
		}

		return false;
	}
}
