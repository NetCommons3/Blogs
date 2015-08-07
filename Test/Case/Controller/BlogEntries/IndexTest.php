<?php
/**
 * BlogEntriesController Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogEntriesController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * Summary for BlogEntriesController Test Case
 */
class Controller_BlogEntries_IndexTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->blogEntriesMock = $this->generate(
			'Blogs.BlogEntries',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
				]
			]
		);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		Configure::write('Config.language', null);
		CakeSession::write('Auth.User', null);
		parent::tearDown();
	}

/**
 * testIndex
 *
 * @return void
 */
	public function testIndex() {
		$view = $this->testAction(
			'/blogs/blog_entries/index/1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
		// ブログ名が表示される
		$this->assertRegExp('/<h1.*>ブログ名<\/h1>/', $view);
	}

/**
 * testTag
 *
 * @return void
 */
	public function testTag() {
		$this->testAction(
			'/blogs/blog_entries/tag/1/id:1',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * testYearMonth
 *
 * @return void
 */
	public function testYearMonth() {
		$this->testAction(
			'/blogs/blog_entries/year_month/1/year_month:2014-02',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntries']);
	}

/**
 * フレームがあってブロックがないときのテスト
 *
 * @return void
 */
	public function testNoBlock() {
		$result = $this->testAction(
			'/blogs/blog_entries/index/201',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertEquals('', $result);
	}

/**
 * カテゴリの記事一覧
 *
 * @return void
 */
	public function testCategory() {
		$return = $this->testAction(
			'/blogs/blog_entries/index/1/category_id:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertRegExp('/<h1.*>カテゴリ:category_1<\/h1>/', $return);
	}
}
