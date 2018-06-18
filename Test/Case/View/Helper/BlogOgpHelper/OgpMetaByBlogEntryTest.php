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
class BlogOgpOgpMetaByBlogEntryTest extends NetCommonsHelperTestCase {

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
	}

	public function testNoImageEntry() {
		$blogEntry = [
			'BlogEntry' => [
				'key' => 'entry_1',
				'title' => 'Blog entry title',
				'body1' => 'Body1 text.'
			]
		];

		$this->BlogOgp->ogpMetaByBlogEntry($blogEntry);

		// Viewにアクセスできるようにする
		$property = new ReflectionProperty($this->BlogOgp, '_View');
		$property->setAccessible(true);
		$view = $property->getValue($this->BlogOgp);
		$html = $view->fetch('meta');

		$this->assertTextContains('<meta property="og:title" content="Blog entry title"', $html);
		$this->assertTextContains(
			'<meta property="og:url" content="' . FULL_BASE_URL . '/blogs/blog_entries/view/entry_1"',
		$html);
		$this->assertTextContains('<meta property="og:description" content="Body1 text."', $html);
		$this->assertTextContains('<meta property="twitter:card" content="summary_large_image"', $html);
	}

}
