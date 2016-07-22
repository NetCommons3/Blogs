<?php
/**
 * BlogEntriesEditController::add()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowControllerAddTest', 'Workflow.TestSuite');

/**
 * BlogEntriesEditController::add()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogEntriesEditController
 */
class BlogEntriesEditControllerAddTest extends WorkflowControllerAddTest {

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
	protected $_controller = 'blog_entries_edit';

/**
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$blockId = '2';
		$blockKey = 'block_1';

		$data = array(
			'save_' . WorkflowComponent::STATUS_IN_DRAFT => null,
			'Frame' => array(
				'id' => $frameId,
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '1',
				'plugin_key' => $this->plugin,
			),

			//必要のデータセットをここに書く
			'BlogEntry' => array(
				'id' => null,
				'key' => null,
				'language_id' => '2',
				'status' => null,
				'title' => 'BlogEntryTitle',
				'body1' => 'BlogEntryBody1',
				'publish_start' => '2016-01-01 10:00',
			),

			'WorkflowComment' => array(
				'comment' => 'WorkflowComment save test',
			),
		);

		return $data;
	}

/**
 * addアクションのGETテスト(ログインなし)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddGet() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		// * ログインなし
		$results[0] = array(
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'assert' => null, 'exception' => 'ForbiddenException',
		);

		return $results;
	}

/**
 * addアクションのGETテスト(作成権限あり)用DataProvider
 *
 * ### 戻り値
 *  - urlOptions: URLオプション
 *  - assert: テストの期待値
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddGetByCreatable() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array(
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			'assert' => array('method' => 'assertNotEmpty'),
		);

		// * フレームID指定なしテスト
		array_push($results, Hash::merge($results[0], array(
			'urlOptions' => array('frame_id' => null, 'block_id' => $data['Block']['id']),
			'assert' => array('method' => 'assertNotEmpty'),
		)));

		return $results;
	}

/**
 * addアクションのPOSTテスト用DataProvider
 *
 * ### 戻り値
 *  - data: 登録データ
 *  - role: ロール
 *  - urlOptions: URLオプション
 *  - exception: Exception
 *  - return: testActionの実行後の結果
 *
 * @return array
 */
	public function dataProviderAddPost() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		// * ログインなし
		$results[0] = array(
			'data' => $data, 'role' => null,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'exception' => 'ForbiddenException'
		);
		// * 作成権限あり
		$results[1] = array(
			'data' => $data, 'role' => Role::ROOM_ROLE_KEY_GENERAL_USER,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
		);
		// * フレームID指定なしテスト
		$results[2] = array(
			'data' => $data, 'role' => Role::ROOM_ROLE_KEY_ROOM_ADMINISTRATOR,
			'urlOptions' => array(
				'frame_id' => null,
				'block_id' => $data['Block']['id']),
		);

		return $results;
	}

/**
 * addアクションのValidationErrorテスト用DataProvider
 *
 * ### 戻り値
 *  - data: 登録データ
 *  - urlOptions: URLオプション
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderAddValidationError() {
		$data = $this->__data();
		$result = array(
			'data' => $data,
			'urlOptions' => array(
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id']
			),
			'validationError' => array(),
		);

		//テストデータ
		$results = array();
		array_push($results, Hash::merge($result, array(
			'validationError' => array(
				'field' => 'BlogEntry.title', // エラーにするフィールド指定
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Title')), //エラーメッセージ指定
			)
		)));

		return $results;
	}

/**
 * Viewのアサーション
 *
 * @param array $data テストデータ
 * @return void
 */
	private function __assertAddGet($data) {
		$this->assertInput(
			'input', 'data[Frame][id]', $data['Frame']['id'], $this->view
		);
		$this->assertInput(
			'input', 'data[Block][id]', $data['Block']['id'], $this->view
		);
	}

/**
 * view(ctp)ファイルのテスト(公開権限なし)
 *
 * @return void
 */
	public function testViewFileByCreatable() {
		TestAuthGeneral::login($this, Role::ROOM_ROLE_KEY_GENERAL_USER);

		//テスト実行
		$data = $this->__data();
		$this->_testGetAction(
			array(
				'action' => 'add',
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			array('method' => 'assertNotEmpty')
		);

		//チェック
		$this->__assertAddGet($data);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_APPROVED, null, $this->view);

		//debug($this->view);

		TestAuthGeneral::logout($this);
	}

/**
 * view(ctp)ファイルのテスト(公開権限あり)
 *
 * @return void
 */
	public function testViewFileByPublishable() {
		//ログイン
		TestAuthGeneral::login($this);

		//テスト実行
		$data = $this->__data();
		$this->_testGetAction(
			array(
				'action' => 'add',
				'frame_id' => $data['Frame']['id'],
				'block_id' => $data['Block']['id'],
			),
			array('method' => 'assertNotEmpty')
		);

		//チェック
		$this->__assertAddGet($data);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_IN_DRAFT, null, $this->view);
		$this->assertInput('button', 'save_' . WorkflowComponent::STATUS_PUBLISHED, null, $this->view);

		//debug($this->view);

		//ログアウト
		TestAuthGeneral::logout($this);
	}

}
