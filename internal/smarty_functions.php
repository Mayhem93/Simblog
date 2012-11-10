<?php

function smarty_isAdmin() {
	return SBFactory::getCurrentUser()->isAdmin();
}

function smarty_getSetting($setting) {
	return SBFactory::Settings()->getSetting($setting);
}

function smarty_isLastPage($page_number) {
	$total_pages = ceil(SBFactory::Database()->countRows("post")/
					SBFactory::Settings()->getSetting("no_posts_per_page"));
	return ($total_pages == $page_number);
}