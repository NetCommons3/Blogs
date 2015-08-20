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
class BlogEntrySaveTest extends CakeTestCase {

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
		'plugin.comments.comment',
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
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'title';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 2;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;
		$data['BlogEntry']['blog_key'] = 'blog1';

		$savedData = $this->BlogEntry->save($data);
		$this->assertTrue(isset($savedData['BlogEntry']['id']));
	}

/**
 * コンテンツ削除時にコメントも削除が実行されるテスト
 *
 * @return void
 */
	public function testCommentDelete() {
		// origin_id=1 のテストデータのkeyはkey1なのでComment->deleteByContentKye('key1')がコールされるかテスト
		$mock = $this->getMockForModel('Comments.Comment', ['deleteByContentKey']);
		$mock->expects($this->once())
			->method('deleteByContentKey')
			->with(
				$this->equalTo('key1')
			);

		$this->BlogEntry->deleteEntryByOriginId(1);
	}

/**
 * 削除失敗時に例外がなげられるテスト
 *
 * @return void
 */
	public function testDeleteFail() {
		$BlogEntryMock = $this->getMockForModel('Blogs.BlogEntry', ['deleteAll']);
		$BlogEntryMock->expects($this->once())
			->method('deleteAll')
			->will($this->returnValue(false));

		// 例外のテスト
		$this->setExpectedException('InternalErrorException');
		$BlogEntryMock->Behaviors->unload('Tag');
		$BlogEntryMock->Behaviors->unload('Trackable');
		$BlogEntryMock->Behaviors->unload('Like');
		$BlogEntryMock->deleteEntryByOriginId(1);
	}

/**
 * test saveEntry
 *
 * @return void
 */
	public function testSaveEntry() {
		$CommentMock = $this->getMockForModel('Comments.Comment', ['validateByStatus']);
		$CommentMock->expects($this->once())
			->method('validateByStatus')
			->will($this->returnValue(true));

		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'testSaveEntry';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 3;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;
		$data['BlogEntry']['blog_key'] = 'blog1';

		$result = $this->BlogEntry->saveEntry(6, 6, $data);
		$this->assertTrue(isset($result['BlogEntry']['id']));
	}

/**
 * test saveEntry validate fail
 *
 * @return void
 */
	public function testSaveEntryInvalid() {
		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = ''; // invalid
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 3;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;

		$result = $this->BlogEntry->saveEntry(6, 6, $data);
		$this->assertFalse($result);
	}

/**
 * test saveEntry save fail
 *
 * @return void
 */
	public function testSaveEntryFailed() {
		$BlogEntryMock = $this->getMockForModel('Blogs.BlogEntry', ['save']);
		$BlogEntryMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'Title';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 3;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;

		// 例外のテスト
		$this->setExpectedException('InternalErrorException');
		$BlogEntryMock->saveEntry(6, 6, $data);
	}

/**
 * test saveEntry コメントバリデーション失敗test
 *
 * @return void
 */
	public function testSaveEntryCommentInvalid() {
		$CommentMock = $this->getMockForModel('Comments.Comment', ['validateByStatus']);
		$CommentMock->expects($this->once())
			->method('validateByStatus')
			->will($this->returnValue(false));

		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'testSaveEntry';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 3;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;
		$data['BlogEntry']['blog_key'] = 'blog1';

		$result = $this->BlogEntry->saveEntry(6, 6, $data);
		$this->assertFalse($result);
	}

/**
 * test saveEntry コメントsave失敗
 *
 * @return void
 */
	public function testSaveEntrySaveCommentFailed() {
		$CommentMock = $this->getMockForModel('Comments.Comment', ['validateByStatus', 'save']);
		$CommentMock->expects($this->once())
			->method('validateByStatus')
			->will($this->returnValue(true));
		$CommentMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$CommentMock->data = true; // saveEntry でthis->Comment->dataの有無チェックがあるので。

		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['category_id'] = 0;
		$data['BlogEntry']['title'] = 'testSaveEntry';
		$data['BlogEntry']['body1'] = 'body1';
		$data['BlogEntry']['key'] = '';
		$data['BlogEntry']['status'] = 3;
		$data['BlogEntry']['origin_id'] = 0;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['published_datetime'] = '2015-01-01 00:00:00';
		$data['BlogEntry']['block_id'] = 5;
		$data['BlogEntry']['blog_key'] = 'blog1';

		// 例外のテスト
		$this->setExpectedException('InternalErrorException');
		$this->BlogEntry->saveEntry(6, 6, $data);
	}
}
