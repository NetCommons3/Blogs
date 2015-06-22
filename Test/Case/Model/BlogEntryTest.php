<?php
/**
 * BlogEntry Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogEntry', 'Blogs.Model');

CakePlugin::load('NetCommons');
App::uses('NetCommonsBlockComponent', 'NetCommons.Controller/Component');
App::uses('TestingWrapper', 'Blogs.Test');

/**
 * Summary for BlogEntry Test Case
 */
class BlogEntryTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog_entry',
		'plugin.categories.category',
		'plugin.categories.category_order',
		//'plugin.tags.tag',
		//'plugin.tags.tags_content',
		'plugin.users.user', // Trackableビヘイビアでテーブルが必用
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BlogEntry = ClassRegistry::init('Blogs.BlogEntry');
		// モデルからビヘイビアをはずす:
		$this->BlogEntry->Behaviors->unload('Tag');
		$this->BlogEntry->Behaviors->unload('Trackable');
		$this->BlogEntry->Behaviors->unload('Like');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BlogEntry);

		parent::tearDown();
	}

/**
 * 記事削除テスト
 *
 * @return void
 */
	public function testDeleteEntryByOriginId() {
		$count2 = $this->BlogEntry->find('count', array('conditions' => array('origin_id' => 1)));

		$this->assertEqual($count2, 2);

		$deleted = $this->BlogEntry->deleteEntryByOriginId(1);
		$this->assertTrue($deleted);

		$count0 = $this->BlogEntry->find('count', array('conditions' => array('origin_id' => 1)));
		$this->assertEqual($count0, 0);
	}

/**
 * カテゴリ無しで保存するテスト
 *
 * @return void
 */
	public function testSaveNoCategory() {
		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = null; // category_idがnullでも保存できることを確認
		$data['BlogEntry']['title'] = 'title';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 1;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;

		$savedData = $this->BlogEntry->save($data);
		$this->assertTrue(isset($savedData['BlogEntry']['id']));
	}

