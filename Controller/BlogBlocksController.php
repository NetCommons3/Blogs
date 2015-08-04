<?php
/**
 * BlocksController
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlocksController
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Controller
 */
class BlogBlocksController extends BlogsAppController {

/**
 * layout
 *
 * @var array
 */
	public $layout = 'NetCommons.setting';

/**
 * use models
 *
 * @var array
 */
	public $uses = array(
		'Blocks.Block',
		'Frames.Frame',
		'Blogs.Blog',
		'Blogs.BlogSetting',
		'Categories.Category',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.NetCommonsBlock',
		'NetCommons.NetCommonsRoomRole' => array(
			//コンテンツの権限設定
			'allowedActions' => array(
				'blockEditable' => array('index', 'add', 'edit', 'delete')
			),
		),
		'Paginator',
		'Categories.Categories',
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Date',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->deny('index');

		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);

		//タブの設定
		$this->initTabs('block_index', 'block_settings');
	}

/**
 * index
 *
 * @return void
 * @throws Exception
 */
	public function index() {
		$this->Paginator->settings = array(
			'Blog' => array(
				'order' => array('Block.id' => 'desc'),
				'conditions' => array(
					'Block.language_id' => $this->viewVars['languageId'],
					'Block.room_id' => $this->viewVars['roomId'],
					'Block.plugin_key ' => $this->params['plugin'],
				),
				//'limit' => 1
			)
		);
		$blogs = $this->Paginator->paginate('Blog');
		if (! $blogs) {
			$this->view = 'BlogBlocks/not_found';
			return;
		}

		$results = array(
			'blogs' => $blogs
		);
		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'BlogBlocks/edit';

		$this->set('blockId', null);
		$blog = $this->Blog->create(
			array(
				'id' => null,
				'key' => null,
				'block_id' => null,
				'name' => __d('blogs', 'New Blog %s', date('YmdHis')),
			)
		);
		$block = $this->Block->create(
			array('id' => null, 'key' => null)
		);

		$data = Hash::merge($blog, $block);

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();
			$this->Blog->saveBlog($data);
			if ($this->handleValidationError($this->Blog->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/blogs/blog_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
			$data['Block']['id'] = null;
			$data['Block']['key'] = null;
		}

		$results = $this->camelizeKeyRecursive($data);
		$this->set($results);
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		$this->initBlog(['blogSetting']);

		$this->Categories->initCategories();

		if ($this->request->isPost()) {
			$data = $this->__parseRequestData();
			$data['BlogSetting']['blog_key'] = $data['Blog']['key'];

			$this->Blog->saveBlog($data);
			if ($this->handleValidationError($this->Blog->validationErrors)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/blogs/blog_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}

			$results = $this->camelizeKeyRecursive($data);
			$this->set($results);
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if (! $this->NetCommonsBlock->validateBlockId()) {
			$this->throwBadRequest();
			return false;
		}
		$this->set('blockId', (int)$this->params['pass'][1]);

		$this->initBlog();

		if ($this->request->isDelete()) {
			if ($this->Blog->deleteBlog($this->data)) {
				if (! $this->request->is('ajax')) {
					$this->redirect('/blogs/blog_blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$this->throwBadRequest();
	}

/**
 * Parse data from request
 *
 * @return array
 */
	private function __parseRequestData() {
		$data = $this->data;
		//if ($data['Block']['public_type'] === Block::TYPE_LIMITED) {
		//	//$data['Block']['from'] = implode('-', $data['Block']['from']);
		//	//$data['Block']['to'] = implode('-', $data['Block']['to']);
		//} else {
		//	unset($data['Block']['from'], $data['Block']['to']);
		//}

		return $data;
	}

}
