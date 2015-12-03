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
class SandBoxTest extends BlogsAppModelTestBase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		//'plugin.blogs.blog',
		//'plugin.blogs.blog_entry',
		//'plugin.blogs.blog_setting',
		//'plugin.blocks.block',
		//'plugin.blocks.block_role_permission',
		//'plugin.rooms.room',
		//'plugin.rooms.roles_room',
		//'plugin.categories.category',
		//'plugin.categories.categoryOrder',
		//'plugin.frames.frame',
		//'plugin.boxes.box',
		//'plugin.blogs.plugin',
		//'plugin.m17n.language',
		//'plugin.users.user',
	);

	public function testIndex() {
		$e1 = new extend1();
		$e2 = new extend2();
		$e1::$staticVal = 'foo';
		debug($e2::$staticVal);
	}

}

class base
{
	static $staticVal;
}
class extend1 extends base{

}
class extend2 extends base{

}