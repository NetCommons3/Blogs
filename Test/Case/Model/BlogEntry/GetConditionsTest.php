<?php
/**
 * BlogEntry::getConditions()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('WorkflowGetTest', 'Workflow.TestSuite');

/**
 * BlogEntry::getConditions()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\BlogEntry
 */
class BlogEntryGetConditionsTest extends WorkflowGetTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.blog_setting',
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
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'BlogEntry';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'getConditions';

/**
 * test getConditions content_readable = false
 *
 * @return void
 */
	public function testGetConditionsForNotReadable() {
		$permissions = [
			'content_readable' => false,
		];
		$result = $this->BlogEntry->getConditions(1, $permissions);
		$this->assertEquals(['BlogEntry.id' => 0], $result);
	}

/**
 * test getConditions content_readable = true
 *
 * @return void
 */
	public function testGetConditionsForReadable() {
		$permissions = [
			'content_readable' => true,
		];
		$blockId = 1;
		$result = $this->BlogEntry->getConditions($blockId, $permissions);
		$this->assertEquals($blockId, $result['BlogEntry.block_id']);
	}
}