/**
 * test _getPublishedConditions
 *
 * @return void
 */
	public function testGetPublishedConditions() {
		$BlogEntryTesting = new TestingWrapper($this->BlogEntry);
		$now = '2015-01-01 00:00:00';
		$conditions = $BlogEntryTesting->_testing__getPublishedConditions($now);
		$this->assertEquals(1, $conditions['BlogEntry.is_active']);
		$this->assertEquals($now, $conditions['BlogEntry.published_datetime <=']);
	}

	public function testGetCondition() {
		$userId = 1;
		$blockId = 2;
		$currentDateTime = '2015-01-01 00:00:00';
		// contentReadable false
		$permissions = array(
			'contentReadable' => false,
			'contentCreatable' => false,
			'contentEditable' => false,
		);
		$conditions = $this->BlogEntry->getConditions(
			$blockId,
			$userId,
			$permissions,
			$currentDateTime
		);
		$this->assertSame(
			$conditions,
			array(
				'BlogEntry.block_id' => $blockId,
				'BlogEntry.id' => 0
			)
		);

		// contentReadable のみ
		$permissions = array(
			'contentReadable' => true,
			'contentCreatable' => false,
			'contentEditable' => false,
		);
		$conditions = $this->BlogEntry->getConditions($blockId, $userId, $permissions, $currentDateTime);
		$this->assertSame(
			$conditions,
			array(
				'BlogEntry.block_id' => $blockId,
				'BlogEntry.is_active' => 1,
				'BlogEntry.published_datetime <=' => $currentDateTime
			)
		);

		// 作成権限あり
		$permissions = array(
			'contentReadable' => true,
			'contentCreatable' => true,
			'contentEditable' => false,
		);
		$conditions = $this->BlogEntry->getConditions($blockId, $userId, $permissions, $currentDateTime);
		$this->assertSame(
			$conditions,
			array(
				'BlogEntry.block_id' => $blockId,
				'OR' => array(
					array(
						'BlogEntry.is_active' => 1,
						'BlogEntry.published_datetime <=' => $currentDateTime
					),
					'BlogEntry.created_user' => $userId
				)
			)
		);

		// 編集権限あり
		$permissions = array(
			'contentReadable' => true,
			'contentCreatable' => true,
			'contentEditable' => true,
		);
		$conditions = $this->BlogEntry->getConditions($blockId, $userId, $permissions, $currentDateTime);
		$this->assertSame(
			$conditions,
			array(
				'BlogEntry.block_id' => $blockId,
				'BlogEntry.is_latest' => 1,
			)
		);
	}

	public function testGetYearMonthCount() {
		$blockId = 5;
		$userId = 1;
		$permissions = array(
			'contentCreatable' => true,
			'contentEditable' => true,
		);
		$currentDateTime = '2015-06-30 00:00:00';
		$counts = $this->BlogEntry->getYearMonthCount($blockId, $userId, $permissions, $currentDateTime);

		$this->assertEquals(1, $counts['2014-02']);
		$this->assertEquals(0, $counts['2014-03']);

		// 記事がひとつもないケース
		$blockId = 6;
		$counts = $this->BlogEntry->getYearMonthCount($blockId, $userId, $permissions, $currentDateTime);
		$this->assertEquals(1, count($counts));
		$this->assertEquals(0, $counts['2015-06']);
	}

	public function testBeforeSaveWithId() {
		$options = array();

		// IDがセットされてたらupdate なのでupdateAllされないはず
		$model = $this->getMockForModel('Blogs.BlogEntry', array('updateAll'));
		$model->expects($this->never())
			->method('updateAll');
			//->will($this->returnValue(true));
		$this->BlogEntry->data['BlogEntry']['id'] = 1;
		$resultTrue = $this->BlogEntry->beforeSave($options);
		$this->assertTrue($resultTrue);
	}

	public function testBeforeSave4Published() {
		$options = array();

		$this->BlogEntry->data['BlogEntry']['status'] = NetCommonsBlockComponent::STATUS_PUBLISHED;
		$this->BlogEntry->data['BlogEntry']['origin_id'] = 3;
		$this->BlogEntry->data['BlogEntry']['language_id'] = 1;

		$resultTrue = $this->BlogEntry->beforeSave($options);
		$this->assertTrue($resultTrue);

		$id3Data = $this->BlogEntry->findById(3);
		$this->assertEquals(0, $id3Data['BlogEntry']['is_latest']);
		$this->assertEquals(0, $id3Data['BlogEntry']['is_active']);

	}

	public function testSaveEntry() {
		
	}

	//
	//
	//public function testExecuteConditions() {
	//	$userId = 1;
	//	$blockId = 2;
	//	$currentDateTime = '2015-01-01 00:00:00';
	//
	//	// contentReadable false
	//	$permissions = array(
	//		'contentReadable' => false,
	//		'contentCreatable' => false,
	//		'contentEditable' => false,
	//	);
	//	$conditions = $this->BlogEntry->getConditions(
	//		$blockId,
	//		$userId,
	//		$permissions,
	//		$currentDateTime
	//	);
	//
	//	$result = $this->BlogEntry->find('all', array('conditions' => $conditions));
	//	$this->assertSame($result, array());
	//
	//	// contentReadable true
	//	$permissions = array(
	//		'contentReadable' => true,
	//		'contentCreatable' => false,
	//		'contentEditable' => false,
	//	);
	//	$conditions = $this->BlogEntry->getConditions(
	//		$blockId,
	//		$userId,
	//		$permissions,
	//		$currentDateTime
	//	);
	//
	//	$blogEntries = $this->BlogEntry->find('all', array('conditions' => $conditions));
	//	$this->assertEqual($blogEntries[0]['BlogEntry']['id'], 1);
	//
	//	$publishedEntryIs1 = $this->BlogEntry->find('count', array('conditions' => $conditions));
	//
	//	$this->assertEqual($publishedEntryIs1, 1);
	//
	//}
	//
	//public function testFind4CreatableUser() {
	//	$userId = 1;
	//	$blockId = 2;
	//	$currentDateTime = '2015-01-01 00:00:00';
	//
	//	// contentCreatable true
	//	$permissions = array(
	//		'contentReadable' => true,
	//		'contentCreatable' => true,
	//		'contentEditable' => false,
	//	);
	//	$conditions = $this->BlogEntry->getConditions(
	//		$blockId,
	//		$userId,
	//		$permissions,
	//		$currentDateTime
	//	);
	//
	//	$blogEntries = $this->BlogEntry->find('all', array('conditions' => $conditions));
	//
	//	$publishedAndMyEntriesAre3 = $this->BlogEntry->find('count', array('conditions' => $conditions));
	//
	//	$this->assertEqual($publishedAndMyEntriesAre3, 3);
	//
	//}
	//
	//public function testFind4EditableUser() {
	//	$userId = 1;
	//	$blockId = 2;
	//	$currentDateTime = '2015-01-01 00:00:00';
	//
	//	// contentCreatable true
	//	$permissions = array(
	//		'contentReadable' => true,
	//		'contentCreatable' => true,
	//		'contentEditable' => true,
	//	);
	//	$conditions = $this->BlogEntry->getConditions(
	//		$blockId,
	//		$userId,
	//		$permissions,
	//		$currentDateTime
	//	);
	//
	//	$blogEntries = $this->BlogEntry->find('all', array('conditions' => $conditions));
	//
	//	$entriesAre4 = $this->BlogEntry->find('count', array('conditions' => $conditions));
	//
	//	$this->assertEqual($entriesAre4, 4);
	//
	//}
}
