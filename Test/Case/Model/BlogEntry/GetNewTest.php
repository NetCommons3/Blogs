<?php
/**
 * BlogEntry::getNew()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowGetTest', 'Workflow.TestSuite');

/**
 * BlogEntry::getNew()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryGetNewTest extends WorkflowGetTest {

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
	protected $_methodName = 'getNew';

/**
 * getNew()のテスト
 *
 * @return void
 */
	public function testGetNew() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		// NetCommonsTimeに現在日付を 2016-01-01 00:00:00 に設定する
		$nowProperty = new ReflectionProperty('NetCommonsTime', '_now');
		$nowProperty->setAccessible(true);
		$nowProperty->setValue(strtotime('2016-01-01 00:00:00'));
		// test code ..

		//テスト実施
		$result = $this->$model->$methodName();

		$this->assertEquals('2016-01-01 00:00:00', $result['BlogEntry']['publish_start']);

		$nowProperty->setValue(null); // 現在日時変更が他のテストに影響を与えないようにnullにもどしておく
	}
}
