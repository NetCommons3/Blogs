<?php
/**
 * BlogEntriesEdit
 */
App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogEntriesEdit Controller
 *
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 * @property NetCommonsWorkflow $NetCommonsWorkflow
 * @property PaginatorComponent $Paginator
 * @property BlogEntry $BlogEntry
 * @property BlogCategory $BlogCategory
 * @property NetCommonsComponent $NetCommons
 */
class BlogEntriesEditController extends BlogsAppController {

/**
 * @var array use models
 */
	public $uses = array(
		'Blogs.BlogEntry',
		'Categories.Category',
		'Comments.Comment',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		//'NetCommons.NetCommonsWorkflow',
		//'NetCommons.NetCommonsRoomRole' => array(
		//	//コンテンツの権限設定
		//	'allowedActions' => array(
		//		'contentEditable' => array('edit', 'add', 'delete'),
		//		'contentCreatable' => array('edit', 'add', 'delete'),
		//	),
		//),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
		'Workflow.Workflow',

		'Categories.Categories',
		//'Blogs.BlogEntryPermission',
	);

	/**
	 * @var array helpers
	 */
	public $helpers = array(
		//'NetCommons.Token',
		'NetCommons.BackTo',
		'NetCommons.NetCommonsForm',
		'Workflow.Workflow',
		//'Likes.Like',
	);

	/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		App::uses('CakeTime', 'Utility');
		$this->request->data['BlogEntry']['published_datetime'] = CakeTime::toServer($this->request->data['BlogEntry']['published_datetime'], $this->Auth->user('timezone'));
		parent::beforeFilter();
		//$this->Categories->initCategories();
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->set('isEdit', false);

		$blogEntry = $this->BlogEntry->getNew();
		$this->set('blogEntry', $blogEntry);

		if ($this->request->is('post')) {
			$this->BlogEntry->create();
			$this->request->data['BlogEntry']['blog_key'] = ''; // https://github.com/NetCommons3/NetCommons3/issues/7 対策

			// set status
			$status = $this->Workflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = Current::read('Block.id');
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];
			if (($result = $this->BlogEntry->saveEntry(Current::read('Block.id'), Current::read('Frame.id'), $this->request->data))) {
				return $this->redirect(
					array('controller' => 'blog_entries', 'action' => 'view', Current::read('Frame.id'), 'origin_id' => $result['BlogEntry']['origin_id'])
				);
			}

			$this->NetCommons->handleValidationError($this->BlogEntry->validationErrors);

		} else {
			$this->request->data = $blogEntry;
			$this->request->data['Tag'] = array();
		}

		//$comments = $this->Comment->getComments(
		//	array(
		//		'plugin_key' => 'blogs',
		//		'content_key' => isset($blogEntry['BlogEntry']['key']) ? $blogEntry['BlogEntry']['key'] : null,
		//	)
		//);
		//$this->set('comments', $comments);

		$this->render('form');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @throws ForbiddenException
 * @return void
 */
	public function edit() {
		$this->set('isEdit', true);
		//$originId = $this->request->params['named']['origin_id'];
		$originId = $this->params['pass'][1];

		//  origin_idのis_latstを元に編集を開始
		$blogEntry = $this->BlogEntry->findByOriginIdAndIsLatest($originId, 1);
		if (empty($blogEntry)) {
			//  404 NotFound
			throw new NotFoundException();
		}

		if ($this->request->is(array('post', 'put'))) {

			$this->BlogEntry->create();
			$this->request->data['BlogEntry']['blog_key'] = ''; // https://github.com/NetCommons3/NetCommons3/issues/7 対策

			// set status
			$status = $this->Workflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = Current::read('Block.id');
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];

			$data = $this->request->data;

			unset($data['BlogEntry']['id']); // 常に新規保存

			if ($this->BlogEntry->saveEntry(Current::read('Block.id'), Current::read('Frame.id'), $data)) {
				return $this->redirect(
					array('controller' => 'blog_entries', 'action' => 'view', Current::read('Frame.id'), 'origin_id' => $data['BlogEntry']['origin_id'])
				);
			}

			$this->NetCommons->handleValidationError($this->BlogEntry->validationErrors);

		} else {

			$this->request->data = $blogEntry;
			if ($this->BlogEntry->canEditWorkflowContent($blogEntry) === false) {
				throw new ForbiddenException(__d('net_commons', 'Permission denied'));
			}

		}

		$this->set('blogEntry', $blogEntry);
		$this->set('isDeletable', $this->BlogEntry->canDeleteWorkflowContent($blogEntry));

		$comments = $this->BlogEntry->getCommentsByContentKey($blogEntry['BlogEntry']['key']);
		$this->set('comments', $comments);

		$this->render('form');
	}

/**
 * delete method
 *
 * @throws ForbiddenException
 * @throws InternalErrorException
 * @return void
 */
	public function delete() {
		$originId = $this->request->data['BlogEntry']['origin_id'];

		$this->request->allowMethod('post', 'delete');

		$blogEntry = $this->BlogEntry->findByOriginIdAndIsLatest($originId, 1);

		// 権限チェック
		if ($this->BlogEntry->canDeleteWorkflowContent($blogEntry) === false) {
			throw new ForbiddenException(__d('net_commons', 'Permission denied'));
		}

		if ($this->BlogEntry->deleteEntryByOriginId($originId) === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		return $this->redirect(array('controller' => 'blog_entries', 'action' => 'index', Current::read('Frame.id')));
	}
}
