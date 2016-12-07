<?php
/**
 * BlogBlocksController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * BlogBlocksController::beforeFilter()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogBlocksController
 */
class BlogBlocksControllerBeforeFilterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.block_setting_for_blog',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.categories.categories_language',
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
	protected $_controller = 'blog_blocks';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * index()アクションのテスト
 *
 * @return void
 */
	public function testBeforeFilterIndex() {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$this->_testGetAction(array('action' => 'index', 'block_id' => '2', 'frame_id' => '6'),
			array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->assertFalse($this->controller->Components->loaded('Categories.CategoryEdit'));
		$this->assertNotEmpty($this->view);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * edit()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBeforeFilterGetForEdit() {
		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'block_id' => '2', 'frame_id' => '6'),
			array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		// action=indexのときはCategoryEditComponentが無いこと
		$result = $this->controller->Components->loaded('CategoryEdit');
		$this->assertTrue($result);
	}

}
