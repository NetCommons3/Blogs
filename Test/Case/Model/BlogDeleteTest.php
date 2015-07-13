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
 * testDeleteBlog method
 *
 * @return void
 */
	public function testDeleteBlog() {
		$data = $this->Blog->findById(1);
		$resultTrue = $this->Blog->deleteBlog($data);
		$this->assertTrue($resultTrue);
	}

/**
 * testDeleteBlog method
 *
 * @return void
 */
	public function testDeleteBlogInternalErrorException() {
		$BlogMock = $this->getMockForModel('Blogs.Blog', ['deleteAll']);
		$BlogMock->expects($this->once())
			->method('deleteAll')
			->will($this->returnValue(false));

		$data = $this->Blog->findById(1);
		$this->setExpectedException('InternalErrorException');
		$BlogMock->deleteBlog($data);
	}

/**
 * testDeleteBlog method
 *
 * @return void
 */
	public function testDeleteBlogWithBlogSettingInternalErrorException() {
		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['deleteAll']);
		$BlogSettingMock->expects($this->once())
			->method('deleteAll')
			->will($this->returnValue(false));

		$data = $this->Blog->findById(1);
		$this->setExpectedException('InternalErrorException');
		$this->Blog->deleteBlog($data);
	}

/**
 * testDeleteBlog method
 *
 * @return void
 */
	public function testDeleteBlogWithBlogEntryInternalErrorException() {
		$BlogEntryMock = $this->getMockForModel('Blogs.BlogEntry', ['deleteAll']);
		$BlogEntryMock->expects($this->once())
			->method('deleteAll')
			->will($this->returnValue(false));

		$data = $this->Blog->findById(1);
		$this->setExpectedException('InternalErrorException');
		$this->Blog->deleteBlog($data);
	}

}
