<?php
/**
 * BlogBlocksController::index()のPaginatorテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlocksControllerPaginatorTest', 'Blocks.TestSuite');

/**
 * BlogBlocksController::index()のPaginatorテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Controller\BlogBlocksController
 */
class BlogBlocksControllerIndexPaginatorTest extends BlocksControllerPaginatorTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.block_setting_for_blog',
		'plugin.blogs.blog4paginator',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'blogs';

/**
 * Controller name
 *
 * @var string
 */
	protected $_controller = 'blog_blocks';

/**
 * Edit controller name
 *
 * @var string
 */
	protected $_editController = 'blog_blocks';

}
