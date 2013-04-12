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

		return SBPost::createPost($title, $content, $category, $tags, $pinned = false);
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

		return SBPost::deletePost($id);
	}

	/**
	 * Modifies a post.
	 * @param int $id
	 * @param string $title The new title.
	 * @param string $content The new content.
	 * @param string $category The new category.
	 * @param string $tags
	 */
	public function modifyPost($id, $title=null, $content=null, $category=null, array $tags=null) {
		if (!$this->_isAdmin) {
			setHTTP(HTTP_UNAUTHORIZED);
		}

		$post = new SBPost($id);
		if ($title)
			$post->title = $title;
		if ($content)
			$post->content = $content;
		if ($category)
			$post->category = $category;
		if ($tags)
			$post->tags = $tags;

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

		return SBPost::addComment($postid, $author, $email, $content);
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

		return SBPost::deleteComment($commentid);
	}
}
