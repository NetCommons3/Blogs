<?php
/**
 * BlogEntryFixture
 *
* @author Noriko Arai <arai@nii.ac.jp>
* @author Your Name <yourname@domain.com>
* @link http://www.netcommons.org NetCommons Project
* @license http://www.netcommons.org/license.txt NetCommons License
* @copyright Copyright 2014, NetCommons Project
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
		'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'key' => 'primary', 'comment' => 'ID |  |  | '),
		'blog_key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
		'category_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false, 'comment' => 'category id | カテゴリーID | blog_categories.id | '),
		'status' => array('type' => 'integer', 'null' => false, 'default' => null, 'length' => 4, 'unsigned' => false, 'comment' => 'public status, 1: public, 2: public pending, 3: draft during 4: remand | 公開状況  1:公開中、2:公開申請中、3:下書き中、4:差し戻し |  | '),
		'is_active' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
		'is_latest' => array('type' => 'boolean', 'null' => true, 'default' => null),
		'language_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'title' => array('type' => 'string', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'title | タイトル |  | ', 'charset' => 'utf8'),
		'body1' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'entry body1 | 本文1 |  | ', 'charset' => 'utf8'),
		'body2' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => 'entry body2 | 本文2 |  | ', 'charset' => 'utf8'),
		'public_type' => array('type' => 'integer', 'null' => false, 'default' => '1', 'length' => 4, 'unsigned' => false),
		'publish_start' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'publish_end' => array('type' => 'datetime', 'null' => true, 'default' => null),
		'plus_vote_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => 'plus vote number | プラス投票数 |  | '),
		'minus_vote_number' => array('type' => 'integer', 'null' => false, 'default' => '0', 'unsigned' => false, 'comment' => 'minus vote number | マイナス投票数 |  | '),
		'created_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => 'created user | 作成者 | users.id | '),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'created datetime | 作成日時 |  | '),
		'modified_user' => array('type' => 'integer', 'null' => true, 'default' => '0', 'unsigned' => false, 'comment' => 'modified user | 更新者 | users.id | '),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => null, 'comment' => 'modified datetime | 更新日時 |  | '),
		'block_id' => array('type' => 'integer', 'null' => true, 'default' => null, 'unsigned' => false),
		'key' => array('type' => 'string', 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
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
		// * ルーム管理者が書いたコンテンツ＆一度公開して、下書き中
		//   (id=1とid=2で区別できるものをセットする)
		array(
			'id' => '1',
			'block_id' => '2',
			'key' => 'content_key_1',
			'language_id' => '2',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 1,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 1,
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 1,
			'minus_vote_number' => 1,
			'created_user' => 1,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 1,
			'modified' => '2016-03-17 07:10:12',
		),
		array(
			'id' => '2',
			'block_id' => '2',
			'key' => 'content_key_1',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 2,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 2,
			'publish_start' => '2015-01-24 07:10:12', // 2015年1月の記事  @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 2,
			'minus_vote_number' => 2,
			'created_user' => 1,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 2,
			'modified' => '2016-03-17 07:10:12',
		),
		// * 一般が書いたコンテンツ＆一度も公開していない（承認待ち）
		array(
			'id' => '3',
			'block_id' => '2',
			'key' => 'content_key_2',
			'language_id' => '2',
			'status' => '2',
			'is_active' => false,
			'is_latest' => true,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 3,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 3,
			'publish_start' => '2015-01-17 07:10:12', // 2015年1月記事  @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 3,
			'minus_vote_number' => 3,
			'created_user' => 4,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 3,
			'modified' => '2016-03-17 07:10:12',
		),
		// * 一般が書いたコンテンツ＆公開して、一時保存
		//   (id=4とid=5で区別できるものをセットする)
		array(
			'id' => '4',
			'block_id' => '2',
			'key' => 'content_key_3',
			'language_id' => '2',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 4,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 4,
			'publish_start' => '2015-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 4,
			'minus_vote_number' => 4,
			'created_user' => 4,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 4,
			'modified' => '2016-03-17 07:10:12',
		),
		array(
			'id' => '5',
			'block_id' => '2',
			'key' => 'content_key_3',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 5,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 5,
			'publish_start' => '2015-03-17 07:10:12', // 2015年3月記事 @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 5,
			'minus_vote_number' => 5,
			'created_user' => 4,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 5,
			'modified' => '2016-03-17 07:10:12',
		),
		// * 編集者が書いたコンテンツ＆一度公開して、差し戻し
		//   (id=6とid=7で区別できるものをセットする)
		array(
			'id' => 6,
			'block_id' => '2',
			'key' => 'content_key_4',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '1',
			'is_active' => true,
			'is_latest' => false,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 6,
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 6,
			'minus_vote_number' => 6,
			'created_user' => 3,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 6,
			'modified' => '2016-03-17 07:10:12',
		),
		array(
			'id' => '7',
			'block_id' => '2',
			'key' => 'content_key_4',
			'language_id' => '2',
			'status' => '4',
			'is_active' => false,
			'is_latest' => true,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 7,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 7,
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 7,
			'minus_vote_number' => 7,
			'created_user' => 3,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 7,
			'modified' => '2016-03-17 07:10:12',
		),
		array(
			'id' => '8',
			'block_id' => '2',
			'key' => 'content_key_5',
			'language_id' => '2',
			'status' => '3',
			'is_active' => false,
			'is_latest' => true,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => 8,
			'title' => 'Lorem ipsum dolor sit amet',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => 8,
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'plus_vote_number' => 8,
			'minus_vote_number' => 8,
			'created_user' => 2,
			'created' => '2016-03-17 07:10:12',
			'modified_user' => 8,
			'modified' => '2016-03-17 07:10:12',
		),
	);

}
