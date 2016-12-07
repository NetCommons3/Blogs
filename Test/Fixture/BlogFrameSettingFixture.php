<?php
/**
 * BlogFrameSettingFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for BlogFrameSettingFixture
 */
class BlogFrameSettingFixture extends CakeTestFixture {

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => '1',
			'frame_key' => 'frame_key_1',
			'articles_per_page' => '1',
			'created_user' => '1',
			'created' => '2016-03-17 07:10:45',
			'modified_user' => '1',
			'modified' => '2016-03-17 07:10:45'
		),
		array(
			'id' => '2',
			'frame_key' => 'frame_key_2',
			'articles_per_page' => '20',
			'created_user' => '2',
			'created' => '2016-03-17 07:10:45',
			'modified_user' => '2',
			'modified' => '2016-03-17 07:10:45'
		),
		array(
			'id' => '6', // @see BlogBlocksControllerBeforeFilterTest
			'frame_key' => 'frame_3',
			'articles_per_page' => '20',
			'created_user' => '2',
			'created' => '2016-03-17 07:10:45',
			'modified_user' => '2',
			'modified' => '2016-03-17 07:10:45'
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Blogs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new BlogsSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
