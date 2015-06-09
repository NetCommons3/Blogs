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
		'NetCommons.NetCommonsWorkflow',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'contentEditable' => array('edit', 'add', 'delete'),
				'contentCreatable' => array('edit', 'add', 'delete'),
			),
		),
		'Categories.Categories',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Categories->initCategories();
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
			// set status
			$status = $this->NetCommonsWorkflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = $this->viewVars['blockId'];
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];

			if (($result = $this->BlogEntry->saveEntry($this->viewVars['blockId'], $this->request->data))) {
				return $this->redirect(
					array('controller' => 'blog_entries', 'action' => 'view', $this->viewVars['frameId'], 'origin_id' => $result['BlogEntry']['origin_id'])
				);
			}

			$this->handleValidationError($this->BlogEntry->validationErrors);

		} else {
			$this->request->data = $blogEntry;
			$this->request->data['Tag'] = array();
		}

		$comments = $this->Comment->getComments(
			array(
				'plugin_key' => 'blogs',
				'content_key' => isset($blogEntry['BlogEntry']['key']) ? $blogEntry['BlogEntry']['key'] : null,
			)
		);
		$this->set('comments', $comments);

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
		$originId = $this->request->params['named']['origin_id'];

		//  origin_idのis_latstを元に編集を開始
		$blogEntry = $this->BlogEntry->findByOriginIdAndIsLatest($originId, 1);
		if (empty($blogEntry)) {
			//  404 NotFound
			throw new NotFoundException();
		}

		if ($this->request->is(array('post', 'put'))) {
			$this->BlogEntry->create();
			// set status
			$status = $this->NetCommonsWorkflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = $this->viewVars['blockId'];
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];

			$data = $this->request->data;

			unset($data['BlogEntry']['id']); // 常に新規保存

			if ($this->BlogEntry->saveEntry($this->viewVars['blockId'], $data)) {
				return $this->redirect(
					array('controller' => 'blog_entries', 'action' => 'view', $this->viewVars['frameId'], 'origin_id' => $data['BlogEntry']['origin_id'])
				);
			}

			$this->handleValidationError($this->BlogEntry->validationErrors);

		} else {

			$this->request->data = $blogEntry;
			if ($this->_hasEditPermission($blogEntry) === false) {
				throw new ForbiddenException(__d('net_commons', 'Permission denied'));
			}

		}

		$this->set('blogEntry', $blogEntry);

		$comments = $this->Comment->getComments(
			array(
				'plugin_key' => 'blogentries',
				// ε(　　　　 v ﾟωﾟ)　＜ Commentプラグインでセーブするときにモデル名をstrtolowerして複数形になおして保存してるのでこんな名前。なんとかしたい
				'content_key' => isset($blogEntry['BlogEntry']['key']) ? $blogEntry['BlogEntry']['key'] : null,
			)
		);
		$comments = $this->camelizeKeyRecursive($comments);
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

		$this->request->onlyAllow('post', 'delete');

		$blogEntry = $this->BlogEntry->findByOriginIdAndIsLatest($originId, 1);

		// 権限チェック
		if ($this->_hasEditPermission($blogEntry) === false) {
			throw new ForbiddenException(__d('net_commons', 'Permission denied'));
		}

		if ($this->BlogEntry->deleteEntryByOriginId($originId) === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		return $this->redirect(array('controller' => 'blog_entries', 'action' => 'index', $this->viewVars['frameId']));
	}

/**
 * 編集・削除の権限チェック
 *
 * @param BlogEntry $blogEntry 権限チェック対象記事
 * @return bool
 */
	protected function _hasEditPermission($blogEntry) {
		if ($this->viewVars['contentEditable']) {
			// 編集権限あり　＝＞OK
		} elseif ($this->viewVars['contentCreatable']) {
			// 作成権限あり→自分の記事ならOK
			if ($blogEntry['BlogEntry']['created_user'] !== $this->Auth->user('id')) {
				return false;
			}
		} else {
			// 権限無し
			return false;
		}
		return true;
	}

}
