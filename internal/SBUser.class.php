<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 10/26/12
 * Time: 5:39 PM
 * To change this template use File | Settings | File Templates.
 */
class SBUser {

	private $_stateManager;
	private $_sessID = null;
	private $_ip = null;
	private $_browserInfo = array();
	private $_isAdmin = false;

	private $_comments = array();

	public function __construct() {

		$this->_stateManager = SBFactory::getStateManager();
		$this->_sessID = $this->_stateManager->getSessionID();
		$this->_ip = $_SERVER['REMOTE_ADDR'];
		if (ini_get('browscap')) {
			$this->_browserInfo = get_browser(null, true);
		}

		if ($this->_stateManager->getSessionVar('admin')) {
			$this->_isAdmin = true;
		}

		$this->_comments = $this->_stateManager->getCookieVar('comments');

	}

	public function isAdmin() {
		return $this->_isAdmin;
	}

	public function adminLogIn($username, $password) {
		if ($this->isAdmin()) {
			$admin_username = SBFactory::Settings()->getSetting('admin_username');
			$admin_password = SBFactory::Settings()->getSetting('admin_password');

			if ($username == $admin_username && $password == $admin_password) {
				$this->_stateManager->setSessionVar('admin', false);
			} else {
				return false;
			}
		} else {
			return false;
		}

	}

	public function adminLogOut() {
		if ($this->isAdmin()) {
			$this->_stateManager->unsetSessionVar('admin');
			$this->_sessID = $this->_stateManager->refreshSession();
		} else {
			return false;
		}

		return true;
	}

	/**
	 * Adds a post.
	 * @param string $title Title
	 * @param string $content Content
	 * @param string $category Category
	 * @param string $tags Space delimited string of tags
	 * @param boolean $pinned Pinned posts will show up first
	 * @return boolean true if successful
	 */
	public function addPost($title, $content, $category, $tags, $pinned = false) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();

		$row = array(
			'title' => $title,
			'pinned' => $pinned ? 1 : 0,
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

		return $database->insertRow(TABLE_PREFIX.'post', $row);
	}

	/**
	 * Delets a post.
	 * @param int $id
	 * @return boolean True if successful, false otherwise.
	 */
	public function deletePost($id) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();

		$eventData = array('id' => $id);
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_DELETE));

		$filter = array('id' => $id);

		return $database->deleteRows(TABLE_PREFIX.'post', $filter);
	}

	/**
	 * Modifies a post.
	 * @param int $id
	 * @param string $title The new title.
	 * @param string $content The new content.
	 * @param string $category The new category.
	 * @param string $tags
	 * @return boolean true if successful
	 */
	public function modifyPost($id, $title=null, $content=null, $category=null, $tags=null) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();

		if ($title == null && $content == null && $category == null)
			return false;

		$eventData = array(
			'id' => $id,
			'title' => &$title,
			'content' => &$content,
			'category' => &$category,
			'tags' => &$tags
		);
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_MOD));

		$filter = array('id' => $id);
		$update_set = array();

		if ($title)
			$update_set['title'] = $title;
		if ($content)
			$update_set['content'] = $content;
		if ($category)
			$update_set['category'] = $category;
		if ($tags)
			$update_set['tags'] = $tags;

		return $database->updateRows(TABLE_PREFIX.'post', $filter, $update_set);
	}

	/**
	 * Pins a post. Pinned posts show up first on the blog.
	 * @param int $id
	 * @return boolean true if successful
	 */
	public function togglePinPost($id) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();
		//TODO event on toggle pinned post

		$filter = array('id' => $id);
		$update_set = array();

		if (blog_postIsPinned($id))
			$update_set['pinned'] = '0';
		else
			$update_set['pinned'] = '1';

		return $database->updateRows(TABLE_PREFIX.'post', $filter, $update_set);
	}

	/**
	 * Adds a comment to the post ID.
	 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
	 * @param string $content Comment content.
	 * @param string $author Comment author.
	 * @param string $email
	 * @return boolean true if query successful
	 */
	public function addComment($postid, $content, $author, $email) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();
		$eventData = array(
			'post_id' => $postid,
			'content' => &$content,
			'author' => &$author,
			'email' => &$email,
			'utime' => time());
		SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_USER_COMMENT));
		$row = array(
			'post_id' => $postid,
			'author'	=>	$author,
			'email' => $email,
			'ip'	=> $_SERVER['REMOTE_ADDR'],
			'content'	=> $content,	//TODO verify content by using DOM extension
			'utime'	=> time()
		);

		return $database->insertRow(TABLE_PREFIX.'comment', $row);
	}

	/**
	 * Deletes a comment.
	 * @param int $commentid The ID of the comment to be deleted.
	 * @return boolean true if successful, false otherwise
	 */
	public function deleteComment($commentid) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$database = SBFactory::Database();

		SBEventObserver::fire(new SBEvent(array('id' => $commentid), SBEvent::ON_COMMENT_DELETE));
		$filter = array('id' => $commentid);

		return $database->deleteRows(TABLE_PREFIX.'comment', $filter);
	}
}
