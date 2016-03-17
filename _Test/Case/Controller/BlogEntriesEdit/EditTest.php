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
class BlogsEntriesEdit_EditTest extends BlogsAppControllerTestBase {

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
 * test edit action 編集対象コンテンツがなかったとき
 *
 * @return void
 */
	public function testEditNotFound() {
		$this->setExpectedException('NotFoundException');

		RolesControllerTest::login($this);
		$this->testAction(
			'/blogs/blog_entries_edit/edit/1/key:100',
			array(
				'method' => 'get',
			)
		);
		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. 権限がないとき
 *
 * @return void
 */
	public function testEditNoEditPermission() {
		RolesControllerTest::login($this, 'general_user');
		// key:1作成ユーザと異なるuser idを返させる
		$this->blogEntriesEditMock->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue(4));

		// 編集権限無しで他のユーザのコンテンツはedit NG
		$this->setExpectedException('ForbiddenException');
		$this->testAction(
			'/blogs/blog_entries_edit/edit/1/key:1',
			array(
				'method' => 'get',
			)
		);
		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. 権限がないとき
 *
 * @return void
 */
	public function testEditNoEditPermission4Visitor() {
		RolesControllerTest::login($this, Role::ROLE_KEY_VISITOR);
		// key:1作成ユーザと異なるuser idを返させる
		$this->blogEntriesEditMock->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue(4));

		// 編集権限無しで他のユーザのコンテンツはedit NG
		$this->setExpectedException('ForbiddenException');
		$this->testAction(
			'/blogs/blog_entries_edit/edit/1/key:1',
			array(
				'method' => 'get',
			)
		);
		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. validate fail
 *
 * @return void
 */
	public function testEditPutValidateFail() {
		$this->blogEntriesEditMock->NetCommonsWorkflow->expects($this->once())
			->method('parseStatus')
			->will($this->returnValue(1));

		RolesControllerTest::login($this);

		// validate error発生でhandleValidationError()が呼ばれる。
		$this->blogEntriesEditMock->expects($this->once())
			->method('handleValidationError')
			->with($this->isType('array'));
		$this->testAction(
			'/blogs/blog_entries_edit/edit/1/key:1',
			array(
				'method' => 'put',
			)
		);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit action. put success
 *
 * @return void
 */
	public function testEditPutSuccess() {
		$this->blogEntriesEditMock->NetCommonsWorkflow->expects($this->once())
			->method('parseStatus')
			->will($this->returnValue(1));

		RolesControllerTest::login($this);

		$BlogEntry = ClassRegistry::init('Blogs.BlogEntry');

		$data = $BlogEntry->findByKeyAndIsLatest(1, 1);

		$data['BlogEntry']['title'] = 'Edit title';
		$data['Comment']['comment'] = '';

		$this->testAction(
			'/blogs/blog_entries_edit/edit/1/key:1',
			array(
				'method' => 'put',
				'data' => $data,
			)
		);
		$this->assertRegExp('#blogs/blog_entries/view/1/key:1#', $this->headers['Location']);
		AuthGeneralControllerTest::logout($this);
	}

}

