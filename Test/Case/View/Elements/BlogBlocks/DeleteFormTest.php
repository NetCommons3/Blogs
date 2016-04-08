<?php
/**
 * View/Elements/BlogBlocks/delete_formのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/BlogBlocks/delete_formのテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Elements\BlogBlocks\DeleteForm
 */
class BlogsViewElementsBlogBlocksDeleteFormTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestBlogs.TestViewElementsBlogBlocksDeleteForm');
	}

/**
 * View/Elements/BlogBlocks/delete_formのテスト
 *
 * @return void
 */
	public function testDeleteForm() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_blocks_delete_form/delete_form/2/?frame_id=6',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogBlocks/delete_form', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//debug($this->view);

		$this->assertTextContains(sprintf(__d('net_commons', 'Delete all data associated with the %s.'), __d('blogs', 'Blog')), $this->view);

		$this->assertInput('input', 'data[Block][id]', 10, $this->view);
		$this->assertInput('input', 'data[Block][key]', 'block_key_10', $this->view);
		$this->assertInput('input', 'data[Blog][key]', 'blog_key_10', $this->view);

	}

}
