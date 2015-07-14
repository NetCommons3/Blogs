<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 15/05/18
 * Time: 9:56
 */

App::uses('BlogEntriesEditController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * BlogsController Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller
 */
class Controller_BlogsEntriesEdit_DeleteTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->blogEntriesEditMock = $this->generate(
			'Blogs.BlogEntriesEdit',
			[
				'methods' => [
					'handleValidationError',
				],
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
					'NetCommons.NetCommonsWorkflow'
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
 * testDelete
 *
 * @return void
 */
	public function testDelete() {
		RolesControllerTest::login($this);

		$this->testAction(
			'/blogs/blog_entries_edit/delete/1',
			array(
				'method' => 'post',
				'return' => 'view',
				'data' => array(
					'BlogEntry' => array('origin_id' => 3)
				)
			)
		);
		$this->assertRegExp('#/blogs/blog_entries/index#', $this->headers['Location']);

		$BlogEntry = ClassRegistry::init('Blogs.BlogEntry');
		$countZero = $BlogEntry->find('count', array('conditions' => array('origin_id' => 3)));
		$this->assertEqual($countZero, 0);

		AuthGeneralControllerTest::logout($this);
	}

}

