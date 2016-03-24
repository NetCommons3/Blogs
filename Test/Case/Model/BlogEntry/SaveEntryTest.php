<?php
/**
 * BlogEntry::saveEntry()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowSaveTest', 'Workflow.TestSuite');
App::uses('BlogEntryFixture', 'Blogs.Test/Fixture');

/**
 * BlogEntry::saveEntry()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntrySaveEntryTest extends WorkflowSaveTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.blog_setting',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
		'plugin.tags.tags_content',
		'plugin.tags.tag',
		'plugin.likes.like',
		'plugin.likes.likes_user',
		//'plugin.content_comments.content_comment',
		//'plugin.mails.mail_setting',
		//'plugin.mails.mail_queue',
		//'plugin.mails.mail_queue_user',
		//'plugin.site_manager.site_setting',
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
	protected $_methodName = 'saveEntry';

	public function setUp() {
		parent::setUp();
		$this->BlogEntry->Behaviors->unload('ContentComment');
	}

/**
 * Save用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array テストデータ
 */
	public function dataProviderSave() {
		$data['BlogEntry'] = (new BlogEntryFixture())->records[1];
		$data['BlogEntry']['status'] = '1';

		$results = array();
		// * 編集の登録処理
		$results[0] = array($data);
		// * 新規の登録処理
		$results[1] = array($data);
		$results[1] = Hash::insert($results[1], '0.BlogEntry.id', null);
		$results[1] = Hash::insert($results[1], '0.BlogEntry.key', null);
		$results[1] = Hash::remove($results[1], '0.BlogEntry.created_user');
		$results[1] = Hash::remove($results[1], '0.BlogEntry.created');

		return $results;
	}

/**
 * SaveのExceptionError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnExceptionError() {
		$data = $this->dataProviderSave()[0][0];

		//TODO:テストパタンを書く
		return array(
			array($data, 'Blogs.BlogEntry', 'save'),
		);
	}

/**
 * SaveのValidationError用DataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド(省略可：デフォルト validates)
 *
 * @return array テストデータ
 */
	public function dataProviderSaveOnValidationError() {
		$data = $this->dataProviderSave()[0][0];

		//TODO:テストパタンを書く
		return array(
			array($data, 'Blogs.BlogEntry'),
		);
	}

}
