<?php
/**
 * BlogEntry::deleteEntryByKey()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowDeleteTest', 'Workflow.TestSuite');
App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('BlogEntryFixture', 'Blogs.Test/Fixture');

/**
 * BlogEntry::deleteEntryByKey()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryDeleteEntryByKeyTest extends NetCommonsModelTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.block_setting_for_blog',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'blogs';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'BlogEntry';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'deleteEntryByKey';

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_removeBehaviors($this->BlogEntry);
	}

/**
 * テストの邪魔になるビヘイビアとアソシエーションをひっぺがす
 *
 * @param BlogEntry $model BlogEntryモデル
 * @return void
 */
	protected function _removeBehaviors($model) {
		$model->Behaviors->unload('Tag');
		$model->Behaviors->unload('ContentComment');
		$model->Behaviors->unload('Like');
		$model->unbindModel(['belongsTo' => ['Like', 'LikesUser']], false);
	}

/**
 * testDeleteEntryByKey
 *
 * @return void
 */
	public function testDeleteEntryByKey() {
		$key = 'content_key_1';
		$result = $this->BlogEntry->deleteEntryByKey($key);
		$this->assertTrue($result);

		$count = $this->BlogEntry->find('count', ['conditions' => ['key' => $key]]);

		$this->assertEquals(0, $count);
	}

/**
 * testDeleteEntryByKey delete failed
 *
 * @return void
 */
	public function testDeleteEntryByKeyFailed() {
		$key = 'content_key_1';
		$blogEntryMock = $this->getMockForModel('Blogs.BlogEntry', ['deleteAll']);
		$blogEntryMock->expects($this->once())
			->method('deleteAll')
			->will($this->returnValue(false));

		$this->_removeBehaviors($blogEntryMock);

		$this->setExpectedException('InternalErrorException');
		$blogEntryMock->deleteEntryByKey($key);
	}

}
