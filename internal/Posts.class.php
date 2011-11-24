<?php

class Posts {
	
	public static function addPost($title, $content, $category, array $tags, $pinned = false) {
		global $simblog;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else {
			if(!is_writable(POSTS_DIR))
				throwError("Error: ".POSTS_DIR." is not writeable.");

			if($pinned)
				$post_file = fopen(POSTS_DIR."/pinned/".time().".json", "w");
			else 
				$post_file = fopen(POSTS_DIR."/".time().".json", "w");
			
			$json = array();
			$json['title'] = $title;
			$json['content'] = BBCode::parse($content);
			$json['category'] = $category;
			$json['tags'] = $tags;
			$json['date'] = date("d F Y, g:i:s a");
			$json = json_encode($json);
			fwrite($post_file, $json);
			fclose($post_file);
		}
	}
	public static function deletePost($id) {
		global $simblog;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else {
			if(self::isPinned($id))
				unlink(POSTS_DIR."/pinned/{$id}.json");
			else
				unlink(POSTS_DIR."/{$id}.json");
		}
	}
	public static function modifyPost($id, $title=null, $content=null, $category=null, $tags=null) {
		global $simblog;
		
		if($title == null && $content == null)
			return;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else {
			if(self::isPinned($id)) {
				$post_string = POSTS_DIR."/pinned/{$id}.json";
				$post_file = fopen($post_string,"w");
			}
			else {
				$post_string = POSTS_DIR."/{$id}.json";
				$post_file = fopen($post_string,"w");
			}
			$json = file_get_contents($post_string);
			$json = json_decode($json,true);
			if($title)
				$json['title'] = $title;
			if($content)
				$json['content'] = BBCode::parse($content);
			if($category)
				$json['category'] = $category;
			if($tags)
				$json['tags'] = $tags;
			$json['last_modified'] = date("d F Y, g:i:s a");
			fwrite($post_file, json_encode($json));
			fclose($post_file);
		}
		
	}
	public static function togglePinPost($id) {
		global $simblog;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else {
			if(self::isPinned($id))
				rename(POSTS_DIR."/pinned/{$id}.json", POSTS_DIR."/{$id}.json");
			else
				rename(POSTS_DIR."/{$id}.json", POSTS_DIR."/pinned/{$id}.json");
		}
	}
	public static function getPosts($page=1) {
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
				$posts[] = json_decode(file_get_contents(POSTS_DIR."/{$file}.json"));
			return $posts;
		}
	}
	public static function getPinnedPosts() {
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
				$posts[] = json_decode(file_get_contents(POSTS_DIR."/pinned/{$file}.json"));
			return $posts;
		}
		
	}
	private static function isPinned($id) {
		global $simblog;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else
			return file_exists(POSTS_DIR."/pinned/{$id}.json");
	}
	public static function getComments($postid) {
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
				$comments[] = json_decode(file_get_contents(COMMENTS_DIR."/{$postid}/{$file}.json"));
			return $comments;
		}
	}
	public static function addComment($postid, $content, $author){}
	public static function deleteComment($commentid){}
}