<?php
/**
 * add record
 */

App::uses('NetCommonsMigration', 'NetCommons.Config/Migration');

/**
 * Class AddRecord
 */
class AddRecord extends NetCommonsMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'add_record';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
		),
		'down' => array(
		),
	);

/**
 * recodes
 *
 * @var array $migration
 */
	public $records = array(
		'Plugin' => array(
			//日本語
			array(
				'language_id' => '2',
				'key' => 'blogs',
				'namespace' => 'netcommons/blogs',
				'name' => 'BLOG',
				'type' => 1,
				'default_action' => 'blog_entries/index',
				'default_setting_action' => 'blog_blocks/index',
			),
			//英語
			array(
				'language_id' => '1',
				'key' => 'blogs',
				'namespace' => 'netcommons/blogs',
				'name' => 'BLOG',
				'type' => 1,
				'default_action' => 'blog_entries/index',
				'default_setting_action' => 'blog_blocks/index',
			),
		),
		'PluginsRole' => array(
			array(
				'role_key' => 'room_administrator',
				'plugin_key' => 'blogs'
			),
		),
		'PluginsRoom' => array(
			//パブリックスペース
			array('room_id' => '1', 'plugin_key' => 'blogs', ),
			//プライベートスペース
			array('room_id' => '2', 'plugin_key' => 'blogs', ),
			//グループスペース
			array('room_id' => '3', 'plugin_key' => 'blogs', ),
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
		if ($direction === 'down') {
			return true;
		}
		foreach ($this->records as $model => $records) {
			if (!$this->updateRecords($model, $records)) {
				return false;
			}
		}
		return true;
	}
}
