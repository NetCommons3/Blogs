<?php
/**
 * BlogEtnriesController
 */
App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogEntries Controller
 *
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 * @property NetCommonsWorkflow $NetCommonsWorkflow
 * @property PaginatorComponent $Paginator
 * @property BlogEntry $BlogEntry
 * @property BlogCategory $BlogCategory
 */
class BlogEntriesController extends BlogsAppController {

/**
 * @var array use models
 */
	public $uses = array(
		'Blogs.BlogEntry',
		'Workflow.WorkflowComment',
		'Categories.Category',
		'ContentComments.ContentComment',	// コンテンツコメント
	);

/**
 * @var array helpers
 * @var array helpers
 */
	public $helpers = array(
		'NetCommons.Token',
		'NetCommons.BackTo',
		'Workflow.Workflow',
		'Likes.Like',
	);


/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('index', 'view', 'category', 'tag', 'year_month');
		//$this->Categories->initCategories();
		parent::beforeFilter();
	}

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'Paginator',
		//'NetCommons.NetCommonsWorkflow',
		//'NetCommons.NetCommonsRoomRole' => array(
		//	//コンテンツの権限設定
		//	'allowedActions' => array(
		//		'contentEditable' => array('edit', 'add'),
		//		'contentCreatable' => array('edit', 'add'),
		//	),
		//),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
					//'add,edit,delete' => 'content_creatable',
					//'reply' => 'content_comment_creatable',
					//'approve' => 'content_comment_publishable',
			),
		),
		'Categories.Categories',
		'ContentComments.ContentComments',
			'Files.Download',
	);

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = array(
		'categoryId' => 0,
		'status' => 0,
		'yearMonth' => 0,
	);

/**
 * index
 *
 * @return void
 */
	public function index() {
		if (! Current::read('Block.id')) {
			$this->autoRender = false;
			return;
		}

		$this->_prepare();
		$this->set('listTitle', $this->_blogTitle);
		$this->set('filterDropDownLabel', __d('blogs', 'All Entries'));

		$conditions = array();
		$this->_filter['categoryId'] = $this->_getNamed('category_id', 0);
		if ($this->_filter['categoryId']) {
			$conditions['BlogEntry.category_id'] = $this->_filter['categoryId'];
			$category = $this->Category->findById($this->_filter['categoryId']);
			$this->set('listTitle', __d('blogs', 'Category') . ':' . $category['Category']['name']);
			$this->set('filterDropDownLabel', $category['Category']['name']);
		}

		$this->_list($conditions);
	}

/**
 * tag別一覧
 *
 * @return void
 */
	public function tag() {
		$this->_prepare();
		// indexとのちがいはtagIdでの絞り込みだけ
		$tagId = $this->_getNamed('id', 0);

		// カテゴリ名をタイトルに
		$tag = $this->BlogEntry->getTagByTagId($tagId);
		$this->set('listTitle', __d('blogs', 'Tag') . ':' . $tag['Tag']['name']);
		$this->set('filterDropDownLabel', '----');

		$conditions = array(
			'Tag.id' => $tagId // これを有効にするにはentry_tag_linkもJOINして検索か。
		);

		$this->_list($conditions);
	}

/**
 * 年月別一覧
 *
 * @return void
 */
	public function year_month() {
		$this->_prepare();
		// indexとの違いはyear_monthでの絞り込み
		$this->_filter['yearMonth'] = $this->_getNamed('year_month', 0);

		list($year, $month) = explode('-', $this->_filter['yearMonth']);
		$this->set('listTitle', __d('blogs', '%d-%d Blog Entry List', $year, $month));
		$this->set('filterDropDownLabel', __d('blogs', '%d-%d', $year, $month));

		$first = $this->_filter['yearMonth'] . '-1';
		$last = date('Y-m-t', strtotime($first));

		$conditions = array(
			'BlogEntry.publish_start BETWEEN ? AND ?' => array($first, $last)
		);
		$this->_list($conditions);
	}

/**
 * 権限の取得
 *
 * @return array
 */
	protected function _getPermission() {
		$permissionNames = array(
			'content_readable',
			'content_creatable',
			'content_editable',
			'content_publishable',
		);
		$permission = array();
		foreach ($permissionNames as $key) {
			$permission[$key] = Current::permission($key);
		}
		return $permission;
	}

