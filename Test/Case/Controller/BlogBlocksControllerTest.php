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
}

