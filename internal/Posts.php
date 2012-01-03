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
 * Adds a post.
 * @param string $title Title
 * @param string $content Content
 * @param string $category Category
 * @param boolean $pinned Pinned posts will show up first
 */
function blog_addPost($title, $content, $category, $pinned = false) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$row = array(
			"rating" => 0,
			"title" => $title,
			"pinned" => $pinned ? 1 : 0,
			"category" => $category,
			"content" => $content,
			"date_posted" => date("d F Y, g:i:s a")
		);
		$database->insertRow("post", $row);
	}
	else {
		if(!is_writable(POSTS_DIR))
			throwError("Error: ".POSTS_DIR." is not writeable.");

		if($pinned)
			$post_file = POSTS_DIR."/pinned/".time().".json";
		else 
			$post_file = POSTS_DIR."/".time().".json";
		
		$json = array();
		$json['title'] = $title;
		$json['rating'] = 0;
		$json['content'] = BBCode::parse($content);
		$json['category'] = $category;
		$json['date'] = date("d F Y, g:i:s a");
		file_put_contents($post_file, json_encode($json));
	}
}
/**
 * Delets a post.
 * @param int $id The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 */
function blog_deletePost($id) {
	$database = SBFactory::Database();
	
	if (SBFactory::Settings()->getSetting("database_support")) {
		$filter = array("id" => $id);
		$database->deleteRows("post", $filter);
	} else {
		if (blog_postIsPinned($id)) //TODO: delete all the comments with the post id.
			unlink(POSTS_DIR."/pinned/{$id}.json");
		else
			unlink(POSTS_DIR."/{$id}.json");
	}
}
/**
 * Modifies a post.
 * @param int $id The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @param string $title The new title.
 * @param string $content The new content.
 * @param string $category The new category.
 * @uses The last three parameters cannot be null at the same time.
 */
function blog_modifyPost($id, $title=null, $content=null, $category=null) {
	$database = SBFactory::Database();
	
	if($title == null && $content == null && $category == null)
		return;
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$filter = array("id" => $id);
		$update_set = array();
		if($title)
			$update_set['title'] = $title;
		if($content)
			$update_set['content'] = $content;
		if($category)
			$update_set['category'] = $category;
		
		$database->updateRows("post", $filter, $update_set);
	}
	else {
		if(blog_postIsPinned($id))
			$post_string = POSTS_DIR."/pinned/{$id}.json";
		else 
			$post_string = POSTS_DIR."/{$id}.json";
		
		$json = file_get_contents($post_string);
		$json = json_decode($json,true);
		if($title)
			$json['title'] = $title;
		if($content)
			$json['content'] = BBCode::parse($content);
		if($category)
			$json['category'] = $category;
		$json['last_modified'] = date("d F Y, g:i:s a");
		file_put_contents($post_string,json_encode($json));
	}
	
}
/**
 * Pins a post. Pinned posts show up first on the blog.
 * @param int $id The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 */
function blog_togglePinPost($id) {
	$database = SBFactory::Database();
	
	if (SBFactory::Settings()->getSetting("database_support")) {
		
		if (blog_postIsPinned($id))
			$query = "UPDATE `post` SET `pinned` = '0' WHERE `id`='$id'";
		else
			$query = "UPDATE `post` SET `pinned` = '1' WHERE `id`='$id'";
		
		return $database->query($query);
	}
	else {
		if (blog_postIsPinned($id))
			rename(POSTS_DIR."/pinned/{$id}.json", POSTS_DIR."/{$id}.json");
		else
			rename(POSTS_DIR."/{$id}.json", POSTS_DIR."/pinned/{$id}.json");
	}
}
/**
 * Get the first posts.
 * @param int $page 
 * @return array An associative array with the results.
 */
function blog_getPosts($page=1) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$nr_posts = SBFactory::Settings()->getSetting("no_posts_per_page");
		
		$query = "SELECT * FROM `post` ORDER BY `id` DESC LIMIT ".(($page-1)*$nr_posts).", {$nr_posts};";
		
		return $database->query($query);
	}
	else {
		$dir = new DirectoryIterator(POSTS_DIR);
		$postFiles = array();
		foreach($dir as $d)
			if(!$d->isDir() && substr((string)$d, -5) == '.json')
				$postFiles[] = intval((string)$d);
		rsort($postFiles, SORT_NUMERIC);
		$postFiles = array_slice($postFiles, -($simblog['conf']['no_posts_per_page']*$page));
		$posts = array();
		foreach($postFiles as $file)
			$posts[$file] = json_decode(file_get_contents(POSTS_DIR."/{$file}.json"),true);
		return $posts;
	}
}
/**
 * Returns all pinned posts.
 * @return array Associative array with all the pinned posts.
 */
function blog_getPinnedPosts() {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$filter = array("pinned" => 1);
		
		return $database->selectRows("post", "*", $filter);
	}
	else {
		$dir = new DirectoryIterator(POSTS_DIR."/pinned");
		$postFiles = array();
		foreach($dir as $d)
			if(!$d->isDir() && substr((string)$d, -5) == '.json')
				$postFiles[] = intval((string)$d);
		rsort($postFiles, SORT_NUMERIC);
		$postFiles = array_slice($postFiles, -$simblog['conf']['no_posts_per_page']);
		$posts = array();
		foreach($postFiles as $file)
			$posts[$file] = json_decode(file_get_contents(POSTS_DIR."/pinned/{$file}.json"),true);
		return $posts;
	}
	
}
/**
 * See if a post is pinned.
 * @param int $id The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return boolean True if it's pinned, false otherwise.
 */
