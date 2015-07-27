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
 * BlogsController Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller
 */
class BlogBlockRolePermissionsControllerTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		//$this->loadFixtures(
		//	'Rooms.RoomRole'
		//);
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->_controllerMock = $this->generate(
			'Blogs.BlogBlockRolePermissions',
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
 * test edit
 *
 * @return void
 */
	public function testEdit() {
		RolesControllerTest::login($this);

		$this->testAction(
			'/blogs/blog_block_role_permissions/edit/1/5',
			array(
				'method' => 'get',
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit invalid block id
 *
 * @return void
 */
	public function testEditInvalidBlockId() {
		RolesControllerTest::login($this);

		$this->_controllerMock->expects($this->once())
			->method('throwBadRequest');

		$resultFalse = $this->testAction(
			'/blogs/blog_block_role_permissions/edit/1/0',
			array(
				'method' => 'get',
			)
		);
		$this->assertFalse($resultFalse);

		$this->_controllerMock->expects($this->once())
			->method('throwBadRequest');

		$resultFalse = $this->testAction(
			'/blogs/blog_block_role_permissions/edit/1/999',
			array(
				'method' => 'get',
			)
		);
		$this->assertFalse($resultFalse);

		AuthGeneralControllerTest::logout($this);
	}

}

