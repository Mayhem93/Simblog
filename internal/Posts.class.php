<?php

class Posts {
	
	public static function addPost($title, $content, $pinned = false) {
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
			if(Posts::isPinned($id))
				unlink(POSTS_DIR."/pinned/{$id}.json");
			else
				unlink(POSTS_DIR."/{$id}.json");
		}
	}
	public static function modifyPost($id, $title=null, $content=null) {
		global $simblog;
		
		if($title == null && $content == null)
			return;
		
		if($simblog['conf']['database_support']) {
			//TODO
		}
		else {
			if(Posts::isPinned($id)) {
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
			$json['last_modified'] = date("d F Y, g:i:s a");
			fwrite($post_file, json_encode($json));
			fclose($post_file);
		}
		
	}
	public static function togglePinnedPost($id) {}
	public static function getPosts($count = null) {}
	public static function getPinnedPosts() {}
	private static function isPinned($id) {}
	public static function getCommentsNumber($id) {}
}