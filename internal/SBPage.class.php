<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Kingaa
 * Date: 6/23/12
 * Time: 6:08 PM
 * To change this template use File | Settings | File Templates.
 */
class SBPage
{
	private static $_actions = array(
		'main' => array(

		),
		'post' => array(),
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
	private $_loadedResources;
	private $_pageAction;

	public function __construct($action = null) {
		$this->_pageAction = isset($action) ? $action : $_GET['action'];

		if (!$this->isValidAction())
			throw new Exception("$action is not a valid action.");
		$this->_pageAction = $action;
		$this->loadPluginResources();

		if (count($_POST))
			$this->postAction();
		else if (isset($_GET['action']))
			$this->getAction();

	}

	public function run() {
		try {
			/*$requestParamsCSS = '';
			$requestParamsJS = '';

			foreach ($this->_loadedResources['css'] as $file)
				$requestParamsCSS .= 'css[]=' . $file . '&';
			foreach ($this->_loadedResources['js'] as $file)
				$requestParamsJS .= 'js[]=' . $file . '&';

			SBFactory::Template()->assign('cssParams', trim($requestParamsCSS, '&'));
			SBFactory::Template()->assign('jsParams', trim($requestParamsJS, '&'));*/
			SBFactory::Template()->display('index.tpl');
		} catch (SmartyException $e) {
			echo $e->getMessage();
		}

		return;
	}

	public function getResources(){
		return $this->_loadedResources;
	}

	public function addResource(array $resources) {
		foreach ($resources as $file) {
			if (substr($file, -4) == '.css')
				$this->_loadedResources['css'][] = $file;
			else if (substr($file, -3) == '.js')
				$this->_loadedResources['js'][] = $file;
		}

		return;
	}

	private function getAction() {
		$commonDefaultResources = array(
			'css/bootstrap-responsive.css',
			'css/common.css',
			'css/kendo/kendo.commmon.css',
			'css/kendo/kendo.blueopal.css',
			'js/jquery-1.7.2.js',
			'js/common.js',
			'js/kendo/kendo.core.js',
			'js/kendo/kendo.fx.js',
			'js/kendo/kendo.treeview.js'
		);

		$this->addResource($commonDefaultResources);
		$this->addResource(self::$_actions[$this->_pageAction]);
		switch ($this->_pageAction) {
			case 'main':
				$page = isset($_GET['page']) ? $_GET['page'] : 1;

				SBFactory::Template()->assign("blog_posts", blog_getPosts($page));
				SBFactory::Template()->assign("page", $page);
				SBFactory::Template()->assign("page_title", SBFactory::Settings()->getSetting("blog_title"));
				SBFactory::Template()->assign("page_template", "main.tpl");

				break;

			case 'post':
				$post = blog_getPost($_GET['id']);
				$comments = blog_getComments($_GET['id']);

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
				$post = blog_getPostsByCategory($category, $page);

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
				$post = blog_getPost($_GET['id']);

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

				blog_addComment($_GET['post_id'], $_POST['commentBody'], $_POST['commentName'], $_POST['email']);
				header("Location: /?action=post&id=" . $_GET['post_id']);
				exit();

				break;

			case 'addpost':
				$pinned = isset($_POST['pinned']);
				//Hardcoded -- needs to go !
				$category = (!isset($_POST['category'])) ? "" : $_POST['category'];

				blog_addPost($_POST['title'], $_POST['content'], $category, $pinned);
				redirectMainPage();

				break;

			case 'modifypost':
				$post_id = $_POST['post_id'];
				$content = $_POST['post_content'];
				$title = $_POST['post_title'];
				$category = ($_POST['category'] == "no categories available") ? "" : $_POST['category'];

				blog_modifyPost($post_id, $title, $content, $category);
				redirectMainPage();

				break;
		}
	}

	public function getCSSfile() {
		if ($this->_cssfile !== null)
			return $this->_cssfile;

		sort($this->_loadedResources['css']);
		$cacheFileName = md5(implode('', $this->_loadedResources['css'])).'.css';
		if (file_exists('cache/css/'.$cacheFileName)) {
			return $cacheFileName;
		}

		$stylesheet = '';

		foreach($this->_loadedResources['css'] as $css) {
			if ( file_exists($css) )
				$stylesheet .= file_get_contents($css);
			else
				throw new Exception("Resource file \"$css\" does not exist.");
		}

		file_put_contents('cache/css/'.$cacheFileName, CssMin::minify($stylesheet));
		$this->_cssfile = $cacheFileName;

		return $cacheFileName;
	}

	public function getJSfile() {
		if ($this->_jsfile !== null)
			return $this->_jsfile;

		$javascript = '';
		sort($this->_loadedResources['js']);
		$cacheFileName = md5(implode('', $this->_loadedResources['js'])).'.js';

		if (file_exists('cache/js/'.$cacheFileName))
			return $cacheFileName;

		foreach($this->_loadedResources['js'] as $js) {
			if ( file_exists($js) )
				$javascript .= file_get_contents($js);
			else
				throw new Exception("Resource file \"$js\" does not exist.");
		}

		file_put_contents('cache/js/'.$cacheFileName, \JShrink\Minifier::minify($javascript));
		$this->_jsfile = $cacheFileName;

		return $cacheFileName;
	}

	private function loadPlugins() {
		$this->_loadedPlugins = SBFactory::PluginManager();

		return;
	}

	private function loadPluginResources() {
		foreach ($this->_loadedPlugins as $plugins) {
			if ($plugins->getPluginLocation() == $this->_pageAction)
				$this->addResource($plugins->getCSSfiles + $plugins->getJSfiles);
		}
	}

	private function isValidAction() {
		return isset(self::$_actions[$this->_pageAction]);
	}
}
