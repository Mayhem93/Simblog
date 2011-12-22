<?php
	
function blog_addPost($title, $content, $category, $pinned = false) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
function blog_deletePost($id) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
	}
	else {
		if(blog_postIsPinned($id))
			unlink(POSTS_DIR."/pinned/{$id}.json");
		else
			unlink(POSTS_DIR."/{$id}.json");
	}
}
function blog_modifyPost($id, $title=null, $content=null, $category=null) {
	global $simblog;
	
	if($title == null && $content == null)
		return;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
function blog_togglePinPost($id) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
	}
	else {
		if(blog_postIsPinned($id))
			rename(POSTS_DIR."/pinned/{$id}.json", POSTS_DIR."/{$id}.json");
		else
			rename(POSTS_DIR."/{$id}.json", POSTS_DIR."/pinned/{$id}.json");
	}
}
function blog_getPosts($page=1) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
			$posts[] = json_decode(file_get_contents(POSTS_DIR."/{$file}.json"),true);
		return $posts;
	}
}
function blog_getPinnedPosts() {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
			$posts[] = json_decode(file_get_contents(POSTS_DIR."/pinned/{$file}.json"),true);
		return $posts;
	}
	
}
function blog_postIsPinned($id) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
	}
	else
		return file_exists(POSTS_DIR."/pinned/{$id}.json");
}
function blog_getComments($postid) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
function blog_addComment($postid, $content, $author) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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
function blog_deleteComment($commentid, $postid=null) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
	}
	else 
		unlink(COMMENTS_DIR."/{$postid}/{$commentid}.json");
}

function blog_ratePost($id, $positive=true) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
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

function blog_rateComment($id, $post_id, $positive=true) {
	global $simblog;
	
	if($simblog['conf']['database_support']) {
		//TODO
	}
	else {
		$json = json_decode(file_get_contents(COMMENTS_DIR."/{$post_id}/{$id}.json"),true);
		$positive ? $json['rating']++ : $json['rating']--;
		file_put_contents(COMMENTS_DIR."/{$post_id}/{$id}.json", json_encode($json));
	}
}