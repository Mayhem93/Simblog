<?php

class Posts {
	
	public static function addPost($title, $content) {
		global $simblog;
		
		if($simblog['conf']['database_support']) {
			
		}
		else {
			$content = BBCode::parse($content);
		}
	}
	public static function deletePost($id) {}
	public static function modifyPost($id) {}
	public static function togglePinPost($id) {}
	public static function retrievePosts($count = null) {}
}