function blog_postIsPinned($id) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$query = "SELECT `pinned` FROM `post` WHERE `id` = '$id';";
		
		$result = $database->querySingleValue($query);
		if($result == "1")
			return true;
		else if($result == "0")
			return false;
	}
	else
		return file_exists(POSTS_DIR."/pinned/{$id}.json");
}
/**
 * Gets all the comments from a post.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return array Associative array with the results. 
 */
function blog_getComments($postid) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$where = array("post_id" => $postid);
		$query = "SELECT * FROM `comment` WHERE `post_id`='$postid' ORDER BY `id` DESC;";
		
		return $database->selectRows("comment", "*", $where, null, "DESC", "id");
	}
	else {
		$dir = new DirectoryIterator(COMMENTS_DIR."/{$postid}");
		$commentFiles = array();
		foreach($dir as $d) {
			if(!$d->isDir() && substr((string)$d, -5) == '.json')
				$commentFiles[] = intval((string)$d);
		}
		rsort($commentFiles, SORT_NUMERIC);
		$comments = array();
		foreach($commentFiles as $file) 
			$comments[] = json_decode(file_get_contents(COMMENTS_DIR."/{$postid}/{$file}.json"),true);
		return $comments;
	}
}
/**
 * Adds a comment to the post ID.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @param string $content Comment content.
 * @param string $author Comment author.
 */
function blog_addComment($postid, $content, $author) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$row = array(
			"post_id" => $postid,
			"rating" => 0,
			"name"	=>	$author,
			"ip"	=> $_SERVER['REMOTE_ADDR'],
			"text"	=> $content,
			"date"	=> date("d F Y, g:i:s a")
		);
		
		$database->insertRow("comment", $row);
	}
	else {
		$comment_id = time();
		$json = array();
		
		$json['content'] = $content;
		$json['author'] = $authot;
		$json['rating'] = 0;
		$json['ip'] = $_SERVER['REMOTE_ADDR'];
		$json['date'] = date("d F Y, g:i:s a");
		
		file_put_contents(COMMENTS_DIR."/{$postid}/{$comment_id}.json", json_encode($json));
	}
	
}
/**
 * Deletes a comment.
 * @param int $commentid The ID of the comment to be deleted.
 * @param int $postid (optional) Only used for no-mysql support.
 */
function blog_deleteComment($commentid, $postid=null) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		$filter = array("id" => $commentid);
		
		$database->deleteRows("comment", $filter);
	}
	else 
		unlink(COMMENTS_DIR."/{$postid}/{$commentid}.json");
}

/**
 * Gets number of comments for a post.
 * @param int $postid The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @return int Number of comments.
 */
function blog_getCommentsNumber($postid) {
	$database = SBFactory::Database();
	
	if (SBFactory::Settings()->getSetting("database_support")) {
		$filter = array("post_id" => $postid);
		
		return $database->countRows("comment", $filter);
	}
	else {
		$dir = new DirectoryIterator(COMMENTS_DIR."/{$postid}");
		$count = 0;
		foreach($dir as $d)
			if($d->isFile())
				$count++;
		return $count;
	}
}

/**
 * Rates a post.
 * @param int $id The post ID. For no-mysql support this is the filename of the post (which is a unix timestamp).
 * @param bool $positive True if it's a positive rate, false if it's a negative rating.
 */
function blog_ratePost($id, $positive=true) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		if($positive)
			$sql = "UPDATE `post` SET rating=rating+1 WHERE `id` = '$id';";
		else
			$sql = "UPDATE `post` SET rating=rating-1 WHERE `id` = '$id';";
		
		$database->query($sql);
	}
	else {
		if(blog_postIsPinned($id))
			$post_file = POSTS_DIR."/pinned/".$id.".json";
		else
			$post_file = POSTS_DIR."/".$id.".json";
		
		$json = json_decode(file_get_contents($post_file),true);
		$positive ? $json['rating']++ : $json['rating']--;
		file_put_contents($post_file,json_encode($json));
	}
		
}

/**
 * Rates a comment
 * @param int $id The ID of the comment.
 * @param int $post_id (optional) Only used by no-mysql support.
 * @param bool $positive 
 */
function blog_rateComment($id, $post_id=null, $positive=true) {
	$database = SBFactory::Database();
	
	if(SBFactory::Settings()->getSetting("database_support")) {
		if($positive)
			$sql = "UPDATE `comment` SET rating=rating+1 WHERE `id` = '$id';";
		else
			$sql = "UPDATE `comment` SET rating=rating-1 WHERE `id` = '$id';";
		
		$database->query($sql);
	}
	else {
		$json = json_decode(file_get_contents(COMMENTS_DIR."/{$post_id}/{$id}.json"),true);
		$positive ? $json['rating']++ : $json['rating']--;
		file_put_contents(COMMENTS_DIR."/{$post_id}/{$id}.json", json_encode($json));
	}
}