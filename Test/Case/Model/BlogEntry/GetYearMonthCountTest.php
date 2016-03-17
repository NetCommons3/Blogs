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
	protected $_methodName = 'getYearMonthCount';

/**
 * getYearMonthCount()のテスト
 *
 * @return void
 */
	public function testGetYearMonthCount() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$blockId = null;
		$userId = null;
		$permissions = null;
		$currentDateTime = null;

		//テスト実施
		$result = $this->$model->$methodName($blockId, $userId, $permissions, $currentDateTime);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
