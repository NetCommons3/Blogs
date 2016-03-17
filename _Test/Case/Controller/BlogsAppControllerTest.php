<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 15/05/18
 * Time: 9:56
 */

App::uses('BlogBlockRolePermissionsController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * BlogsAppController Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller
 */
class BlogsAppControllerTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->generate(
			'Blogs.BlogsApp',
			[
				'methods' => [
					'throwBadRequest',
				],
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
				]
			]
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * test initTabs
 *
 * @return void
 */
	public function testInitTabs() {
		$mainActiveTab = 'block_index';
		$blockActiveTab = 'block_settings';
		$this->controller->viewVars['frameId'] = 1;
		$this->controller->viewVars['blockId'] = 5;
		$this->controller->initTabs($mainActiveTab, $blockActiveTab);

		$this->assertInternalType('array', $this->controller->viewVars['settingTabs']);
		$this->assertInternalType('array', $this->controller->viewVars['blockSettingTabs']);
	}

/**
 * test index
 *
 * @return void
 */
	public function testInitBlogSuccess() {
		RolesControllerTest::login($this);

		$this->controller->viewVars['blockId'] = 5;
		$this->controller->viewVars['frameId'] = 1;
		$this->controller->viewVars['roomId'] = 1;
		$resultTrue = $this->controller->initBlog();

		$this->assertTrue($resultTrue);

		//$this->testAction(
		//	'/blogs/blog_entries/index/1',
		//	array(
		//		'method' => 'get',
		//	)
		//);
		//$this->assertInternalType('array', $this->vars['blog']);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test initBlog faild
 *
 * @return void
 */
	public function testInitBlogGetBlogFail() {
		RolesControllerTest::login($this);

		$BlogMock = $this->getMockForModel('Blogs.Blog', ['getBlog']);
		$BlogMock->expects($this->once())
			->method('getBlog')
			->will($this->returnValue(false));

		$this->controller->expects($this->once())
			->method('throwBadRequest');

		$this->controller->viewVars['blockId'] = 5;
		$this->controller->viewVars['frameId'] = 1;
		$this->controller->viewVars['roomId'] = 1;
		$resultFalse = $this->controller->initBlog();

		$this->assertFalse($resultFalse);
		AuthGeneralControllerTest::logout($this);
	}

/**
 * test init blog. get blogSetting faild
 *
 * @return void
 */
	public function testInitBlogGetBlogSettingFail() {
		RolesControllerTest::login($this);

		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['getBlogSetting']);
		$BlogSettingMock->expects($this->once())
			->method('getBlogSetting')
			->will($this->returnValue(false));

		$this->controller->viewVars['blockId'] = 5;
		$this->controller->viewVars['frameId'] = 1;
		$this->controller->viewVars['roomId'] = 1;
		$resultTrue = $this->controller->initBlog();
		$this->assertTrue($resultTrue);

		$this->assertNull($this->controller->viewVars['blogSetting']['id']);
		AuthGeneralControllerTest::logout($this);
	}

	//public function testInitBlogWithFrameSetting() {
	//	RolesControllerTest::login($this);
	//
	//	$this->controller->viewVars['blockId'] = 5;
	//	$this->controller->viewVars['frameId'] = 1;
	//	$this->controller->viewVars['roomId'] = 1;
	//	$this->controller->viewVars['frameKey'] = 'frame_1';
	//	$resultTrue = $this->controller->initBlog(['blogFrameSetting']);
	//	$this->assertTrue($resultTrue);
	//
	//	$this->assertNull($this->controller->viewVars['blogSetting']['id']);
	//	AuthGeneralControllerTest::logout($this);
	//}

}

