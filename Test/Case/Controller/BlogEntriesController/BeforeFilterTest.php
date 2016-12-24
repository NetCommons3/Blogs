<?php
/**
 * BlogEntriesController::beforeFilter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * BlogEntriesController::beforeFilter()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogEntriesController
 */
class BlogEntriesControllerBeforeFilterTest extends NetCommonsControllerTestCase {

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
		'plugin.tags.tags_content',
		'plugin.tags.tag',
		'plugin.content_comments.content_comment',
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
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'blog_entries';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		//TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * index()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testBeforeFilterGet() {
		//テスト実行
		$blockId = '2';

		// Loginしてなくても'index', 'view', 'category', 'tag', 'year_month'にアクセスできる

		$this->_testGetAction(array('action' => 'index', 'block_id' => $blockId), array('method' => 'assertNotEmpty'), false, 'view');
		$this->_testGetAction(array('action' => 'view', 'block_id' => $blockId, 'key' => (new BlogEntryFixture())->records[0]['key']), array('method' => 'assertNotEmpty'), false, 'view');
		$this->_testGetAction(array('action' => 'tag', 'block_id' => $blockId, 'id' => 1), array('method' => 'assertNotEmpty'), false, 'view');
		$this->_testGetAction(array('action' => 'year_month', 'block_id' => $blockId, 'year_month' => '2015-01'), array('method' => 'assertNotEmpty'), false, 'view');
	}

}
