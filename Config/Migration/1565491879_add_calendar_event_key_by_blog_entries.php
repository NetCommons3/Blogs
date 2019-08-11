<?php
class AddCalendarEventKeyByBlogEntries extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_calendar_event_key_by_blog_entries';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'create_field' => array(
				'blog_entries' => array(
					'calendar_event_key' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'body2'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'blog_entries' => array('calendar_event_key'),
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
