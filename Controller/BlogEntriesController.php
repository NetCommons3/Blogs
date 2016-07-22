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
		//'ContentComments.ContentComment',	// コンテンツコメント
	);

/**
 * @var array helpers
 */
	public $helpers = array(
		'NetCommons.BackTo',
		'Workflow.Workflow',
		'Likes.Like',
		'ContentComments.ContentComment' => array(
			'viewVarsKey' => array(
				'contentKey' => 'blogEntry.BlogEntry.key',
				'contentTitleForMail' => 'blogEntry.BlogEntry.title',
				'useComment' => 'blogSetting.use_comment',
				'useCommentApproval' => 'blogSetting.use_comment_approval'
			)
		),
		'NetCommons.SnsButton',
		'NetCommons.TitleIcon',
		'NetCommons.DisplayNumber',
	);

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
		'ContentComments.ContentComments' => array(
			'viewVarsKey' => array(
				'contentKey' => 'blogEntry.BlogEntry.key',
				'useComment' => 'blogSetting.use_comment'
			),
			'allow' => array('view')
		)	);

/**
 * @var array 絞り込みフィルタ保持値
 */
	protected $_filter = array(
		'categoryId' => 0,
		'status' => 0,
		'yearMonth' => 0,
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		// ゲストアクセスOKのアクションを設定
		$this->Auth->allow('index', 'view', 'tag', 'year_month');
		//$this->Categories->initCategories();
		parent::beforeFilter();
	}

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
		//$this->_filter['categoryId'] = Hash::get($this->request->params['named'], 'category_id', 0);
		$this->_filter['categoryId'] = Hash::get($this->request->params['named'], 'category_id', 0);
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
 * @throws NotFoundException
 * @return void
 */
	public function tag() {
		$this->_prepare();
		// indexとのちがいはtagIdでの絞り込みだけ
		$tagId = Hash::get($this->request->params['named'], 'id', 0);

		// カテゴリ名をタイトルに
		$tag = $this->BlogEntry->getTagByTagId($tagId);
		if (!$tag) {
			throw new NotFoundException(__d('tags', 'Tag not found'));
		}
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
		$this->_filter['yearMonth'] = Hash::get($this->request->params['named'], 'year_month', 0);

		if (!preg_match('/^[0-9]{4}-[0-1][0-9]$/', $this->_filter['yearMonth'])) {
			// 年月としてありえない値だったらBadRequest
			return $this->throwBadRequest();
		}
		list($year, $month) = explode('-', $this->_filter['yearMonth']);
		if (is_numeric($year) && $month >= 1 && $month <= 12) {
			$this->set('listTitle', __d('blogs', '%d-%d Blog Entry List', $year, $month));
			$this->set('filterDropDownLabel', __d('blogs', '%d-%d', $year, $month));

			$first = $this->_filter['yearMonth'] . '-1';
			$last = date('Y-m-t', strtotime($first));

			$conditions = array(
				'BlogEntry.publish_start BETWEEN ? AND ?' => array($first, $last)
			);
			$this->_list($conditions);
		} else {
			// 年月としてありえない値だったらBadRequest
			return $this->throwBadRequest();
		}
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
			$permission
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
				//'fields' => '*, ContentCommentCnt.cnt',
			)
		);
		$this->BlogEntry->recursive = 0;
		$this->BlogEntry->Behaviors->load('ContentComments.ContentComment');
		$this->set('blogEntries', $this->Paginator->paginate());
		$this->BlogEntry->Behaviors->unload('ContentComments.ContentComment');
		$this->BlogEntry->recursive = -1;

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

		$key = $this->params['key'];

		$conditions = $this->BlogEntry->getConditions(
			Current::read('Block.id'),
			$this->_getPermission()
		);

		$conditions['BlogEntry.key'] = $key;

		$options = array(
			'conditions' => $conditions,
			'recursive' => 0,
		);
		$this->BlogEntry->Behaviors->load('ContentComments.ContentComment');
		$blogEntry = $this->BlogEntry->find('first', $options);
		$this->BlogEntry->Behaviors->unload('ContentComments.ContentComment');
		if ($blogEntry) {
			$this->set('blogEntry', $blogEntry);

			//新着データを既読にする
			$this->BlogEntry->saveTopicUserStatus($blogEntry);

			// tag取得
			//$blogTags = $this->BlogTag->getTagsByEntryId($blogEntry['BlogEntry']['id']);
			//$this->set('blogTags', $blogTags);

			// コメントを利用する
			if ($this->_blogSetting['BlogSetting']['use_comment']) {
				if ($this->request->is('post')) {
					// コメントする

					$blogEntryKey = $blogEntry['BlogEntry']['key'];
					$useCommentApproval = $this->_blogSetting['BlogSetting']['use_comment_approval'];
					if (!$this->ContentComments->comment('blogs', $blogEntryKey,
						$useCommentApproval)) {
						return $this->throwBadRequest();
					}
				}
			}

		} else {
			// 表示できない記事へのアクセスならBadRequest
			return $this->throwBadRequest();
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
			$this->_getPermission()
		);
		foreach ($yearMonthCount as $yearMonth => $count) {
			list($year, $month) = explode('-', $yearMonth);
			$options[$yearMonth] = __d('blogs', '%d-%d (%s)', $year, $month, $count);
		}
		$this->set('yearMonthOptions', $options);
	}
}
