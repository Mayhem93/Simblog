<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 6/23/12
 * Time: 6:08 PM
 * To change this template use File | Settings | File Templates.
 */
class SBPage {

    const MESSAGE_NOTICE = 0;
    const MESSAGE_WARNING = 1;
    const MESSAGE_ERROR = 2;

	private static $_actions = array(
		'main' => array(
			'css/posts.css'
		),
		'post' => array(
			'css/posts.css',
			'css/comments.css'
		),
		'category' => array(),
		'dashboard' => array(),
		'addpost' => array(
			'js/kendo/kendo.draganddrop.js',
			'js/kendo/kendo.popup.js',
			'js/kendo/kendo.list.js',
			'js/kendo/kendo.dropdownlist.js',
			'js/kendo/kendo.combobox.js',
			'js/kendo/kendo.window.js',
			'js/kendo/kendo.editor.js'
		),
		'plugin' => array(),
		'modifyPost' => array(
			'js/kendo/kendo.draganddrop.js',
			'js/kendo/kendo.popup.js',
			'js/kendo/kendo.list.js',
			'js/kendo/kendo.dropdownlist.js',
			'js/kendo/kendo.combobox.js',
			'js/kendo/kendo.window.js',
			'js/kendo/kendo.editor.js'
		)
	);

	private $_cssfile = null;
	private $_jsfile = null;

	/**
	 * @var SBPlugin_Controller
	 */
	private $_loadedPlugins;
	private $_loadedResources = array('css' => array(), 'js' => array());
	private $_pageAction;
    private $_templateFile;
    private $_messagesVisible = true;
    private $_messages = array();

	public function __construct($action = null, $templateFile = 'index.tpl') {
		$this->_pageAction = isset($action) ? $action : $_GET['action'];
        $this->_templateFile = $templateFile;

		if (!$this->isValidAction())
			throw new Exception("$action is not a valid action.");

		if (empty($_POST)) {
			$commonDefaultResources = array(
				'css/common.css',
				'css/bootstrap.css',
				'css/bootstrap-responsive.css',
				'css/kendo/kendo.common.css',
				'css/kendo/kendo.blueopal.css',
				'js/jquery-1.7.2.js',
				'js/bootstrap.js',
				'js/kendo/kendo.core.js',
				'js/kendo/kendo.fx.js',
				'js/kendo/kendo.treeview.js',
				'js/kendo/kendo.draganddrop.js',
				'js/kendo/kendo.resizable.js',
				'js/kendo/kendo.window.js',
				'js/common.js'
			);

			$this->addResource($commonDefaultResources);
			$this->addResource(self::$_actions[$this->_pageAction]);
		}

	}

	public function run() {

		if (count($_POST))
            $this->postAction();
        else
            $this->getAction();

		$this->loadPlugins();

		return $this;
	}

	public function display() {
		try {
			SBFactory::Template()->display($this->_templateFile);
		} catch (SmartyException $e) {
			echo $e->getMessage();
		}

		return ;
	}

	public function getResources(){
		return $this->_loadedResources;
	}

	public function addResource(array $resources) {
		foreach ($resources as $file) {
			if (substr($file, -4) == '.css' && !in_array($file, $this->_loadedResources['css']))
				$this->_loadedResources['css'][] = $file;
			else if (substr($file, -3) == '.js' && !in_array($file, $this->_loadedResources['js']))
				$this->_loadedResources['js'][] = $file;
		}

		return;
	}

	private function getAction() {

		SBFactory::Template()->assign("categories", SBPost::getCategoriesList());
		SBFactory::Template()->assign("action", $this->_pageAction);
		SBFactory::Template()->assign("archives", SBPost::getPostArchives());
        SBFactory::Template()->assign("notify_msg", $this->_messages);

		switch ($this->_pageAction) {
			case 'main':
				$page = isset($_GET['page']) ? $_GET['page'] : 1;

				SBFactory::Template()->assign("blog_posts", SBPost::getPostsList($page));
				SBFactory::Template()->assign("page", $page);
				SBFactory::Template()->assign("page_title", SBFactory::Settings()->getSetting("blog_title"));
				SBFactory::Template()->assign("page_template", "main.tpl");

				break;

			case 'post':
				$post = new SBPost($_GET['id']);
				$comments = $post->getComments();

				SBFactory::Template()->assign('post', $post);
				SBFactory::Template()->assign('commentCount', count($comments));
				SBFactory::Template()->assign('comments', $comments);
				SBFactory::Template()->assign('page_title', $post['title'] . " - " .
					SBFactory::Settings()->getSetting('blog_title'));
				SBFactory::Template()->assign('page_template', "post_page.tpl");

				break;

			case 'category':
				$category = urldecode($_GET['name']);
				$page = isset($_GET['page']) ? $_GET['page'] : 1;
				$post = SBPost::getPostsList($page, $category);

				SBFactory::Template()->assign("blog_posts", $post);
				SBFactory::Template()->assign("page", $page);
				SBFactory::Template()->assign("page_title", $category . " Category - " .
					SBFactory::Settings()->getSetting("blog_title"));
				SBFactory::Template()->assign("page_template", "main.tpl");

				break;

			case 'addPost':
				SBFactory::Template()->assign("page_title", "New post - " .
					SBFactory::Settings()->getSetting("blog_title"));
				SBFactory::Template()->assign("page_template", "add_post.tpl");

				break;

			case 'modifyPost':
				$post = new SBPost($_GET['id']);

				SBFactory::Template()->assign("post", $post);
				SBFactory::Template()->assign("page_title", "Modify Post - " . SBFactory::Settings()->getSetting("blog_title"));
				SBFactory::Template()->assign("page_template", "modify_post.tpl");

				break;
		}

		SBFactory::Template()->assign("cssFile", $this->getCSSfile());
		SBFactory::Template()->assign("jsFile", $this->getJSfile());

		return;
	}

