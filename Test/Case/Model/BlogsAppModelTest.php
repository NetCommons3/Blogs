<?php
/**
 * BlogAppModel Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogsAppModel', 'Blogs.Model');
App::uses('BlogsAppModelTestBase', 'Blogs.Test/Case/Model');
App::uses('TestingWrapper', 'Blogs.Test');

/**
 * Class BlogFakeModel テスト用Fakeモデル
 */
class BlogFakeModel extends BlogsAppModel {

/**
 * @var bool Fakeなのでテーブル使わない
 */
	public $useTable = false;
}

/**
 * Summary for BlogAppModel Test Case
 */
class BlogsAppModelTest extends BlogsAppModelTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog_entry',
		'plugin.categories.category',
		'plugin.categories.category_order',
		////'plugin.tags.tag',
		////'plugin.tags.tags_content',
		//'plugin.users.user', // Trackableビヘイビアでテーブルが必用
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		//$this->BlogAppModel = ClassRegistry::init('Blogs.BlogAppModel');
		$this->BlogEntry = ClassRegistry::init('Blogs.BlogEntry');
		$this->_unloadTrackable($this->BlogEntry);
		$this->BlogEntry->Behaviors->unload('Tag');
		$this->BlogEntry->Behaviors->unload('Like');
		$this->BlogEntry->Behaviors->unload('Category');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BlogAppModel);
		unset($this->BlogEntry);

		parent::tearDown();
	}

/**
 * test getNew
 *
 * @return void
 */
	public function testGetNew() {
		$new = $this->BlogEntry->getnew();

		$this->assertInternalType('array', $new);
	}

/**
 * test tarnsaction method
 *
 * @return void
 */
	public function testTransaction() {
		$this->BlogEntry->begin();

		$result = $this->_saveOneData();

		$savedData = $this->BlogEntry->findById($result['BlogEntry']['id']);
		$this->assertEquals('title', $savedData['BlogEntry']['title']);
		$this->BlogEntry->rollback();

		$savedDataNotFound = $this->BlogEntry->findById($result['BlogEntry']['id']);
		$this->assertEmpty($savedDataNotFound);

		$this->BlogEntry->begin();
		$result = $this->_saveOneData();
		$this->BlogEntry->commit();
		$savedDataFound = $this->BlogEntry->findById($result['BlogEntry']['id']);
		$this->assertEquals('title', $savedDataFound['BlogEntry']['title']);
	}

/**
 * _getValidateSpecificationテスト
 *
 * @return void
 */
	public function testGetValidateSpecification() {
		$BlogFakeModel = new BlogFakeModel();
		$BlogEntryTesting = new TestingWrapper($BlogFakeModel);
		$specificationArray = $BlogEntryTesting->_testing__getValidateSpecification();
		$this->assertInternalType('array', $specificationArray);
	}

/**
 * testTransaction用にデータ保存する
 *
 * @return mixed
 */
	protected function _saveOneData() {
		$data = $this->BlogEntry->getNew();
		$data['BlogEntry']['title'] = 'title';
		$data['BlogEntry']['body1'] = 'body1text';
		$data['BlogEntry']['status'] = 2;
		$data['BlogEntry']['key'] = 1;
		$data['BlogEntry']['language_id'] = 1;
		$data['BlogEntry']['block_id'] = 1;
		$data['BlogEntry']['blog_key'] = 'blog1';
		$result = $this->BlogEntry->save($data);
		return $result;
	}
}
