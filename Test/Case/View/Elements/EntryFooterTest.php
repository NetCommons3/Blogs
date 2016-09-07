<?php
/**
 * View/Elements/entry_footerのテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsControllerTestCase', 'NetCommons.TestSuite');

/**
 * View/Elements/entry_footerのテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\View\Elements\EntryFooter
 */
class BlogsViewElementsEntryFooterTest extends NetCommonsControllerTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
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
		$this->generateNc('TestBlogs.TestViewElementsEntryFooter',
			[
				'helpers' => [
					'ContentComments.ContentComment' => array(
						'viewVarsKey' => array(
							'contentKey' => 'blogEntry.BlogEntry.key',
							'contentTitleForMail' => 'blogEntry.BlogEntry.title',
							'useComment' => 'blogSetting.use_comment',
							'useCommentApproval' => 'blogSetting.use_comment_approval'
						)
					),
				]
			]
		);
	}

/**
 * View/Elements/entry_footerのテスト
 *
 * @return void
 */
	public function testEntryFooter() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_entry_footer/entry_footer',
				array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/entry_footer', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		// use sns
		$this->assertContains('fb-like', $this->view);
		$this->assertContains('twitter.com', $this->view);
		// use like
		$this->assertContains('glyphicon-thumbs-up', $this->view);
		// indexでもLikeボタンを表示
		$this->assertContains('ng-controller="Likes"', $this->view);

		// indexではcontent comment countを表示
		$this->assertContains('blogs__content-comment-count', $this->view);
	}

/**
 * use_sns falseのテスト
 *
 * @return void
 */
	public function testNotUseSns() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_entry_footer/not_use_sns',
			array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/entry_footer', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		// use sns
		$this->assertNotContains('fb-like', $this->view);
		$this->assertNotContains('twitter.com', $this->view);
		// use like
		$this->assertContains('glyphicon-thumbs-up', $this->view);
		// indexでもLikeボタンを表示
		$this->assertContains('ng-controller="Likes"', $this->view);

		// indexではcontent comment countを表示
		$this->assertContains('blogs__content-comment-count', $this->view);
	}

/**
 * indexじゃないときのテスト
 *
 * @return void
 */
	public function testNotIndex() {
		//テスト実行
		$this->_testGetAction('/test_blogs/test_view_elements_entry_footer/not_index',
			array('method' => 'assertNotEmpty'), null, 'view');

		//チェック
		$pattern = '/' . preg_quote('View/Elements/entry_footer', '/') . '/';
		$this->assertRegExp($pattern, $this->view);

		// use sns

		$this->assertNotContains('fb-like', $this->view);
		$this->assertNotContains('twitter.com', $this->view);
		// use like
		$this->assertContains('glyphicon-thumbs-up', $this->view);
		// index以外でもLikeボタン表示
		$this->assertContains('ng-controller="Likes"', $this->view);

		// indexではcontent comment countを表示
		$this->assertNotContains('blogs__content-comment-count', $this->view);
	}

}
