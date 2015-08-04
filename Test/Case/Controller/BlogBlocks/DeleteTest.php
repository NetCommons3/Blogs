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
class Controller_BlogBlocks_DeleteTest extends BlogsAppControllerTestBase {

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
 * test delete. validateBlockId failed
 *
 * @return void
 */
	public function testDeleteFail() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(false));

		$this->controller->expects($this->once())
			->method('throwBadRequest');

		$data = [
			'Blog' => [
				'key' => 'blog1',
			],
			'Block' => [
				'id' => 5,
				'key' => 'block_5',
			]
		];
		$this->testAction(
			'/blogs/blog_blocks/delete/1/5',
			array(
				'method' => 'delete',
				'data' => $data,
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test delete action. Post
 *
 * @return void
 */
	public function testDeleteNotDeleteMethod() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(true));

		$this->controller->expects($this->once())
			->method('throwBadRequest');

		$data = [
			'Blog' => [
				'key' => 'blog1',
			],
			'Block' => [
				'id' => 5,
				'key' => 'block_5',
			]
		];
		$this->testAction(
			'/blogs/blog_blocks/delete/1/5',
			array(
				'method' => 'get',
				'data' => $data,
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test delete action. Post success
 *
 * @return void
 */
	public function testDeletePostSuccess() {
		RolesControllerTest::login($this);

		$this->controller->NetCommonsBlock->expects($this->once())
			->method('validateBlockId')
			->will($this->returnValue(true));

		$data = [
			'Blog' => [
				'key' => 'blog1',
			],
			'Block' => [
				'id' => 5,
				'key' => 'block_5',
			]
		];
		$this->testAction(
			'/blogs/blog_blocks/delete/1/5',
			array(
				'method' => 'delete',
				'data' => $data,
			)
		);

		$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}
}

