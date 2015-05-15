<?php
/**
 * BlogEntryFixture
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * Summary for BlogEntryFixture
 */
class BlogEntryFixture extends CakeTestFixture {

/**
 * Fields
 *
 * @var array
 */
	public $fields = array(
		'id' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'key' => 'primary',
			'comment' => 'ID |  |  | '
		),
		'category_id' => array(
			'type' => 'integer',
			'null' => true,
			'default' => null,
			'comment' => 'category id | カテゴリーID | categories.id | '
		),
		'key' => array(
			'type' => 'string',
			'null' => false,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'entry key | エントリーキー | Hash値 | ',
			'charset' => 'utf8'
		),
		'origin_id' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'comment' => 'origin_id '
		),
		'status' => array(
			'type' => 'integer',
			'null' => false,
			'default' => null,
			'length' => 4,
			'comment' => 'public status, 1: public, 2: public pending, 3: draft during 4: remand | 公開状況  1:公開中、2:公開申請中、3:下書き中、4:差し戻し |  | '
		),
		'title' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'title | タイトル |  | ',
			'charset' => 'utf8'
		),
		'body1' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'entry body1 | 本文1 |  | ',
			'charset' => 'utf8'
		),
		'body2' => array(
			'type' => 'text',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'entry body2 | 本文2 |  | ',
			'charset' => 'utf8'
		),
		'plus_vote_number_copy1' => array(
			'type' => 'integer',
			'null' => false,
			'default' => '0',
			'comment' => 'plus vote number | プラス投票数 |  | '
		),
		'minus_vote_number' => array(
			'type' => 'integer',
			'null' => false,
			'default' => '0',
			'comment' => 'minus vote number | マイナス投票数 |  | '
		),
		'published_datetime' => array('type' => 'datetime', 'null' => false, 'default' => null),
		'is_auto_translated' => array(
			'type' => 'boolean',
			'null' => false,
			'default' => '0',
			'comment' => 'translation type. 0:original , 1:auto translation | 翻訳タイプ  0:オリジナル、1:自動翻訳 |  | '
		),
		'translation_engine' => array(
			'type' => 'string',
			'null' => true,
			'default' => null,
			'collate' => 'utf8_general_ci',
			'comment' => 'translation engine | 翻訳エンジン |  | ',
			'charset' => 'utf8'
		),
		'created_user' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '0',
			'comment' => 'created user | 作成者 | users.id | '
		),
		'created' => array(
			'type' => 'datetime',
			'null' => true,
			'default' => null,
			'comment' => 'created datetime | 作成日時 |  | '
		),
		'modified_user' => array(
			'type' => 'integer',
			'null' => true,
			'default' => '0',
			'comment' => 'modified user | 更新者 | users.id | '
		),
		'modified' => array(
			'type' => 'datetime',
			'null' => true,
			'default' => null,
			'comment' => 'modified datetime | 更新日時 |  | '
		),
		'indexes' => array(
			'PRIMARY' => array('column' => 'id', 'unique' => 1)
		),
		'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB')
	);

/**
 * Records
 *
 * @return void
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
	public function init() {
		$this->records = array(
			array(
				'id' => 1,
				'category_id' => 2,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 1,
				'status' => NetCommonsBlockComponent::STATUS_PUBLISHED,
				'title' => '公開済み記事',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 1,
				'minus_vote_number' => 1,
				'published_datetime' => '2014-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 1,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 1,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 2,
				'category_id' => 2,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 1,
				'status' => NetCommonsBlockComponent::STATUS_PUBLISHED,
				'title' => '2015年2月23日公開予定の記事',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 2,
				'minus_vote_number' => 2,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 1,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 2,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 3,
				'category_id' => 2,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 3,
				'status' => NetCommonsBlockComponent::STATUS_IN_DRAFT,
				'title' => '下書き記事',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 3,
				'minus_vote_number' => 3,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 1,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 3,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 4,
				'category_id' => 2,
				'key' => 'Lorem ipsum dolor sit amet',
				'status' => NetCommonsBlockComponent::STATUS_IN_DRAFT,
				'origin_id' => 4,
				'title' => '別の人の書いた下書き',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 4,
				'minus_vote_number' => 4,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 4, // userが1でない
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 4,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 5,
				'category_id' => 2,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 5,
				'status' => NetCommonsBlockComponent::STATUS_PUBLISHED,
				'title' => '別の人が書いた公開予定記事',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 5,
				'minus_vote_number' => 5,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 5,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 5,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 6,
				'category_id' => 6,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 6,
				'status' => 6,
				'title' => 'Lorem ipsum dolor sit amet',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 6,
				'minus_vote_number' => 6,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 6,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 6,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 7,
				'category_id' => 7,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 7,
				'status' => 7,
				'title' => 'Lorem ipsum dolor sit amet',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 7,
				'minus_vote_number' => 7,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 7,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 7,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 8,
				'category_id' => 8,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 8,
				'status' => 8,
				'title' => 'Lorem ipsum dolor sit amet',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 8,
				'minus_vote_number' => 8,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 8,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 8,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 9,
				'category_id' => 9,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 9,
				'status' => 9,
				'title' => 'Lorem ipsum dolor sit amet',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 9,
				'minus_vote_number' => 9,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 9,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 9,
				'modified' => '2015-02-23 05:58:13'
			),
			array(
				'id' => 10,
				'category_id' => 10,
				'key' => 'Lorem ipsum dolor sit amet',
				'origin_id' => 10,
				'status' => 10,
				'title' => 'Lorem ipsum dolor sit amet',
				'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
				'plus_vote_number_copy1' => 10,
				'minus_vote_number' => 10,
				'published_datetime' => '2015-02-23 05:58:13',
				'is_auto_translated' => 1,
				'translation_engine' => 'Lorem ipsum dolor sit amet',
				'created_user' => 10,
				'created' => '2015-02-23 05:58:13',
				'modified_user' => 10,
				'modified' => '2015-02-23 05:58:13'
			),
		);
		parent::init();
	}
}
