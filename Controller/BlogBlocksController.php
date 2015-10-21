<?php
/**
 * BlogBlocks Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogBlocks Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
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
		'Blogs.BlogFrameSetting',
		'Blocks.Block',
	);

/**
 * use components
 *
 * @var array
 */
	public $components = array(

		'Blocks.BlockTabs' => array(
			'mainTabs' => array(
				'block_index' => array('url' => array('controller' => 'blog_blocks')),
				'frame_settings' => array('url' => array('controller' => 'blog_frame_settings')),
			),
			'blockTabs' => array(
				'block_settings' => array('url' => array('controller' => 'blog_blocks')),
				'role_permissions' => array('url' => array('controller' => 'blog_block_role_permissions')),
			),
		),
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'index,add,edit,delete' => 'block_editable',
			),
		),
		'Paginator',
		'Categories.CategoryEdit',

	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'Blocks.BlockForm',
		//'Blocks.Block',
		'Likes.Like',
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		//CategoryEditComponentの削除
		if ($this->params['action'] === 'index') {
			$this->Components->unload('Categories.CategoryEdit');
		}
	}

/**
 * index
 *
 * @return void
 */
	public function index() {
		$this->Paginator->settings = array(
			'Blog' => array(
				'order' => array('Blog.id' => 'desc'),
				'conditions' => $this->Blog->getBlockConditions(),
			)
		);

		$blogs = $this->Paginator->paginate('Blog');
		if (! $blogs) {
			$this->view = 'Blocks.Blocks/not_found';
			return;
		}
		$this->set('blogs', $blogs);
		$this->request->data['Frame'] = Current::read('Frame');
	}

/**
 * add
 *
 * @return void
 */
	public function add() {
		$this->view = 'edit';

		if ($this->request->isPost()) {
			//登録処理
			if ($this->Blog->saveBlog($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->Blog->validationErrors);

		} else {
			//表示処理(初期データセット)
			$this->request->data = $this->Blog->createBlog();
			$this->request->data = Hash::merge($this->request->data, $this->BlogFrameSetting->getBlogFrameSetting(true));
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

/**
 * edit
 *
 * @return void
 */
	public function edit() {
		if ($this->request->isPut()) {
			//登録処理
			if ($this->Blog->saveBlog($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
			$this->NetCommons->handleValidationError($this->Blog->validationErrors);

		} else {
			//表示処理(初期データセット)
			CurrentFrame::setBlock($this->request->params['pass'][1]);
			if (! $blog = $this->Blog->getBlog()) {
				$this->setAction('throwBadRequest');
				return false;
			}
			$this->request->data = Hash::merge($this->request->data, $blog);
			$this->request->data = Hash::merge($this->request->data, $this->BlogFrameSetting->getBlogFrameSetting(true));
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}

/**
 * delete
 *
 * @return void
 */
	public function delete() {
		if ($this->request->isDelete()) {
			if ($this->Blog->deleteBlog($this->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
			}
		}

		$this->setAction('throwBadRequest');
	}
}
