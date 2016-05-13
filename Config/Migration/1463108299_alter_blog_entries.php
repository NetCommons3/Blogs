<?php
class AlterBlogEntries extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'alter_blog_entries';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'blog_entries' => array(
					'public_type' => array('type' => 'integer', 'null' => false, 'default' => '2', 'length' => 4, 'unsigned' => false),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'blog_entries' => array(
					'public_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'unsigned' => false),
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
