<?php
/**
 * BlogEntriesController Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogEntriesController', 'Blogs.Controller');
App::uses('BlogsAppControllerTest', 'Blogs.Test/Case/Controller');

/**
 * Summary for BlogEntriesController Test Case
 */
class BlogEntriesControllerTest extends BlogsAppControllerTest {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->blogEntriesMock = $this->generate(
			'Blogs.BlogEntries',
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
		$this->testAction(
			'/blogs/blog_entries/index/1',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * ブログ名が一覧に表示されるか
 *
 * @return void
 */
	public function testIndexTitle() {
		$return = $this->testAction(
			'/blogs/blog_entries/index/1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertRegExp('/<h1.*>ブログ名<\/h1>/', $return);
	}

/**
 * testTag
 *
 * @return void
 */
	public function testTag() {
		$this->testAction(
			'/blogs/blog_entries/tag/1/id:1',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * testYearMonth
 *
 * @return void
 */
	public function testYearMonth() {
		$this->testAction(
			'/blogs/blog_entries/year_month/1/year_month:2014-02',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * testView
 *
 * @return void
 */
	public function testView() {
		$result = $this->testAction(
			'/blogs/blog_entries/view/1/origin_id:1',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntry']);
	}

/**
 * フレームがあってブロックがないときのテスト
 *
 * @return void
 */
	public function testNoBlock() {
		$result = $this->testAction(
			'/blogs/blog_entries/index/201',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertEquals('', $result);
	}

	// contentReadable falseならActionがコールされる前にガードされるので個別にテスト不要 NetCommonsRoomRoleComponentでやってると思われる
	//public function testViewNotReadable() {
	//	// visitorの閲覧権限を無しにする
	//	$RoomRolePermission = ClassRegistry::inist('Rooms.RoomRolePermission');
	//	$contentReadable = $RoomRolePermission->findByRolesRoomIdAndPermission(5, 'content_readable');
	//	$contentReadable['RoomRolePermission']['value'] = 0;
	//	$RoomRolePermission->save($contentReadable);
	//
	//	RolesControllerTest::login($this, Role::ROLE_KEY_VISITOR);
	//
	//	$this->setExpectedException('NotFoundException');
	//
	//	$result = $this->testAction(
	//		'/blogs/blog_entries/view/1/origin_id:1',
	//		array(
	//			'method' => 'get',
	//			//'return' => 'view',
	//		)
	//	);
	//	//$this->assertInternalType('array', $this->vars['blogEntry']);
	//	AuthGeneralControllerTest::logout($this);
	//}

/**
 * カテゴリの記事一覧
 *
 * @return void
 */
	public function testCategory() {
		$return = $this->testAction(
			'/blogs/blog_entries/index/1/category_id:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertRegExp('/<h1.*>カテゴリ:category_1<\/h1>/', $return);
	}
}
