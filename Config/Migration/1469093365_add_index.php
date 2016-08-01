<?php
/**
 * AddIndex
 */

/**
 * Class AddIndex
 */
class AddIndex extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_index';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'blog_entries' => array(
					'indexes' => array(
						'block_id' => array('column' => array('block_id', 'language_id'), 'unique' => 0),
					),
				),
				'blog_frame_settings' => array(
					'indexes' => array(
						'frame_key' => array('column' => 'frame_key', 'unique' => 0),
					),
				),
				'blog_settings' => array(
					'indexes' => array(
						'blog_key' => array('column' => 'blog_key', 'unique' => 0),
					),
				),
				'blogs' => array(
					'indexes' => array(
						'block_id' => array('column' => 'block_id', 'unique' => 0),
					),
				),
			),
			'alter_field' => array(
				'blog_entries' => array(
					'block_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
				'blog_frame_settings' => array(
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'frame key | フレームKey | frames.key | ', 'charset' => 'utf8'),
				),
				'blog_settings' => array(
					'blog_key' => array('type' => 'string', 'null' => false, 'default' => null, 'key' => 'index', 'collate' => 'utf8_general_ci', 'comment' => 'Blog key | BLOGキー | Hash値 | ', 'charset' => 'utf8'),
				),
				'blogs' => array(
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'index'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'blog_entries' => array('indexes' => array('block_id')),
				'blog_frame_settings' => array('indexes' => array('frame_key')),
				'blog_settings' => array('indexes' => array('blog_key')),
				'blogs' => array('indexes' => array('block_id')),
			),
			'alter_field' => array(
				'blog_entries' => array(
					'block_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
				),
				'blog_frame_settings' => array(
					'frame_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'frame key | フレームKey | frames.key | ', 'charset' => 'utf8'),
				),
				'blog_settings' => array(
					'blog_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'Blog key | BLOGキー | Hash値 | ', 'charset' => 'utf8'),
				),
				'blogs' => array(
					'block_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
				),
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction Direction of migration process (up or down)
 * @return bool Should process continue
 */
	public function after($direction) {
		return true;
	}
}
