<?php
/**
 * BlogFrameSettingsController Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

//App::uses('BlocksController', 'Blogs.Controller');
App::uses('BlogsAppControllerTestBase', 'Blogs.Test/Case/Controller');

/**
 * BlogsController Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller
 */
class BlogFrameSettingsControllerTest extends BlogsAppControllerTestBase {

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		Configure::write('Config.language', 'ja');
		$this->generate(
			'Blogs.BlogFrameSettings',
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
 * test edit.
 *
 * @return void
 */
	public function testEdit() {
		RolesControllerTest::login($this);

		$view = $this->testAction(
			'/blogs/blog_frame_settings/edit/1',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);

		$this->assertRegExp('/<form/', $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit.not found blogFrameSetting
 *
 * @return void
 */
	public function testEditNotFoundBlogFrameSetting() {
		RolesControllerTest::login($this);

		$view = $this->testAction(
			'/blogs/blog_frame_settings/edit/202',
			array(
				'method' => 'get',
				'return' => 'view'
			)
		);

		$this->assertRegExp('/<form/', $view);
		$this->assertFalse(isset($this->vars['blogFrameSetting']['id']));

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit. post fail
 *
 * @return void
 */
	public function testEditPostValidateError() {
		RolesControllerTest::login($this);

		$data = array();

		$view = $this->testAction(
			'/blogs/blog_frame_settings/edit/1',
			array(
				'method' => 'post',
				'data' => $data,
				'return' => 'view'
			)
		);

		$this->assertRegExp('/<form/', $view);

		AuthGeneralControllerTest::logout($this);
	}

/**
 * test edit. post sucess
 *
 * @return void
 */
	public function testEditPostSuccess() {
		RolesControllerTest::login($this);

		$data = [
			'BlogFrameSetting' => [
				'frame_key' => 'frame_1',
				'posts_per_page' => 1,
			]
		];

		$this->testAction(
			'/blogs/blog_frame_settings/edit/1',
			array(
				'method' => 'post',
				'data' => $data,
			)
		);

		$this->assertRegExp('#/blogs/blog_blocks/index/#', $this->headers['Location']);

		AuthGeneralControllerTest::logout($this);
	}

}