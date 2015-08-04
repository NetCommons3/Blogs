<?php
/**
 * Blog Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('Blog', 'Blogs.Model');
App::uses('BlogsAppModelTestBase', 'Blogs.Test/Case/Model');

/**
 * Summary for Blog Test Case
 *
 * @property Blog $Blog
 */
class BlogTest extends BlogsAppModelTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_setting',
		'plugin.blocks.block',
		'plugin.blocks.block_role_permission',
		'plugin.rooms.room',
		'plugin.rooms.roles_room',
		'plugin.categories.category',
		'plugin.categories.categoryOrder',
		'plugin.frames.frame',
		'plugin.boxes.box',
		'plugin.blogs.plugin',
		'plugin.m17n.language',
		'plugin.users.user',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->Blog = ClassRegistry::init('Blogs.Blog');
		$this->_unloadTrackable($this->Blog);
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->Blog);

		parent::tearDown();
	}

/**
 * testGetBlog method
 *
 * @return void
 */
	public function testGetBlog() {
		$blockId = 5;
		$roomId = 1;
		$blog = $this->Blog->getBlog($blockId, $roomId);
		$this->assertEquals('ブログ名', $blog['Blog']['name']);
	}

/**
 * testValidateBlog method
 *
 * @return void
 */
	public function testValidateBlog() {
		$data = $this->Blog->getNew();
		// validate fail
		$resultFalse = $this->Blog->validateBlog($data);
		$this->assertFalse($resultFalse);

		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = 'New Blog';
		//$data['Blog']['is_auto_translated'] = false;
		$resultTrue = $this->Blog->validateBlog($data);
		$this->assertTrue($resultTrue);
	}

/**
 * testValidateBlog method
 *
 * @return void
 */
	public function testValidateBlogWithModelFail() {
		$data = $this->Blog->getNew();
		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = 'New Blog';

		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['validateBlogSetting']);
		$BlogSettingMock->expects($this->once())
			->method('validateBlogSetting')
			->will($this->returnValue(false));


		$BlockMock = $this->getMockForModel('Blocks.Block', ['validateBlock']);
		$BlockMock->expects($this->once())
			->method('validateBlock')
			->will($this->returnValue(false));

		$CategoryMock = $this->getMockForModel('Categories.Category', ['validateCategories']);
		$CategoryMock->expects($this->once())
			->method('validateCategories')
			->will($this->returnValue(false));

		$this->Blog->loadModels([
			'Blog' => 'Blogs.Blog',
			'BlogSetting' => 'Blogs.BlogSetting',
			'Category' => 'Categories.Category',
			'Block' => 'Blocks.Block',
			//'Frame' => 'Frames.Frame',
		]);

		$resultFalse = $this->Blog->validateBlog($data, ['blogSetting']);
		$this->assertFalse($resultFalse);

		$this->Blog->create();
		$resultFalse = $this->Blog->validateBlog($data, ['block']);
		$this->assertFalse($resultFalse);

		$this->Blog->create();
		$resultFalse = $this->Blog->validateBlog($data, ['category']);
		$this->assertFalse($resultFalse);
	}

/**
 * testSaveBlog method
 *
 * @return void
 */
	public function testSaveBlog() {
		// validate fail
		$data = $this->Blog->getNew();
		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = ''; // validate error
		$data['Frame']['id'] = 1;
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$resultFalse = $this->Blog->saveBlog($data);
		$this->assertFalse($resultFalse);

		$data = $this->Blog->getNew();
		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = 'New Blog';
		$data['Frame']['id'] = 1;
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$resultTrue = $this->Blog->saveBlog($data);
		$this->assertTrue($resultTrue);
	}

/**
 * testSaveBlog InternalErrorException
 *
 * @return void
 */
	public function testSaveBlogInternalErrorException() {
		// save fail
		$BlogMock = $this->getMockForModel('Blogs.Blog', ['save']);
		$BlogMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$data = $this->Blog->getNew();
		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = 'New Blog';
		$data['Frame']['id'] = 1;
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		// save失敗で例外
		$this->setExpectedException('InternalErrorException');
		$BlogMock->saveBlog($data);
	}

/**
 * testSaveBlog fail
 *
 * @return void
 */
	public function testSaveBlogWithModelFail() {
		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['save']);
		$BlogSettingMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));

		$data = $this->Blog->getNew();
		$data['Blog']['key'] = 'new_blog_key';
		$data['Blog']['block_id'] = 5;
		$data['Blog']['name'] = 'New Blog';
		$data['Frame']['id'] = 1;
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		// BlogSetting->saveで例外
		$this->setExpectedException('InternalErrorException');
		$this->Blog->saveBlog($data);
	}

}
