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
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'フレームKey', 'charset' => 'utf8'),
		'articles_per_page' => array('type' => 'integer', 'null' => false, 'default' => '10', 'unsigned' => false, 'comment' => '表示件数'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

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

}