	private function postAction() {
		switch ($this->_pageAction) {
			case 'post':

				SBFactory::getCurrentUser()->addComment($_GET['post_id'], $_POST['commentBody'], $_POST['commentName'], $_POST['email']);
				header("Location: /?action=post&id=" . $_GET['post_id']);
				exit();

				break;

			case 'addpost':
				$pinned = isset($_POST['pinned']) ? 1 : 0;
				$tags = isset($_POST['tags']) ? $_POST['tags'] : '';
				$category = (!isset($_POST['category'])) ? "" : $_POST['category'];

				SBFactory::getCurrentUser()->addPost($_POST['title'], $_POST['content'], $category, $tags, $pinned);
				redirectMainPage();

				break;

			case 'modifypost':
				$post_id = $_POST['post_id'];
				$content = $_POST['post_content'];
				$title = $_POST['post_title'];
				$category = ($_POST['category'] == "no categories available") ? "" : $_POST['category'];

				SBFactory::getCurrentUser()->modifyPost($post_id, $title, $content, $category);
				redirectMainPage();

				break;
		}
	}

    public function addNotifyMessage($message, $severity) {
        if ($this->_messagesVisible) {
            $this->_messages[] = array('message' => $message, 'severiy' => $severity);

            return true;
        }

        return false;
    }

    public function isNotifyVisible() {
        return $this->_messagesVisible;
    }

    public function setNotifyVisiblity($value) {
        if ($value == true) {
            $this->_messagesVisible = true;
        } else {
            $this->_messagesVisible = false;
        }
    }

	public function getCSSfile() {
		if ($this->_cssfile !== null)
			return $this->_cssfile;

		//sort($this->_loadedResources['css']);
		$cacheFileName = md5(implode('', $this->_loadedResources['css'])).'.css';

		$old = false;

		if (file_exists(CSS_CACHE_DIR.$cacheFileName)) {
			foreach($this->_loadedResources['css'] as $css) {
				if (filemtime(CSS_CACHE_DIR.$cacheFileName) < filemtime(BLOG_PUBLIC_ROOT.'/'.$css)) {
					$old = true;
					break;
				}

			}
			if (!$old)
				return $cacheFileName;
		}

		$stylesheet = '';

		foreach($this->_loadedResources['css'] as $css) {
			if ( file_exists(BLOG_PUBLIC_ROOT.'/'.$css) )
				$stylesheet .= file_get_contents(BLOG_PUBLIC_ROOT.'/'.$css);
			else
				throw new Exception("Resource file \"$css\" does not exist.");
		}

		file_put_contents(CSS_CACHE_DIR.$cacheFileName, $stylesheet);
		$this->_cssfile = $cacheFileName;

		return $cacheFileName;
	}

	public function getJSfile() {
		if ($this->_jsfile !== null)
			return $this->_jsfile;

		$javascript = '';
		$cacheFileName = md5(implode('', $this->_loadedResources['js'])).'.js';

        $old = false;

        if (file_exists(JS_CACHE_DIR.$cacheFileName)) {
            foreach($this->_loadedResources['js'] as $js) {
                if (filemtime(JS_CACHE_DIR.$cacheFileName) < filemtime(BLOG_PUBLIC_ROOT.'/'.$js)) {
                    $old = true;
                    break;
                }

            }
            if (!$old)
                return $cacheFileName;
        }

		foreach($this->_loadedResources['js'] as $js) {
			if ( file_exists($js) )
				$javascript .= file_get_contents($js);
			else
				throw new Exception("Resource file \"$js\" does not exist.");
		}

		require_once BLOG_ROOT.'/libs/Minifier.class.php';
		file_put_contents(JS_CACHE_DIR.$cacheFileName, \JShrink\Minifier::minify($javascript));
		$this->_jsfile = $cacheFileName;

		return $cacheFileName;
	}

	public function getActionName() {
		return $this->_pageAction;
	}

	private function loadPlugins() {
		$this->_loadedPlugins = SBFactory::PluginManager();

		return;
	}

	private function isValidAction() {
		return isset(self::$_actions[$this->_pageAction]);
	}

}
