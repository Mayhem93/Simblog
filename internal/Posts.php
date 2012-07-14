<?php
/**
* This file contains all the functions that handle blog content.
*
* @author		Răzvan Botea<utherr.ghujax@gmail.com>
* @license 		http://www.gnu.org/licenses/gpl.txt
* @copyright	2011-2012 Răzvan Botea
*
* 	PHP 5
*
*	This file is part of Simblog.
*
*   Simblog is free software: you can redistribute it and/or modify
*   it under the terms of the GNU General Public License as published by
*   the Free Software Foundation, either version 3 of the License, or
*   (at your option) any later version.
*
*   Simblog is distributed in the hope that it will be useful,
*   but WITHOUT ANY WARRANTY; without even the implied warranty of
*   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*   GNU General Public License for more details.
*
*   You should have received a copy of the GNU General Public License
*   along with Simblog.  If not, see <http://www.gnu.org/licenses/>.
*/	

DEFINE("TABLE_PREFIX", SBFactory::Settings()->getSetting("tbl_prefix"));

/**
 * Adds a post.
 * @param string $title Title
 * @param string $content Content
 * @param string $category Category
 * @param string $tags Space delimited string of tags
 * @param boolean $pinned Pinned posts will show up first
 * @return boolean true if successful
 */
function blog_addPost($title, $content, $category, $tags, $pinned = false) {
	$database = SBFactory::Database();

	$row = array(
		"title" => $title,
		"pinned" => $pinned ? 1 : 0,
		"category" => $category,
		"tags" => $tags,
		"content" => $content,
		"utime" => time()
	);

	$eventData = array(
		"title" => &$title,
		"content" => &$content,
		"category" => &$category,
		"tags" => &$tags,
		"pinned" => &$pinned,
		"utime" => time());
	SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_ADD));

	return $database->insertRow(TABLE_PREFIX."post", $row);
}
/**
 * Delets a post.
 * @param int $id
 * @return boolean True if successful, false otherwise.
 */
function blog_deletePost($id) {
	$database = SBFactory::Database();
	
	$eventData = array("id" => $id);
	SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_DELETE));

	$filter = array("id" => $id);

	return $database->deleteRows(TABLE_PREFIX."post", $filter);
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
function blog_modifyPost($id, $title=null, $content=null, $category=null, $tags=null) {
	$database = SBFactory::Database();
	
	if ($title == null && $content == null && $category == null)
		return false;
	
	$eventData = array(
			"id" => $id,
			"title" => &$title,
			"content" => &$content,
			"category" => &$category,
			"tags" => &$tags
			);
	SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_POST_MOD));

	$filter = array("id" => $id);
	$update_set = array();

	if ($title)
		$update_set['title'] = $title;
	if ($content)
		$update_set['content'] = $content;
	if ($category)
		$update_set['category'] = $category;
	if ($tags)
		$update_set['tags'] = $tags;

	return $database->updateRows(TABLE_PREFIX."post", $filter, $update_set);
}
/**
 * Pins a post. Pinned posts show up first on the blog.
 * @param int $id
 * @return boolean true if successful
 */
function blog_togglePinPost($id) {
	$database = SBFactory::Database();
	//TODO event on toggle pinned post

	$filter = array("id" => $id);
	$update_set = array();

	if (blog_postIsPinned($id))
		$update_set['pinned'] = '0';
	else
		$update_set['pinned'] = '1';

	return $database->updateRows(TABLE_PREFIX.'post', $filter, $update_set);
}
/**
 * Get the first posts.
 * @param int $page 
 * @return mixed array|boolean An associative array with the results or false if query failed.
 */
function blog_getPosts($page=1) {
	$database = SBFactory::Database();

	$nr_posts = SBFactory::Settings()->getSetting("no_posts_per_page");
	$results = $database->selectRows(TABLE_PREFIX.'post', "*", null, null, 'DESC', 'id', ($page-1)*$nr_posts, $nr_posts);
	if (!$results)
		return false;
	$results['tags'] = explode(' ', $results['tags']);

	return $results;
}

/**
 * Get's the first posts specified by category
 * @param string $category Category name.
 * @param int $page A segment of posts.
 * @return boolean True if successful, false otherwise
 */
