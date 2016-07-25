<?php
/**
 * BlogFrameSettingsController::edit()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @blog http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('FrameSettingsControllerTest', 'Frames.TestSuite');

/**
 * BlogFrameSettingsController::edit()のテスト
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogFrameSettingsController
 */
class BlogFrameSettingsControllerEditTest extends FrameSettingsControllerTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.block_setting_for_blog',
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
 * テストDataの取得
 *
 * @return array
 */
	private function __data() {
		$frameId = '6';
		$frameKey = 'frame_3';
		$blogFrameId = '6';

		$data = array(
			'Frame' => array(
				'id' => $frameId,
				'key' => $frameKey,
			),
			'BlogFrameSetting' => array(
				'id' => $blogFrameId,
				'frame_key' => $frameKey,
				'articles_per_page' => '10',
			),
		);

		return $data;
	}

/**
 * edit()アクションDataProvider
 *
 * ### 戻り値
 *  - method: リクエストメソッド（get or post or put）
 *  - data: 登録データ
 *  - validationError: バリデーションエラー(省略可)
 *  - exception: Exception Error(省略可)
 *
 * @return array
 */
	public function dataProviderEdit() {
		$data = $this->__data();

		//テストデータ
		$results = array();
		$results[0] = array('method' => 'get');
		$results[1] = array('method' => 'post', 'data' => $data, 'validationError' => false);
		$results[2] = array('method' => 'put', 'data' => $data, 'validationError' => false);
		$results[3] = array('method' => 'put', 'data' => $data,
			'validationError' => array(
				'field' => 'BlogFrameSetting.frame_key',
				'value' => null,
			),
			'BadRequestException'
		);

		return $results;
	}

}
