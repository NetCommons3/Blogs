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
class Controller_BlogEntries_ViewTest extends BlogsAppControllerTestBase {

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
 * test view.編集リンクの表示テスト
 *
 * @param string $role ロール
 * @param bool $viewEditLink 編集リンクが表示されるか
 * @dataProvider editLinkDataProvider
 * @return void
 */
	public function testEditLink($role, $viewEditLink) {
		RolesControllerTest::login($this, $role);
		$view = $this->testAction(
			'/blogs/blog_entries/view/1/key:6',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertInternalType('array', $this->vars['blogEntry']);
		if ($viewEditLink) {
			$this->assertTextContains('nc-blog-edit-link', $view);
		} else {
			$this->assertTextNotContains('nc-blog-edit-link', $view);
		}
		AuthGeneralControllerTest::logout($this);
	}

/**
 * testEditLink用dataProvider
 *
 * @return array
 */
	public function editLinkDataProvider() {
		$data = [
			['chief_editor', true],
			['editor', true],
			['general_user', true],
			['visitor', false],
		];
		return $data;
	}

/**
 * test view action まだ公開されてない記事はNotFoundException
 *
 * @return void
 */
	public function testViewNotFound() {
		$this->setExpectedException('NotFoundException');
		// key:4はまだ公開されてない
		$this->testAction(
			'/blogs/blog_entries/view/1/key:4',
			array(
				'method' => 'get',
				//'return' => 'view',
			)
		);
	}

/**
 * test view . タグの表示
 *
 * @return void
 */
	public function testViewWithTag() {
		$view = $this->testAction(
			'/blogs/blog_entries/view/1/key:1',
			array(
				'method' => 'get',
				'return' => 'view',
			)
		);
		$this->assertTextContains('Tag1', $view);
	}

/**
 * test view action content comment post fail -> bad request
 *
 * @return void
 */
	public function testViewContentCommentPostFailed() {
		$blogEntriesMock = $this->generate(
			'Blogs.BlogEntries',
			[
				'components' => [
					'Auth' => ['user'],
					'Session',
					'Security',
					'ContentComments.ContentComments' => ['comment']
				],
			]
		);
		$blogEntriesMock->ContentComments->expects($this->once())
			->method('comment')
			->will($this->returnValue(false));

		$this->setExpectedException('BadRequestException');

		$this->testAction(
			'/blogs/blog_entries/view/1/key:1',
			array(
				'method' => 'post',
				//'return' => 'view',
			)
		);
	}
}
