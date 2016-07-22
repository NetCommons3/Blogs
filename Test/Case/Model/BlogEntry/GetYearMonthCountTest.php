<?php
/**
 * BlogEntry::getYearMonthCount()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowGetTest', 'Workflow.TestSuite');

/**
 * BlogEntry::getYearMonthCount()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryGetYearMonthCountTest extends WorkflowGetTest {

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
		'plugin.content_comments.content_comment',
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
	protected $_methodName = 'getYearMonthCount';

/**
 * @var ReflectionProperty NetCommonsTime::_now のリフレクションプロパティ
 */
	protected $_nowProperty;

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_nowProperty = new ReflectionProperty('NetCommonsTime', '_now');
		$this->_nowProperty->setAccessible(true);
		$this->_nowProperty->setValue(strtotime('2016-01-01 00:00:00'));
		$this->BlogEntry->Behaviors->unload('ContentComment');
	}

/**
 * tearDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		$this->_nowProperty->setValue(null); // 現在日時変更が他のテストに影響を与えないようにnullにもどしておく
	}

/**
 * getYearMonthCount()のテスト
 *
 * @param array $permissions 権限
 * @param array $expect getYearMonthCountの期待値
 * @return void
 * @dataProvider dataProvider4testGetYearMonthCount
 */
	public function testGetYearMonthCount($permissions, $expect) {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$blockId = 2;

		//テスト実施
		$result = $this->$model->$methodName($blockId, $permissions);
		$this->assertEquals($expect, $result);
	}

/**
 * dataProvider for testGetYearMonthCount
 *
 * @return array
 */
	public function dataProvider4testGetYearMonthCount() {
		// permission配列, key年月value記事数の配列
		$data = [
			// permission content_readable falseなら一つも記事が無い
			// 記事がひとつもないケース
			// 現在年月のみ返ってきてカウントは0
			[['content_readable' => false, ], ['2016-01' => 0]],
			// permission content_readable trueなら 記事が取得できる(このテストの権限はcontent_editable, content_publishableともにtrue @see WorkflowGetTest::setUp()
			// 記事がない年月は記事数0となっていること。
			// 記事がある年月は記事数が返ってくる
			// 2015年1月2件 2015年3月1件 他0件
			[['content_readable' => true], [
				'2016-03' => 2, // 未来の記事も件数を表示
				'2016-02' => 0,
				'2016-01' => 0, // 今月
				'2015-12' => 0,
				'2015-11' => 0,
				'2015-10' => 0,
				'2015-09' => 0,
				'2015-08' => 0,
				'2015-07' => 0,
				'2015-06' => 0,
				'2015-05' => 0,
				'2015-04' => 0,
				'2015-03' => 1,
				'2015-02' => 0,
				'2015-01' => 2,
				]
			],

		];
		return $data;
	}

}
