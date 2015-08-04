<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 15/05/18
 * Time: 9:56
 */

App::uses('BlogBlocksController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * BlogsController Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller
 */
class BlogBlocksControllerTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->generate(
			'Blogs.BlogBlocks',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
					'NetCommonsBlock' => ['validateBlockId'],
				],
				'methods' => [
					'throwBadRequest',
				],
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
 * testIndex
 *
 * @return void
 */
	public function testIndex() {
		RolesControllerTest::login($this);

		$this->testAction(
			'/blogs/blog_blocks/index/1',
			array(
				'method' => 'get',
			)
		);
		$this->assertInternalType('array', $this->vars['blogs']);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * testIndex. No blogs
 *
 * @return void
 */
	public function testIndexNoBlogs() {
		RolesControllerTest::login($this);

		// blogレコードを削除しておく
		$Blog = ClassRegistry::init('Blogs.Blog');
		$Blog->deleteAll(array(1 => 1), false, false);

		$view = $this->testAction(
			'/blogs/blog_blocks/index/1',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);
		$this->assertTextContains(__d('net_commons', 'Not found.'), $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test add action.
 *
 * @return void
 */
	public function testAdd() {
		RolesControllerTest::login($this);

		$view = $this->testAction(
			'/blogs/blog_blocks/add/1',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);

		$this->assertRegExp('/<form/', $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test add action. Post
 *
 * @return void
 */
	public function testAddPostValidateFail() {
		RolesControllerTest::login($this);

		$data = array();
		$data = [
			'Blog' => [
				'key' => '',
				'name' => '',
			]
		];
		$view = $this->testAction(
			'/blogs/blog_blocks/add/1',
			array(
				'method' => 'post',
				'return' => 'view',
				'data' => $data,
			)
		);

		$this->assertTextContains(sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog Name')), $view);
		//debug($view);
		//$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test add action. Post success
 *
 * @return void
 */
	public function testAddPostSuccess() {
		RolesControllerTest::login($this);

		$data = [
			'Blog' => [
				'key' => '',
				'name' => 'blog name',
				'block_id' => 5,
			],
			'Frame' => [
				'id' => 1
			]
		];
		$this->testAction(
			'/blogs/blog_blocks/add/1',
			array(
				'method' => 'post',
				'return' => 'view',
				'data' => $data,
			)
		);

		$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action.
 *
 * @return void
 */
	public function testEdit() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(true));

		$view = $this->testAction(
			'/blogs/blog_blocks/edit/1/5',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);

		$this->assertRegExp('/<form/', $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit. validateBlockId failed
 *
 * @return void
 */
	public function testEditFail() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(false));

		$this->controller->expects($this->once())
			->method('throwBadRequest');

		$this->testAction(
			'/blogs/blog_blocks/edit/1/5',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. Post
 *
 * @return void
 */
	public function testEditPostValidateFail() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(true));

		$data = array();
		$data = [
			'Blog' => [
				'key' => '',
				'name' => '',
			]
		];
		$view = $this->testAction(
			'/blogs/blog_blocks/edit/1/5',
			array(
				'method' => 'post',
				'return' => 'view',
				'data' => $data,
			)
		);

		$this->assertTextContains(sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog Name')), $view);
		//debug($view);
		//$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. Post success
 *
 * @return void
 */
	public function testEditPostSuccess() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(true));

		$data = [
			'Blog' => [
				'key' => '',
				'name' => 'blog name',
				'block_id' => 5,
			],
			'Frame' => [
				'id' => 1
			]
		];
		$this->testAction(
			'/blogs/blog_blocks/edit/1/5',
			array(
				'method' => 'post',
				'return' => 'view',
				'data' => $data,
			)
		);

		$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}
}