function blog_getPostsByCategory($category, $page=1) {
	$database = SBFactory::Database();
	$nr_posts = SBFactory::Settings()->getSetting("no_posts_per_page");

	$where = array('category' => $category);
	return $database->selectRows(TABLE_PREFIX.'post', "*", $where, null, 'DESC', 'id', ($page-1)*$nr_posts, $nr_posts);
}

/**
 * Gets a single post
 * @param int $id
 * @return array associative
 */
function blog_getPost($id) {
	$database = SBFactory::Database();

	$filter = array("id" => $id);
	$result = $database->selectRows(TABLE_PREFIX."post", "*", $filter);

	return $result[0];
}

/**
 * Returns all pinned posts.
 * @return array Associative array with all the pinned posts.
 */
function blog_getPinnedPosts() {
	$database = SBFactory::Database();

	$filter = array("pinned" => 1);
	return $database->selectRows(TABLE_PREFIX."post", "*", $filter);
}
/**
 * See if a post is pinned.
 * @param int $id
 * @return boolean True if it's pinned, false otherwise.
 */
function blog_postIsPinned($id) {
	$database = SBFactory::Database();

	$query = "SELECT `pinned` FROM `".TABLE_PREFIX."post` WHERE `id` = '$id';";

	$result = $database->querySingleValue($query);
	if($result == "1")
		return true;
	else if($result == "0")
		return false;
}

/**
 * Gets all the categories.
 * @return mixed The array with the categories or false if no category found.
 */
function blog_getCategories() {
	$database = SBFactory::Database();

	$result = $database->selectRows(TABLE_PREFIX."category", "*");
	return count($result) ? $result : false;
}

/**
 * Gets all the comments from a post.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return array Associative array with the results. 
 */
function blog_getComments($postid) {
	$database = SBFactory::Database();

	$where = array("post_id" => $postid);

	return $database->selectRows("comment", "*", $where, null, "DESC", "id");
}

/**
 * Gets a comment specified by ID.
 * @param int $id Id of the comment.
 * @return array Associative array with the comment.
 */
function blog_getCommentById($id) {
	$database = SBFactory::Database();
	$where = array("id" => $id);
	$result = $database->selectRows(TABLE_PREFIX."comment", "*", $where);
	
	return $result[0];
}

/**
 * Adds a comment to the post ID.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @param string $content Comment content.
 * @param string $author Comment author.
 * @param string $email
 * @return boolean true if query successful
 */
function blog_addComment($postid, $content, $author, $email) {
	$database = SBFactory::Database();
	$eventData = array(
			"post_id" => $postid,
			"content" => &$content,
			"author" => &$author,
			"email" => &$email,
			"utime" => time());
	SBEventObserver::fire(new SBEvent($eventData, SBEvent::ON_USER_COMMENT));
	$row = array(
		"post_id" => $postid,
		"author"	=>	$author,
		"email" => $email,
		"ip"	=> $_SERVER['REMOTE_ADDR'],
		"content"	=> $content,	//TODO verify content by using DOM extension
		"utime"	=> time()
	);

	return $database->insertRow(TABLE_PREFIX."comment", $row);
}
/**
 * Deletes a comment.
 * @param int $commentid The ID of the comment to be deleted.
 * @return boolean true if successful, false otherwise
 */
function blog_deleteComment($commentid) {
	$database = SBFactory::Database();
	
	SBEventObserver::fire(new SBEvent(array("id" => $commentid), SBEvent::ON_COMMENT_DELETE));
	$filter = array("id" => $commentid);

	return $database->deleteRows(TABLE_PREFIX."comment", $filter);
}

/**
 * Gets number of comments for a post.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return int Number of comments.
 */
function blog_getCommentsNumber($postid) {
	$database = SBFactory::Database();

	$filter = array("post_id" => $postid);
	return $database->countRows(TABLE_PREFIX."comment", $filter);
}

/**
 * Gets all the posts to arrange them in the archive section.
 * @return array An associative array in the form: $result[year][month][post]
 */
function blog_getPostArchives() {
	$database = SBFactory::Database();
	
	$columns = array("id","title", "utime");
	$archives = $database->selectRows(TABLE_PREFIX."post", $columns, 
			null, null, "DESC", "utime");
	
	$result = array();

	foreach($archives as $post)
		$result[date("Y", $post['utime'])][date("F", $post['utime'])][] = $post;
	
	return $result;
}