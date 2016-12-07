<?php
/**
 * View/Elements/BlogEntries/edit_linkのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/BlogEntries/edit_linkのテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Elements\BlogEntries\EditLink
 */
class BlogsViewElementsBlogEntriesEditLinkTest extends NetCommonsControllerTestCase {

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
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//テストプラグインのロード
		NetCommonsCakeTestCase::loadTestPlugin($this, 'Blogs', 'TestBlogs');
		//テストコントローラ生成
		$this->generateNc('TestBlogs.TestViewElementsBlogEntriesEditLink');
	}

/**
 * View/Elements/BlogEntries/edit_linkのテスト
 *
 * @return void
 */
	public function testEditLink() {
		//ログイン
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_EDITOR);

		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_entries_edit_link/edit_link/2?frame_id=6',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogEntries/edit_link', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		// リンクがある
		$this->assertContains('<a', $this->view);
		//ログアウト
		TestAuthGeneral::logout($this);
	}

/**
 * View/Elements/BlogEntries/edit_linkのテスト 権限がないと編集リンクは表示されない
 *
 * @return void
 */
	public function testEditLinkNotView() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_entries_edit_link/edit_link/2?frame_id=6',
			array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogEntries/edit_link', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		// リンクがある
		$this->assertNotContains('<a', $this->view);
	}

}
