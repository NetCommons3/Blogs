<?php
/**
 * BlogsAppController::initBlog()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');
App::uses('UserRole', 'UserRoles.Model');

/**
 * BlogsAppController::initBlog()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogsAppController
 */
class BlogsAppControllerPrepareTest extends NetCommonsControllerTestCase {

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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Blogs', 'TestBlogs');
		$this->generateNc('TestBlogs.TestBlogsAppControllerIndex');

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
 * initBlog()のテスト
 *
 * @return void
 */
	public function testPrepare() {
		//テストデータ
		$frameId = '6';
		$blockId = '2';

		$urlOptions = [
			'plugin' => 'test_blogs',
			'controller' => 'test_blogs_app_controller_index',
			'action' => 'index',
			'block_id' => $blockId,
			'frame_id' => $frameId
		];
		//テスト実行
		$this->_testGetAction($urlOptions, ['method' => 'assertNotEmpty']);

		$this->assertEquals('BlockId2Blog', $this->vars['blog']['Blog']['name']);
		$this->assertEquals('content_block_1', $this->vars['blogSetting']['blog_key']);

		$frameSetting = new ReflectionProperty($this->controller, '_frameSetting');
		$frameSetting->setAccessible(true);
		$value = $frameSetting->getValue($this->controller);
		$this->assertEquals($frameId, $value['BlogFrameSetting']['id']);
	}

/**
 * blogデータが取得できなければBadRequest
 *
 * @return void
 */
	public function testBlogNotFound() {
		$frameId = null;
		$blockId = '3';

		$urlOptions = [
			'plugin' => 'test_blogs',
			'controller' => 'test_blogs_app_controller_index',
			'action' => 'index',
			'block_id' => $blockId,
			'frame_id' => $frameId
		];
		//テスト実行
		$this->_testGetAction($urlOptions, false, 'BadRequestException');
	}

/**
 * 新規ブログ作成時にBlogSettingが取得できないので、デフォルト値でViewにセット
 *
 * @return void
 */
	public function testBlogSettingNotFound() {
		$frameId = '6';
		$blockId = '2';

		$urlOptions = [
			'plugin' => 'test_blogs',
			'controller' => 'test_blogs_app_controller_index',
			'action' => 'index',
			'block_id' => $blockId,
			'frame_id' => $frameId
		];

		//$this->_mockForReturn('Blogs.BlogSetting', 'getBlogSetting', false, 1);
		$mockModel = 'Blogs.BlogSetting';
		$mockMethod = 'getBlogSetting';
		list($mockPlugin, $mockModel) = pluginSplit($mockModel);
		$this->controller->$mockModel = $this->getMockForModel(
			$mockPlugin . '.' . $mockModel,
			array($mockMethod),
			array('plugin' => 'Blogs')
		);

		//テスト実行
		$this->_testGetAction(
			$urlOptions,
			[
				'method' => 'assertNotEmpty'
			]
		);
		$this->assertNull($this->vars['blogSetting']['blog_key']);
		$this->assertEquals(1, $this->vars['blogSetting']['use_sns']);
	}

}
