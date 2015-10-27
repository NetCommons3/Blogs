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
class BlogsEntriesEdit_AddTest extends BlogsAppControllerTestBase {

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
 * test add action validate fail
 *
 * @return void
 */
	public function testAddPostValidateFail() {
		$this->blogEntriesEditMock->NetCommonsWorkflow->expects($this->once())
			->method('parseStatus')
			->will($this->returnValue(1));

		RolesControllerTest::login($this);

		// validate error発生でhandleValidationError()が呼ばれる。
		$this->blogEntriesEditMock->expects($this->once())
			->method('handleValidationError')
			->with($this->isType('array'));
		$this->testAction(
			'/blogs/blog_entries_edit/add/1',
			array(
				'method' => 'post',
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test add action post success
 *
 * @return void
 */
	public function testAddPostSuccess() {
		$this->blogEntriesEditMock->NetCommonsWorkflow->expects($this->once())
			->method('parseStatus')
			->will($this->returnValue(1));

		RolesControllerTest::login($this);

		$data = array();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'New ENtry';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 1;
		$data['BlogEntry']['key'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['publish_start'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;
		$data['BlogEntry']['blog_key'] = 'blog1';

		$data['Comment']['comment'] = '';

		$this->testAction(
			'/blogs/blog_entries_edit/add/1',
			array(
				'method' => 'post',
				'data' => $data,
			)
		);
		$this->assertRegExp('#blogs/blog_entries/view#', $this->headers['Location']);
		AuthGeneralControllerTest::logout($this);
	}

}

