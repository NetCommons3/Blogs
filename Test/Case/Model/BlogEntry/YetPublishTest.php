<?php
/**
 * BlogEntry::yetPublish()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowGetTest', 'Workflow.TestSuite');
App::uses('BlogEntryFixture', 'Blogs.Test/Fixture');

/**
 * BlogEntry::yetPublish()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryYetPublishTest extends WorkflowGetTest {

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
		'plugin.tags.tags_content',
		'plugin.tags.tag',
		'plugin.likes.like',
		'plugin.likes.likes_user',
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
	protected $_methodName = 'yetPublish';

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BlogEntry->Behaviors->unload('ContentComment');
	}

/**
 * yetPublish()のテスト
 *
 * @param array $blogEntry テスト対応の記事
 * @param bool $expected yetPublishの期待値
 * @return void
 * @dataProvider dataProvider4testYetPublish
 */
	public function testYetPublish($blogEntry, $expected) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		//$blogEntry = null;

		//テスト実施
		$result = $this->$model->$methodName($blogEntry);

		$this->assertEquals($expected, $result);
	}

/**
 * dataProvider testYetPublish
 *
 * @return array
 */
	public function dataProvider4testYetPublish() {
		$fixture = new BlogEntryFixture();
		$data = [

			[['BlogEntry' => $fixture->records[1]], false], // 一度公開された記事
			[['BlogEntry' => $fixture->records[2]], true], // 一度も公開されたことがない記事
			[['BlogEntry' => $fixture->records[5]], false], // 現在公開されてる記事
		];
		return $data;
	}

}
