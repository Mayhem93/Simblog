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

/**
 * Get the first posts.
 * @param int $page 
 * @return mixed array|boolean An associative array with the results or false if query failed.
 */
function blog_getPosts($page=1) {
	$database = SBFactory::Database();

	$nr_posts = SBFactory::Settings()->getSetting("no_posts_per_page");
	$results = $database->selectRows(DB_TABLE_PREFIX.'post', "*", null, null, 'DESC', 'id', ($page-1)*$nr_posts, $nr_posts);
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
	return $database->selectRows(DB_TABLE_PREFIX.'post', "*", $where, null, 'DESC', 'id', ($page-1)*$nr_posts, $nr_posts);
}

/**
 * Gets a single post
 * @param int $id
 * @return array associative
 */
function blog_getPost($id) {
	$database = SBFactory::Database();

	$filter = array("id" => $id);
	$result = $database->selectRows(DB_TABLE_PREFIX."post", "*", $filter);

	return $result[0];
}

/**
 * Returns all pinned posts.
 * @return array Associative array with all the pinned posts.
 */
function blog_getPinnedPosts() {
	$database = SBFactory::Database();

	$filter = array("pinned" => 1);
	return $database->selectRows(DB_TABLE_PREFIX."post", "*", $filter);
}
/**
 * See if a post is pinned.
 * @param int $id
 * @return boolean True if it's pinned, false otherwise.
 */
function blog_postIsPinned($id) {
	$database = SBFactory::Database();

	$query = "SELECT `pinned` FROM `".DB_TABLE_PREFIX."post` WHERE `id` = '$id';";

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

	$result = $database->selectRows(DB_TABLE_PREFIX."category", "*");
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
	$result = $database->selectRows(DB_TABLE_PREFIX."comment", "*", $where);
	
	return $result[0];
}

/**
 * Gets number of comments for a post.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return int Number of comments.
 */
function blog_getCommentsNumber($postid) {
	$database = SBFactory::Database();

	$filter = array("post_id" => $postid);
	return $database->countRows(DB_TABLE_PREFIX."comment", $filter);
}

/**
 * Gets all the posts to arrange them in the archive section.
 * @return array An associative array in the form: $result[year][month][post]
 */
function blog_getPostArchives() {
	$database = SBFactory::Database();
	
	$columns = array("id","title", "utime");
	$archives = $database->selectRows(DB_TABLE_PREFIX."post", $columns,
			null, null, "DESC", "utime");
	
	$result = array();

	foreach($archives as $post)
		$result[date("Y", $post['utime'])][date("F", $post['utime'])][] = $post;
	
	return $result;
}