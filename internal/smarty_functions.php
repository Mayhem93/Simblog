<?php

function smarty_isAdminSession() {
	return isset($_SESSION[$_SERVER['REMOTE_ADDR']]['admin']);
}

function smarty_getSetting($setting) {
	return SBFactory::Settings()->getSetting($setting);
}

function smarty_isLastPage($page_number) {
	$total_pages = ceil(SBFactory::Database()->countRows("post")/
					SBFactory::Settings()->getSetting("no_posts_per_page"));
	return ($total_pages == $page_number);
}

function smarty_prepareDisplayContent($string) {
	return removeBreaksFromLists(str_replace("\r\n", "<br>", $string));
}

SBFactory::Template()->registerPlugin("modifier", "process", "smarty_prepareDisplayContent");