<?php
/**
 * CategoryIdAllowNull
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Class CategoryIdAllowNull
 */
class CategoryIdAllowNull extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 */
	public $description = 'category_id_allow_null';

/**
 * Actions to be performed
 *
 * @var array $migration
 */
	public $migration = array(
		'up' => array(
			'alter_field' => array(
				'blog_entries' => array(
					'category_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false, 'comment' => 'category id | カテゴリーID | blog_categories.id | '),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'blog_entries' => array(
					'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'category id | カテゴリーID | blog_categories.id | '),
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
