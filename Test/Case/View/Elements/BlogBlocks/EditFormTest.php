<?php
/**
 * View/Elements/BlogBlocks/edit_formのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/BlogBlocks/edit_formのテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Elements\BlogBlocks\EditForm
 */
class BlogsViewElementsBlogBlocksEditFormTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestBlogs.TestViewElementsBlogBlocksEditForm');
	}

/**
 * View/Elements/BlogBlocks/edit_formのテスト
 *
 * @return void
 */
	public function testEditForm() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_blocks_edit_form/edit_form',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogBlocks/edit_form', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//debug($this->view);

		$this->assertInput(
			'input',
			'data[Block][id]',
			$this->controller->request->data['Block']['id'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[Block][key]',
			$this->controller->request->data['Block']['key'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogSetting][id]',
			$this->controller->request->data['BlogSetting']['id'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogFrameSetting][frame_key]',
			$this->controller->request->data['BlogFrameSetting']['frame_key'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogFrameSetting][articles_per_page]',
			$this->controller->request->data['BlogFrameSetting']['articles_per_page'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[Blog][name]',
			$this->controller->request->data['Blog']['name'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogSetting][use_comment]',
			$this->controller->request->data['BlogSetting']['use_comment'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogSetting][use_sns]',
			$this->controller->request->data['BlogSetting']['use_sns'],
			$this->view
		);
	}

}
