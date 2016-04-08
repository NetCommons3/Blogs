<?php
/**
 * View/Elements/BlogFrameSettings/edit_formのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/BlogFrameSettings/edit_formのテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Elements\BlogFrameSettings\EditForm
 */
class BlogsViewElementsBlogFrameSettingsEditFormTest extends NetCommonsControllerTestCase {

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
		$this->generateNc('TestBlogs.TestViewElementsBlogFrameSettingsEditForm');
	}

/**
 * View/Elements/BlogFrameSettings/edit_formのテスト
 *
 * @return void
 */
	public function testEditForm() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_blog_frame_settings_edit_form/edit_form',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/BlogFrameSettings/edit_form', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		//TODO:必要に応じてassert追加する
		debug($this->view);

		//$this->assertInput(
		//	'input',
		//	'data[Block][id]',
		//	$this->controller->request->data['Block']['id'],
		//	$this->view
		//);
		//$this->assertInput(
		//	'input',
		//	'data[Block][key]',
		//	$this->controller->request->data['Block']['key'],
		//	$this->view
		//);
		//$this->assertInput(
		//	'input',
		//	'data[BlogSetting][id]',
		//	$this->controller->request->data['BlogSetting']['id'],
		//	$this->view
		//);
		$this->assertInput(
			'input',
			'data[Frame][id]',
			$this->controller->request->data['Frame']['id'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[Frame][key]',
			$this->controller->request->data['Frame']['key'],
			$this->view
		);
		$this->assertInput(
			'input',
			'data[BlogFrameSetting][id]',
			$this->controller->request->data['BlogFrameSetting']['id'],
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
	}

}
