<?php
/**
 * BlogBlocksController::add(),edit(),delete()
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlocksControllerEditTest', 'Blocks.TestSuite');

/**
 * BlogBlocksController::add(),edit(),delete()
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogBlocksController
 */
class BlogBlocksControllerEditTest extends BlocksControllerEditTest {

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
	protected $_controller = 'blog_blocks';

/**
 * Edit controller name
 *
 * @var string
 */
	protected $_editController = 'blog_blocks';

/**
 * テストDataの取得
 *
 * @param bool $isEdit 編集かどうか
 * @return array
 */
	private function __data($isEdit) {
		$frameId = '6';
		//$frameKey = 'frame_3';
		if ($isEdit) {
			$blockId = '4';
			$blockKey = 'block_2';
			$blogId = '3';
			$blogKey = 'blog_key_2';
		} else {
			$blockId = null;
			$blockKey = null;
			$blogId = null;
			$blogKey = null;
		}

		$data = array(
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '2',
				'plugin_key' => $this->plugin,
				'public_type' => '1',
				'from' => null,
				'to' => null,
			),
			// 必要のデータセットをここに書く
			'Blog' => array(
				'id' => $blogId,
				'key' => $blogKey,
				'block_id' => $blockId,
				'name' => 'Blog name',
			),
		);

		return $data;
	}

/**
 * add()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderAdd() {
		$data = $this->__data(false);

		//テストデータ
		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'put');
		$results[2] = array('method' => 'post', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'post', 'data' => $data,
			'validationError' => array(
				'field' => 'Blog.name', //エラーにするフィールド指定
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog name')),
			)
		);

		return $results;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー
 *
 * @return array
 */
	public function dataProviderEdit() {
		$data = $this->__data(true);

		//テストデータ
		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'post');
		$results[2] = array('method' => 'put', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'put', 'data' => $data,
			'validationError' => array(
				'field' => 'Blog.name', //エラーにするフィールド指定
				'value' => '',
				'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog name')),
			)
		);

		return $results;
	}

/**
 * delete()アクションDataProvider
 *
 * ### 戻り値
 *  - data 削除データ
 *
 * @return array
 */
	public function dataProviderDelete() {
		$data = array(
			'Block' => array(
				'id' => '4',
				'key' => 'block_2',
			),
			// 必要に応じてパラメータ変更する
			'Blog' => array(
				'key' => 'content_block_2',
			),
		);

		//テストデータ
		$results = array();
		$results[0] = array('data' => $data);

		return $results;
	}

/**
 * BlogNotFoundでBadRequest
 *
 * @return void
 */
	public function testEditBlogNotFound() {
		//ログイン
		TestAuthGeneral::login($this);

		$this->_mockForReturnFalse('Blogs.Blog', 'getBlog', 1);

		// Blog::getBlog()がfalseならBadRequest
		//$this->setExpectedException('BadRequestException');

		//テスト実行
		$this->_testGetAction(array('action' => 'edit', 'block_id' => '2', 'frame_id' => '6'),
			false, 'BadRequestException', 'view');

		//ログアウト
		TestAuthGeneral::logout($this);
	}

}
