<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/07/23
 * Time: 19:36
 */
App::uses('Controller', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('BlogEntryPermissionComponent', 'Blogs.Controller/Component');

/**
 * Class BlogsFakeController Fakeコントローラ
 */
class BlogsFakeController extends Controller {
}

/**
 * Class BlogEntryPermissionComponentTest
 */
class BlogEntryPermissionComponentTest extends CakeTestCase {

/**
 * @var BlogEntryPermissionComponent テスト対象
 */
	public $BlogEntryPermission = null;

/**
 * @var Controller テストに使うFakeコントローラ
 */
	public $Controller = null;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		// コンポーネントと偽のテストコントローラをセットアップする
		$Collection = new ComponentCollection();
		$this->BlogEntryPermission = new BlogEntryPermissionComponent($Collection);
		$CakeRequest = new CakeRequest();
		$CakeResponse = new CakeResponse();
		$this->Controller = new BlogsFakeController($CakeRequest, $CakeResponse);
		$this->BlogEntryPermission->startup($this->Controller);
	}

/**
 * test canEdit Editable
 *
 * @return void
 */
	public function testCanEdit4Editable() {
		$this->Controller->viewVars['contentEditable'] = true;
		$blogEntry = [
			'BlogEntry' => [
				'created_user' => 1
			]
		];

		$resultTrue = $this->BlogEntryPermission->canEdit($blogEntry);
		$this->assertTrue($resultTrue);
	}

/**
 * test canEdit Creatable
 *
 * @return void
 */
	public function testCanEdit4Creatable() {
		$this->Controller->viewVars['contentEditable'] = false;
		$this->Controller->viewVars['contentCreatable'] = true;
		$this->Controller->Auth = $this->getMock('Auth', ['user']);
		$this->Controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue(1));

		$blogEntry = [
			'BlogEntry' => [
				'created_user' => 1
			]
		];

		$resultTrue = $this->BlogEntryPermission->canEdit($blogEntry);
		$this->assertTrue($resultTrue);
	}

/**
 * test canEdit NotCreatedUser
 *
 * @return void
 */
	public function testCanEdit4NotCreatedUser() {
		$this->Controller->viewVars['contentEditable'] = false;
		$this->Controller->viewVars['contentCreatable'] = true;
		$this->Controller->Auth = $this->getMock('Auth', ['user']);
		$this->Controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue(4));

		$blogEntry = [
			'BlogEntry' => [
				'created_user' => 1
			]
		];
		$resultFalse = $this->BlogEntryPermission->canEdit($blogEntry);
		$this->assertFalse($resultFalse);
	}

/**
 * test canEdit No Permission
 *
 * @return void
 */
	public function testCanEdit4NoPermission() {
		$this->Controller->viewVars['contentEditable'] = false;
		$this->Controller->viewVars['contentCreatable'] = false;
		$blogEntry = [
			'BlogEntry' => [
				'created_user' => 1
			]
		];
		$resultFalse = $this->BlogEntryPermission->canEdit($blogEntry);
		$this->assertFalse($resultFalse);
	}

/**
 * test canDelete
 *
 * @param bool $canEdit 編集可能か
 * @param bool $contentPublishable 公開権限
 * @param bool $yetPublish 未公開
 * @param int $accessUserId アクセスユーザID
 * @param bool $expected 予想される結果
 *
 * @return void
 *
 * @dataProvider canDeleteTestProvider
 */
	public function testCanDelete($canEdit, $contentPublishable, $yetPublish, $accessUserId, $expected) {
		$this->Controller->viewVars['contentCreatable'] = $canEdit;
		$this->Controller->viewVars['contentEditable'] = $canEdit;
		$this->Controller->viewVars['contentPublishable'] = $contentPublishable;

		$this->Controller->Auth = $this->getMock('Auth', ['user']);
		$this->Controller->Auth->expects($this->any())
			->method('user')
			->will($this->returnValue($accessUserId));

		$this->Controller->BlogEntry = $this->getMockForModel('Blogs.BlogEntry', ['yetPublish']);
		$this->Controller->BlogEntry->expects($this->any())
			->method('yetPublish')
			->will($this->returnValue($yetPublish));

		$blogEntry = [
			'BlogEntry' => [
				'created_user' => 1
			]
		];

		$resultBool = $this->BlogEntryPermission->canDelete($blogEntry);
		$this->assertEquals($expected, $resultBool);
	}

/**
 * canDelete data provider
 *
 * @return array テストデータ
 */
	public function canDeleteTestProvider() {
		$data = [
			// canEdit, publishable, yetPublish, userId, result
			// 本人パターン
			[true, true, true, 1, true],
			[true, true, false, 1, true],
			[true, false, true, 1, true],
			[true, false, false, 1, false],
			[false, false, false, 1, false],
			// 別ユーザパターン
			[true, true, true, 2, true],
			[true, true, false, 2, true],
			[true, false, true, 2, true],
			[true, false, false, 2, false],
			[false, false, false, 2, false],
		];
		return $data;
	}

/**
 * teatDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		// 終了した後のお掃除
		unset($this->BlogEntryPermission);
		unset($this->Controller);
	}
}