<?php
/**
 * BlogEntry::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('BlogEntryFixture', 'Blogs.Test/Fixture');

/**
 * BlogEntry::validate()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryValidateTest extends NetCommonsValidateTest {

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
	protected $_methodName = 'validates';

/**
 * ValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - field フィールド名
 *  - value セットする値
 *  - message エラーメッセージ
 *  - overwrite 上書きするデータ(省略可)
 *
 * @return array テストデータ
 */
	public function dataProviderValidationError() {
		$data['BlogEntry'] = (new BlogEntryFixture())->records[0];

		return array(
			// タイトル無し
			array('data' => $data, 'field' => 'title', 'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Title'))),
			// 本文1無し
			array('data' => $data, 'field' => 'body1', 'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Body1'))),
			// publish_start 不正な年月日時分秒
			array('data' => $data, 'field' => 'publish_start', 'value' => '',
				sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Published datetime'))),
			array('data' => $data, 'field' => 'publish_start', 'value' => '2016-02-30 00:00:00',
				'message' => __d('net_commons', 'Invalid request.')),
			// publish_start 年月日時分秒になってない
			array('data' => $data, 'field' => 'publish_start', 'value' => 'Random string',
				'message' => __d('net_commons', 'Invalid request.')),

			// category_id CategoryBehavior
			//array('data' => $data, 'field' => 'category_id', 'value' => '100',
			//	'message' => __d('net_commons', 'Invalid request.')),

			// status 存在しないステータス
			//array('data' => $data, 'field' => 'status', 'value' => '10', // WorkflowBehavior
			//	'message' => __d('net_commons', 'Invalid request.')),
		);
	}

}
