<?php
class PublishPeriod extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'publish_period';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'blog_entries' => array(
					'public_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'unsigned' => false, 'after' => 'body2'),
					'publish_start' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'public_type'),
					'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => null, 'after' => 'publish_start'),
				),
			),
			'drop_field' => array(
				'blog_entries' => array('published_datetime'),
			),
		),
		'down' => array(
			'drop_field' => array(
				'blog_entries' => array('public_type', 'publish_start', 'publish_end'),
			),
			'create_field' => array(
				'blog_entries' => array(
					'published_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
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
