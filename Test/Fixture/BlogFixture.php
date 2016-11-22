<?php
/**
 * BlogFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for BlogFixture
 */
class BlogFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID'),
		'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
		'name' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'BLOG名', 'charset' => 'utf8'),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'BLOGキー', 'charset' => 'utf8'),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => '作成者'),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '作成日時'),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => '更新者'),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => '更新日時'),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @see https://github.com/s-nakajima/MyShell/blob/master/unitTest/AboutFixture.md#ブロックidの紐付くfixture
 * @var array
 */
	public $records = array(
		array(
			'id' => 2,
			'block_id' => '2',
			'name' => 'BlockId2Blog',
			'key' => 'content_block_1',
			'created_user' => 1,
			'created' => '2016-03-17 07:09:43',
			'modified_user' => 1,
			'modified' => '2016-03-17 07:09:43'
		),
		array(
			'id' => 4,
			'block_id' => 4,
			'name' => 'Lorem ipsum dolor sit amet',
			'key' => 'content_block_2',
			'created_user' => 2,
			'created' => '2016-03-17 07:09:43',
			'modified_user' => 2,
			'modified' => '2016-03-17 07:09:43'
		),
	);

}
