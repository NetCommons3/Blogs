<?php
/**
 * BlogSetting Test Case
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogSetting', 'Blogs.Model');

/**
 * Summary for BlogSetting Test Case
 *
 * @property BlogSetting $BlogSetting
 */
class BlogSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog_setting',
		'plugin.blocks.block_role_permission',
		'plugin.users.user',
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BlogSetting = ClassRegistry::init('Blogs.BlogSetting');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BlogSetting);

		parent::tearDown();
	}

/**
 * testGetBlogSetting method
 *
 * @return void
 */
	public function testGetBlogSetting() {
		$blogKey = 'blog1';
		$blogSetting = $this->BlogSetting->getBlogSetting($blogKey);
		$this->assertEquals(1, $blogSetting['BlogSetting']['id']);
	}

/**
 * testSaveBlogSetting method
 *
 * @return void
 */
	public function testSaveBlogSetting() {
		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$data['BlockRolePermission'] = array();
		$resultTrue = $this->BlogSetting->saveBlogSetting($data);
		$this->assertTrue($resultTrue);

		// validate fail
		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['validateBlogSetting']);
		$BlogSettingMock->expects($this->once())
			->method('validateBlogSetting')
			->will($this->returnValue(false));
		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$data['BlockRolePermission'] = array();

		$resultFalse = $BlogSettingMock->saveBlogSetting($data);
		$this->assertFalse($resultFalse);

		// save fail
		$BlogSettingMock = $this->getMockForModel('Blogs.BlogSetting', ['save']);
		$BlogSettingMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$data['BlockRolePermission'] = array();

		$this->setExpectedException('InternalErrorException');
		$BlogSettingMock->saveBlogSetting($data);
	}

/**
 * test saveBlogSetting BlockRolePermission保存失敗系テスト
 *
 * @return void
 */
	public function testSaveBlogSettingWithBlockRolePermissionFail() {
		// blockRolePermission validate fail
		$Mock = $this->getMockForModel('Blocks.BlockRolePermission', ['validateBlockRolePermissions']);
		$Mock->expects($this->once())
			->method('validateBlockRolePermissions')
			->will($this->returnValue(false));

		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$data['BlockRolePermission'] = array('dummy');
		$resultFalse = $this->BlogSetting->saveBlogSetting($data);
		$this->assertFalse($resultFalse);

		$Mock = $this->getMockForModel('Blocks.BlockRolePermission', ['validateBlockRolePermissions', 'saveMany']);
		$Mock->expects($this->once())
			->method('validateBlockRolePermissions')
			->will($this->returnValue(true));
		$Mock->expects($this->once())
			->method('saveMany')
			->will($this->returnValue(false));

		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$data['BlockRolePermission'] = array('dummy');

		$this->setExpectedException('InternalErrorException');
		$this->BlogSetting->saveBlogSetting($data);
	}

/**
 * testValidateBlogSetting method
 *
 * @return void
 */
	public function testValidateBlogSetting() {
		$data = $this->BlogSetting->getNew();
		$data['BlogSetting']['blog_key'] = 'new_blog_key';
		$resultTrue = $this->BlogSetting->validateBlogSetting($data);
		$this->assertTrue($resultTrue);
	}
}
