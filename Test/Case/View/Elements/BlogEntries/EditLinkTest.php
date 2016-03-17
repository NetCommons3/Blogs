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
	public $fixtures = array();

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
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_entries_edit_link/edit_link',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogEntries/edit_link', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//TODO:必要に応じてassert追加する
		debug($this->view);
	}

}
