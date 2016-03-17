<?php
/**
 * BlogSetting::getBlogSetting()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsGetTest', 'NetCommons.TestSuite');

/**
 * BlogSetting::getBlogSetting()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogSetting
 */
class BlogSettingGetBlogSettingTest extends NetCommonsGetTest {

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
	protected $_modelName = 'BlogSetting';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getBlogSetting';

/**
 * getBlogSetting()のテスト
 *
 * @return void
 */
	public function testGetBlogSetting() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成
		$blogKey = null;

		//テスト実施
		$result = $this->$model->$methodName($blogKey);

		//チェック
		//TODO:Assertを書く
		debug($result);
	}

}
