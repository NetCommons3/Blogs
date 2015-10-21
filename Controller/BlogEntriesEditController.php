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
		'Workflow.WorkflowComment',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
		'Workflow.Workflow',

		'Categories.Categories',
		//'Blogs.BlogEntryPermission',
		'NetCommons.NetCommonsTime',
	);

/**
 * @var array helpers
 */
	public $helpers = array(
		//'NetCommons.Token',
		'NetCommons.BackTo',
		'NetCommons.NetCommonsForm',
		'Workflow.Workflow',
		'NetCommons.NetCommonsTime',
		//'Likes.Like',
	);

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
				$url = NetCommonsUrl::actionUrl(
					array(
						'controller' => 'blog_entries',
						'action' => 'view',
						'block_id' => Current::read('Block.id'),
						'frame_id' => Current::read('Frame.id'),
						'origin_id' => $result['BlogEntry']['origin_id'])
				);
				return $this->redirect($url);
			}

			$this->NetCommons->handleValidationError($this->BlogEntry->validationErrors);

		} else {
			$this->request->data = $blogEntry;
			$this->request->data['Tag'] = array();
		}

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
				$url = NetCommonsUrl::actionUrl(
					array(
						'controller' => 'blog_entries',
						'action' => 'view',
						'frame_id' => Current::read('Frame.id'),
						'block_id' => Current::read('Block.id'),
						'origin_id' => $data['BlogEntry']['origin_id']
					)
				);

				return $this->redirect($url);
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
		return $this->redirect(
			NetCommonsUrl::actionUrl(
				array('controller' => 'blog_entries', 'action' => 'index', 'frame_id' => Current::read('Frame.id'), 'block_id' => Current::read('Block.id'))));
	}
}
