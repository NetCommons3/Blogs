<?php
/**
 * BlogBlockRolePermissionsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlockRolePermissionsControllerEditTest', 'Blocks.TestSuite');

/**
 * BlogBlockRolePermissionsController::edit()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogBlockRolePermissionsController
 */
class BlogBlockRolePermissionsControllerEditTest extends BlockRolePermissionsControllerEditTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.blog_setting',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'blogs';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'blog_block_role_permissions';

/**
 * 権限設定で使用するFieldsの取得
 *
 * @return array
 */
	private function __approvalFields() {
		$data = array(
			'BlogSetting' => array(
				//TODO:権限設定で使用するFieldsをここに書く
				'use_workflow',
				'use_comment_approval',
				'approval_type',
			)
		);

		return $data;
	}

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$data = array(
			'BlogSetting' => array(
				'id' => 2,
				'blog_key' => 'blog_key_2',
				//TODO:必要なデータセットをここに書く
				'use_workflow' => true,
				'use_comment_approval' => true,
				'approval_type' => true,
			)
		);

		return $data;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - approvalFields コンテンツ承認の利用有無のフィールド
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditGet() {
		return array(
			array('approvalFields' => $this->__approvalFields())
		);
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - data POSTデータ
 *  - exception Exception
 *  - return testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderEditPost() {
		return array(
			array('data' => $this->__data())
		);
	}

}