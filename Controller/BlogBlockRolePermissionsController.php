<?php
/**
 * BlockRolePermissions Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlockRolePermissions Controller
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blogs\Controller
 */
class BlogBlockRolePermissionsController extends BlogsAppController {

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
		'Blogs.Blog',
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
				'edit' => 'block_permission_editable',
			),
		),
	);

	/**
	 * use helpers
	 *
	 * @var array
	 */
	public $helpers = array(
		'Blocks.BlockRolePermissionForm'
	);

	/**
	 * edit
	 *
	 * @return void
	 */
	public function edit() {
		CurrentFrame::setBlock($this->request->params['pass'][1]);

		if (! $blog = $this->Blog->getBlog()) {
			$this->setAction('throwBadRequest');
			return false;
		}

		$permissions = $this->Workflow->getBlockRolePermissions(
			array('content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable')
		);
		$this->set('roles', $permissions['Roles']);

		if ($this->request->isPost()) {
			if ($this->BlogSetting->saveBlogSetting($this->request->data)) {
				$this->redirect(NetCommonsUrl::backToIndexUrl('default_setting_action'));
				return;
			}
			$this->NetCommons->handleValidationError($this->BlogSetting->validationErrors);

		} else {
			$this->request->data['BlogSetting'] = $blog['BlogSetting'];
			$this->request->data['Block'] = $blog['Block'];
			$this->request->data['BlockRolePermission'] = $permissions['BlockRolePermissions'];
			$this->request->data['Frame'] = Current::read('Frame');
		}
	}
}
