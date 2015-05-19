<?php
/**
 * BlogsApp
 */
App::uses('AppController', 'Controller');

/**
 * Class BlogsAppController
 */
class BlogsAppController extends AppController {

/**
 * @var array ブログ名
 */
	protected $_blogTitle;

/**
 * @var array ブログ設定
 */
	protected $_blogSetting;

/**
 * @var array フレーム設定
 */
	protected $_frameSetting;

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsFrame',
		'Security',
	);

/**
 * @var array helpers
 */
	public $helpers = array(
		'Blogs.BlogsFormat',
	);

/**
 * @var array use model
 */
	public $uses = array(
		'Blogs.Blog',
		'Blogs.BlogSetting',
		'Blogs.BlogFrameSetting'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
	}

/**
 * 現在時刻を返す。テストしやすくするためにメソッドに切り出した。
 *
 * @return int
 */
	protected function _getNow() {
		return time();
	}

/**
 * 現在の日時を返す
 *
 * @return string datetime
 */
	protected function _getCurrentDateTime() {
		return date('Y-m-d H:i:s', $this->_getNow());
	}

/**
 * ブロック名をブログタイトルとしてセットする
 *
 * @return void
 */
	protected function _setupBlogTitle() {
		$this->loadModel('Blocks.Block');
		$block = $this->Block->findById($this->viewVars['blockId']);
		$this->_blogTitle = $block['Block']['name'];
	}

/**
 * フレーム設定を読みこむ
 *
 * @return void
 */
	protected function _loadFrameSetting() {
		$this->_frameSetting = $this->BlogFrameSetting->getSettingByFrameKey($this->viewVars['frameKey']);
	}

/**
 * 設定等の呼び出し
 *
 * @return void
 */
	protected function _prepare() {
		$this->_setupBlogTitle();
		$this->initBlog(['blogSetting']);
		$this->_loadFrameSetting();
	}

/**
 * namedパラメータ取得
 * 
 * @param string $name namedパラメータ名
 * @param null $default パラメータが存在しなかったときのデフォルト値
 * @return int|string
 */
	protected function _getNamed($name, $default = null) {
		$value = isset($this->request->params['named'][$name]) ? $this->request->params['named'][$name] : $default;
		return $value;
	}

/**
 * initTabs
 *
 * @param string $mainActiveTab Main active tab
 * @param string $blockActiveTab Block active tab
 * @return void
 */
	public function initTabs($mainActiveTab, $blockActiveTab) {
		//タブの設定
		$settingTabs = array(
			'tabs' => array(
				'block_index' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'blocks',
						'action' => 'index',
						$this->viewVars['frameId'],
					)
				),
				'frame_settings' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'blog_frame_settings',
						'action' => 'edit',
						$this->viewVars['frameId'],
					),
				),
			),
			'active' => $mainActiveTab
		);
		$this->set('settingTabs', $settingTabs);

		$blockSettingTabs = array(
			'tabs' => array(
				'block_settings' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'blocks',
						'action' => $this->params['action'],
						$this->viewVars['frameId'],
						$this->viewVars['blockId']
					)
				),
				'role_permissions' => array(
					'url' => array(
						'plugin' => $this->params['plugin'],
						'controller' => 'block_role_permissions',
						'action' => 'edit',
						$this->viewVars['frameId'],
						$this->viewVars['blockId']
					)
				),
			),
			'active' => $blockActiveTab
		);
		$this->set('blockSettingTabs', $blockSettingTabs);
	}

/**
 * initBlog
 *
 * @param array $contains Optional result sets
 * @return bool True on success, False on failure
 */
	public function initBlog($contains = []) {
		if (! $blog = $this->Blog->getBlog($this->viewVars['blockId'], $this->viewVars['roomId'])) {
			$this->throwBadRequest();
			return false;
		}
		$blog = $this->camelizeKeyRecursive($blog);
		$this->set($blog);

		if (in_array('blogSetting', $contains, true)) {
			if (! $blogSetting = $this->BlogSetting->getBlogSetting($blog['blog']['key'])) {
				$blogSetting = $this->BlogSetting->create(
					array('id' => null)
				);
			}
			$this->_blogSetting = $blogSetting;
			$blogSetting = $this->camelizeKeyRecursive($blogSetting);
			$this->set($blogSetting);
		}

		$this->set('userId', (int)$this->Auth->user('id'));

		return true;
	}

}
