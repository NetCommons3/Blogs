<?php
/**
 * BlogFrameSettings Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogFrameSettings Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blogs\Controller
 */
class BlogFrameSettingsController extends BlogsAppController {

	/**
	 * layout
	 *
	 * @var array
	 */
	public $layout = 'Frames.setting';

	/**
	 * use models
	 *
	 * @var array
	 */
	public $uses = array(
		'Blogs.BlogFrameSetting',
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
				'edit' => 'page_editable',
			),
		),
	);

	/**
	 * use helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'NetCommons.DisplayNumber',
	);

	/**
	 * edit
	 *
	 * @return void
	 */
	public function edit() {
		if ($this->request->isPut() || $this->request->isPost()) {
			if ($this->BlogFrameSetting->saveBlogFrameSetting($this->data)) {
				$this->redirect(NetCommonsUrl::backToPageUrl());
				return;
			}
			$this->NetCommons->handleValidationError($this->BlogFrameSetting->validationErrors);

		} else {
			$this->request->data = $this->BlogFrameSetting->getBlogFrameSetting(true);
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
