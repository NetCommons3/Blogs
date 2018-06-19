<?php
/**
 * og:imageパラメータ取得のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * og:imageパラメータ取得のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\NetCommons\Test\Case\View\Helper\SnsButtonHelper
 */
class BlogOgpGetOgImageParamsTest extends NetCommonsHelperTestCase {

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

		//テストデータ生成
		$viewVars = array();
		$requestData = array();
		$params = array(
			'plugin' => 'blogs',
			'controller' => 'blog_entries',
			'action' => 'view',
			'key' => 'entry_1'
		);

		// テスト時のURL変換マップ Fixtureの画像にアクセスされるように変換
		$setting = [
			'localUrlMap' => [
				'http://app.local/img/' => dirname(dirname(dirname(dirname(__DIR__ )))) . '/Fixture/'
			]
		];
		Configure::write('ServerSetting', $setting);

		//Helperロード
		$this->loadHelper('Blogs.BlogOgp', $viewVars, $requestData, $params);
	}

/**
 * Test BlogOgp::__getOgImageParams()
 *
 * @param string $content html
 * @param array $ogImageParams og:image関連パラメータの期待値
 * @throws ReflectionException
 * @return void
 * @dataProvider data4ConvertFullUrl
 */
	public function testOgImageParams($content, $ogImageParams) {
		$method = new ReflectionMethod($this->BlogOgp, '__getOgImageParams');
		$method->setAccessible(true);

		$result = $method->invoke($this->BlogOgp, $content);
		$this->assertEquals($ogImageParams, $result);
	}

/**
 * testConvertFullUrl test case
 *
 * @return array
 */
	public function data4ConvertFullUrl() {
		$data = [
			[
				'content' => 'content no img tag',
				'ogImageParams' => []
			],
			[
				'content' => 'content have iamge. <img src="http://app.local/img/logo640x480.png" />',
				'ogImageParams' => [
					'og:image' => 'http://app.local/img/logo640x480.png',
					'og:image:width' => '640',
					'og:image:height' => '480'
				]
			],
			[
				'content' => 'content have iamge.' .
					'<img src="http://app.local/img/logo.gif" />' . // 規定サイズ以下
					'<img src="http://app.local/img/logo640x480.png" />', // 規定サイズ
				'ogImageParams' => [
					'og:image' => 'http://app.local/img/logo640x480.png',
					'og:image:width' => '640',
					'og:image:height' => '480'
				]
			],
			[
				'content' => 'Not found image. <img src="http://app.local/img/not_found.png" />',
				'ogImageParams' => [
				]
			],
		];
		return $data;
	}
}