/**
 * 一覧
 *
 * @param array $extraConditions 追加conditions
 * @return void
 */
	protected function _list($extraConditions = array()) {
		$this->set('currentCategoryId', $this->_filter['categoryId']);

		$this->set('currentYearMonth', $this->_filter['yearMonth']);

		$this->_setYearMonthOptions();

		$permission = $this->_getPermission();

		$conditions = $this->BlogEntry->getConditions(
			Current::read('Block.id'),
			$this->Auth->user('id'),
			$permission,
			$this->_getCurrentDateTime()
		);
		if ($extraConditions) {
			$conditions = Hash::merge($conditions, $extraConditions);
		}
		$this->Paginator->settings = array_merge(
			$this->Paginator->settings,
			array(
				'conditions' => $conditions,
				'limit' => $this->_frameSetting['BlogFrameSetting']['articles_per_page'],
				'order' => 'publish_start DESC',
				'fields' => '*, ContentCommentCnt.cnt',
			)
		);
		$this->BlogEntry->recursive = 0;
		$this->BlogEntry->Behaviors->load('ContentComments.ContentComment');
		$this->set('blogEntries', $this->Paginator->paginate());
		$this->BlogEntry->Behaviors->unload('ContentComments.ContentComment');

		$this->render('index');
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @return void
 */
	public function view() {
		$this->_prepare();

		//$key = $this->request->params['named']['key'];
		$key = $this->params['pass'][1];

		$conditions = $this->BlogEntry->getConditions(
			Current::read('Block.id'),
			$this->Auth->user('id'),
			$this->_getPermission(),
			$this->_getCurrentDateTime()
		);

		$conditions['BlogEntry.key'] = $key;

		$options = array(
			'conditions' => $conditions,
			'recursive' => 0,
			'fields' => array(
				'*',
				'ContentCommentCnt.cnt',
			)
		);
		$this->BlogEntry->Behaviors->load('ContentComments.ContentComment');
		$blogEntry = $this->BlogEntry->find('first', $options);
		$this->BlogEntry->Behaviors->unload('ContentComments.ContentComment');
		if ($blogEntry) {
			$this->set('blogEntry', $blogEntry);
			// tag取得
			//$blogTags = $this->BlogTag->getTagsByEntryId($blogEntry['BlogEntry']['id']);
			//$this->set('blogTags', $blogTags);

			// コメントを利用する
			if ($this->_blogSetting['BlogSetting']['use_comment']) {
				if ($this->request->isPost()) {
					// コメントする
					if (!$this->ContentComments->comment('blogs', $blogEntry['BlogEntry']['key'], $this->_blogSetting['BlogSetting']['use_comment_approval'])) {
						$this->throwBadRequest();
						return;
					}
				}

				// コンテンツコメントの取得
				$contentComments = $this->ContentComment->getContentComments(array(
					//'block_key' => $this->viewVars['blockKey'],
					'block_key' => Current::read('Block.key'),
					'plugin_key' => 'blogs',
					'content_key' => $blogEntry['BlogEntry']['key'],
				));
				$contentComments = $this->camelizeKeyRecursive($contentComments);
				$this->set('contentComments', $contentComments);
			}

		} else {
			// 表示できない記事へのアクセスなら404
			throw new NotFoundException(__('Invalid blog entry'));
		}
	}

	public function download() {
		$this->_prepare();

		//$originId = $this->request->params['named']['origin_id'];
		$key = $this->params['pass'][1];
		$fieldName = $this->params['pass'][2];

		$conditions = $this->BlogEntry->getConditions(
			Current::read('Block.id'),
			$this->Auth->user('id'),
			$this->_getPermission(),
			$this->_getCurrentDateTime()
		);

		$conditions['BlogEntry.key'] = $key;
		$options = array(
			'conditions' => $conditions,
		);
		$blogEntry = $this->BlogEntry->find('first', $options);

		if ($blogEntry) {
			return $this->Download->doDownload($blogEntry['BlogEntry']['id'], $fieldName);
			// TODO　リクエストパラメータからファイルIDを取得するカラム名を確定する
		} else {
			// 表示できない記事へのアクセスなら404
			throw new NotFoundException(__('Invalid blog entry'));
		}
	}

	/**
 * 年月選択肢をViewへセット
 *
 * @return void
 */
	protected function _setYearMonthOptions() {
		// 年月と記事数
		$yearMonthCount = $this->BlogEntry->getYearMonthCount(
			Current::read('Block.id'),
			$this->Auth->user('id'),
			$this->_getPermission(),
			$this->_getCurrentDateTime()
		);
		foreach ($yearMonthCount as $yearMonth => $count) {
			list($year, $month) = explode('-', $yearMonth);
			$options[$yearMonth] = __d('blogs', '%d-%d (%s)', $year, $month, $count);
		}
		$this->set('yearMonthOptions', $options);
	}
}
