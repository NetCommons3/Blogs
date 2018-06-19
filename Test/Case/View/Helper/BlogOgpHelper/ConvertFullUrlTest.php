<?php
/**
 * SnsButtonHelper::twitter()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsHelperTestCase', 'NetCommons.TestSuite');

/**
 * SnsButtonHelper::twitter()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\NetCommons\Test\Case\View\Helper\SnsButtonHelper
 */
class BlogOgpConvertFullUrlTest extends NetCommonsHelperTestCase {

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
		//Helperロード
		$this->loadHelper('Blogs.BlogOgp', $viewVars, $requestData, $params);

		$stub = $this->getMockBuilder('NetCommonsHtml')
			->setMethods(['url'])
			->getMock();
		$map = [
			['/blogs/blog_entries/view/entry_1'],
			//[null, [], FULL_BASE_URL . '/blogs/blog_entries/view/entry_1'],
			['/blogs/blog_entries/view/../foo/bar.jpg', true, FULL_BASE_URL . '/blogs/blog_entries/view/../foo/bar.jpg']
		];

		$stub->method('url')
			->will($this->returnValueMap($map));
		$this->BlogOgp->NetCommonsHtml = $stub;
	}

/**
 * Tset BlogOgp::__convertFullUrl()
 *
 * @param string $imageUrl image url
 * @param string $fullUrl フルURLの期待値
 * @throws ReflectionException
 * @return void
 * @dataProvider data4ConvertFullUrl
 */
	public function testConvertFullUrl($imageUrl, $fullUrl) {
		$method = new ReflectionMethod($this->BlogOgp, '__convertFullUrl');
		$method->setAccessible(true);

		$result = $method->invoke($this->BlogOgp, $imageUrl);
		debug($result);
		$this->assertEquals($fullUrl, $result);
	}

/**
 * testConvertFullUrl test case
 *
 * @return array
 */
	public function data4ConvertFullUrl() {
		$data = [
			[
				'imageUrl' => 'http://example.com/foo.jpg',
				'fullUrl' => 'http://example.com/foo.jpg'
			],
			[
				'imageUrl' => '/foo/bar.jpg',
				'fullUrl' => FULL_BASE_URL . '/foo/bar.jpg'
			],
			[
				'imageUrl' => '../foo/bar.jpg',
				'fullUrl' => FULL_BASE_URL . '/blogs/blog_entries/view/../foo/bar.jpg'
			],

		];
		return $data;
	}
}
