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
class BlogEntryFindTest extends CakeTestCase {

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
		'plugin.workflow.workflow_comment',
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
 * test _getPublishedConditions
 *
 * @return void
 */
	public function testGetPublishedConditions() {
		$BlogEntryTesting = new TestingWrapper($this->BlogEntry);
		$now = '2015-01-01 00:00:00';
		$conditions = $BlogEntryTesting->_testing__getPublishedConditions($now);
		$this->assertEquals(1, $conditions['BlogEntry.is_active']);
		$this->assertEquals($now, $conditions['BlogEntry.publish_start <=']);
	}

/**
 * test getCondition
 *
 * @return void
 */
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
				'BlogEntry.publish_start <=' => $currentDateTime
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
						'BlogEntry.publish_start <=' => $currentDateTime,
						'BlogEntry.created_user !=' => $userId,
					),
					array(
						'BlogEntry.created_user' => $userId,
						'BlogEntry.is_latest' => 1,
					)
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

/**
 * test getYearMonth
 *
 * @return void
 */
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

/**
 * 一度も公開になってないかを返すテスト
 *
 * @return void
 */
	public function testYetPublish() {
		$yetPublishEntry = $this->BlogEntry->findById(5);
		$resultTrue = $this->BlogEntry->yetPublish($yetPublishEntry);
		$this->assertTrue($resultTrue);

		$PublishedEntry = $this->BlogEntry->findById(2);
		$resultFalse = $this->BlogEntry->yetPublish($PublishedEntry);
		$this->assertFalse($resultFalse);
	}

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
