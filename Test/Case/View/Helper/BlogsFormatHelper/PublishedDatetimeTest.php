<?php
/**
 * BlogsFormatHelper::publishedDatetime()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * BlogsFormatHelper::publishedDatetime()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Helper\BlogsFormatHelper
 */
class BlogsFormatHelperPublishedDatetimeTest extends NetCommonsHelperTestCase {

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
		//TODO:必要に応じてセットする
		$viewVars = array();
		$requestData = array();
		$params = array();

		//Helperロード
		$this->loadHelper('Blogs.BlogsFormat', $viewVars, $requestData, $params);
	}

/**
 * publishedDatetime()のテスト
 *
 * @return void
 */
	public function testPublishedDatetime() {
		//データ生成
		$datetime = null;

		//テスト実施
		$result = $this->BlogsFormat->publishedDatetime($datetime);

		//チェック
		//TODO:assertを書く
		debug($result);
	}

}
