<?php
/**
 * BlogFrameSettingsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * BlogFrameSettingsController::edit()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogFrameSettingsController
 */
class BlogFrameSettingsControllerEditTest extends NetCommonsControllerTestCase {

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
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'blog_frame_settings';

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();

		//ログイン
		TestAuthGeneral::login($this);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		//ログアウト
		TestAuthGeneral::logout($this);

		parent::tearDown();
	}

/**
 * edit()アクションのGetリクエストテスト
 *
 * @return void
 */
	public function testEditGet() {
		//テストデータ
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId),
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$this->__assertEditGet($frameId, $blockId, $blockKey);
	}

/**
 * edit()のチェック
 *
 * @param int $frameId フレームID
 * @param int $blockId ブロックID
 * @param string $blockKey ブロックKey
 * @return void
 */
	private function __assertEditGet($frameId, $blockId, $blockKey) {
		//TODO:必要に応じてassert書く
		debug($this->view);
		debug($this->controller->request->data);

		$this->assertInput('form', null, 'blogs/blog_frame_settings/edit/' . $blockId, $this->view);
		$this->assertInput('input', '_method', 'PUT', $this->view);
		$this->assertInput('input', 'data[Frame][id]', $frameId, $this->view);
		$this->assertInput('input', 'data[Block][id]', $blockId, $this->view);
		$this->assertInput('input', 'data[Block][key]', $blockKey, $this->view);

		$this->assertEquals($frameId, Hash::get($this->controller->request->data, 'Frame.id'));
		$this->assertEquals($blockId, Hash::get($this->controller->request->data, 'Block.id'));
		$this->assertEquals($blockKey, Hash::get($this->controller->request->data, 'Block.key'));

		//TODO:必要に応じてassert書く
	}

/**
 * POSTリクエストデータ生成
 *
 * @return array リクエストデータ
 */
	private function __data() {
		$data = array(
			'Frame' => array(
				'id' => '6'
			),
			'Block' => array(
				'id' => '2', 'key' => 'block_1'
			),
			//TODO:必要に応じて、assertを追加する
		);

		return $data;
	}

/**
 * edit()アクションのPOSTリクエストテスト
 *
 * @return void
 */
	public function testEditPost() {
		//テストデータ
		$frameId = '6';
		$blockId = '2';

		//テスト実行
		$this->_testPostAction('put', $this->__data(),
				array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId), null, 'view');

		//チェック
		$header = $this->controller->response->header();
		$pattern = '/' . preg_quote('/blog/blog/index/' . $blockId, '/') . '/';
		$this->assertRegExp($pattern, $header['Location']);
	}

/**
 * ValidationErrorテスト
 *
 * @return void
 */
	public function testEditPostValidationError() {
		$this->_mockForReturnFalse('TODO:MockにするModel名書く', 'TODO:Mockにするメソッド名書く');

		//テストデータ
		$frameId = '6';
		$blockId = '2';

		//テスト実行
		//TODO:処理によって必要な方を有効にする
		$this->_testPostAction('put', $this->__data(),
				array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId), null, 'view');
		//$this->_testPostAction('put', $this->__data(),
		//		array('action' => 'edit', 'block_id' => $blockId, 'frame_id' => $frameId), 'BadRequestException', 'view');

		//TODO:必要に応じてassert書く
	}

}
