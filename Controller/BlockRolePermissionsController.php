<?php
/**
 * BlockRolePermissions Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Kotaro Hokada <kotaro.hokada@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlockRolePermissions Controller
 *
 * @author Kotaro Hokada <kotaro.hokada@gmail.com>
 * @package NetCommons\Blogs\Controller
 */
class BlockRolePermissionsController extends BlogsAppController {

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
		'Roles.Role',
		'Roles.DefaultRolePermission',
		'Blogs.Blog',
		'Blogs.BlogSetting',
		'Blocks.Block',
		'Blocks.BlockRolePermission',
		'Rooms.RolesRoom',
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
				'blockPermissionEditable' => array('edit')
			),
		),
	);

/**
 * use helpers
 *
 * @var array
 */
	public $helpers = array(
		'NetCommons.Token'
	);

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();

		$results = $this->camelizeKeyRecursive($this->NetCommonsFrame->data);
		$this->set($results);

		//タブの設定
		$this->initTabs('block_index', 'role_permissions');
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

		$this->initBlog();

		if (! $block = $this->Block->find('first', array(
			'recursive' => -1,
			'conditions' => array(
				'Block.id' => $this->viewVars['blockId'],
			),
		))) {
			$this->throwBadRequest();
			return false;
		};
		$this->set('blockId', $block['Block']['id']);
		$this->set('blockKey', $block['Block']['key']);

		$permissions = $this->NetCommonsBlock->getBlockRolePermissions(
			$this->viewVars['blockKey'],
			['content_creatable', 'content_publishable', 'content_comment_creatable', 'content_comment_publishable']
		);

		if ($this->request->isPost()) {
			$data = $this->data;
			$this->BlogSetting->saveBlogSetting($data);
			if ($this->handleValidationError($this->BlogSetting->validationErrors)) {

				if (! $this->request->is('ajax')) {
					$this->redirect('/blogs/blocks/index/' . $this->viewVars['frameId']);
				}
				return;
			}
		}

		$results = array(
			'blockRolePermissions' => $permissions['BlockRolePermissions'],
			'roles' => $permissions['Roles'],
		);
		$results = $this->camelizeKeyRecursive($results);
		$this->set($results);
	}
}
