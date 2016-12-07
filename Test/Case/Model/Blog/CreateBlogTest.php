<?php
/**
 * Blog::createBlog()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');

/**
 * Blog::createBlog()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\Blog
 */
class BlogCreateBlogTest extends NetCommonsModelTestCase {

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
	protected $_modelName = 'Blog';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'createBlog';

/**
 * @var array Current::$current待避
 */
	protected $_current = array();

/**
 * setUp
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->_current = Current::$current;
		Current::$current['Room']['id'] = 1;
		Current::$current['language']['id'] = 2;
	}

/**
 * teadDown
 *
 * @return void
 */
	public function tearDown() {
		parent::tearDown();
		Current::$current = $this->_current;
	}

/**
 * createBlog()のテスト
 *
 * @return void
 */
	public function testCreateBlog() {
		$model = $this->_modelName;
		$methodName = $this->_methodName;

		//データ生成

		//テスト実施
		$result = $this->$model->$methodName();

		//チェック
		// ブログ名に New blogが含まれる
		$this->assertContains('New blog', $result['Blog']['name']);
		// BlogSettingがある
		$this->assertArrayHasKey('BlogSetting', $result);
		// Blockにroom_idがセットされてる
		$this->assertEquals(1, $result['Block']['room_id']);
	}

}
