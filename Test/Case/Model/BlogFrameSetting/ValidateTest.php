<?php
/**
 * BlogFrameSetting::validate()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsValidateTest', 'NetCommons.TestSuite');
App::uses('BlogFrameSettingFixture', 'Blogs.Test/Fixture');

/**
 * BlogFrameSetting::validate()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogFrameSetting
 */
class BlogFrameSettingValidateTest extends NetCommonsValidateTest {

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
		'plugin.categories.categories_language',
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
	protected $_modelName = 'BlogFrameSetting';

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
		$data['BlogFrameSetting'] = (new BlogFrameSettingFixture())->records[0];

		return array(
			array('data' => $data, 'field' => 'frame_key', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'articles_per_page', 'value' => '',
				'message' => __d('net_commons', 'Invalid request.')),
			array('data' => $data, 'field' => 'articles_per_page', 'value' => 'string',
				'message' => __d('net_commons', 'Invalid request.')),
		);
	}

}
