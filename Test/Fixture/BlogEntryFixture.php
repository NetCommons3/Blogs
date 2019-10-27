<?php
/**
 * BlogEntryFixture
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

/**
 * Summary for BlogEntryFixture
 */
class BlogEntryFixture extends CakeTestFixture {

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
			'is_active' => true, // @see BlogEntryYetPublishTest
			'is_latest' => false,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => '1',
			'title' => 'Title 1',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '1',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '1',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
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
			'category_id' => '2',
			'title' => 'Title 2',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '2',
			'publish_start' => '2015-01-24 07:10:12', // 2015年1月の記事  @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '1',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '2',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
		),
		// * 一般が書いたコンテンツ＆一度も公開していない（承認待ち）
		array(
			'id' => '3',
			'block_id' => '2',
			'key' => 'content_key_2',
			'language_id' => '2',
			'status' => '2',
			'is_active' => false,
			'is_latest' => true, // @see BlogEntryYetPublishTest

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'category_id' => '3',
			'title' => 'Title 3',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '3',
			'publish_start' => '2015-01-17 07:10:12', // 2015年1月記事  @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '4',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '3',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
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
			'category_id' => '4',
			'title' => 'Title 4',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2015-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '4',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '4',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
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
			'category_id' => '5',
			'title' => 'Title 5',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2015-03-17 07:10:12', // 2015年3月記事 @see BlogEntryGetYearMonthCountTest で利用
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '4',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '5',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
		),
		// * 編集者が書いたコンテンツ＆一度公開して、差し戻し
		//   (id=6とid=7で区別できるものをセットする)
		array(
			'id' => '6',
			'block_id' => '2',
			'key' => 'content_key_4',
			'language_id' => '2',
			'category_id' => '1',
			'status' => '1',
			'is_active' => true, // @see BlogEntryYetPublishTest
			'is_latest' => false,

			'blog_key' => 'Lorem ipsum dolor sit amet',
			'title' => 'Title 6',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '3',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '6',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
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
			'category_id' => '7',
			'title' => 'Title 7',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '3',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '7',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
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
			'category_id' => '8',
			'title' => 'Title 8',
			'body1' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'body2' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'public_type' => '1',
			'publish_start' => '2016-03-17 07:10:12',
			'publish_end' => '2016-03-17 07:10:12',
			'created_user' => '2',
			'created' => '2016-03-17 07:10:12',
			'modified_user' => '8',
			'modified' => '2016-03-17 07:10:12',
			'title_icon' => '',
			'calendar_event_key' => null,
		),
	);

/**
 * Initialize the fixture.
 *
 * @return void
 */
	public function init() {
		require_once App::pluginPath('Blogs') . 'Config' . DS . 'Schema' . DS . 'schema.php';
		$this->fields = (new BlogsSchema())->tables[Inflector::tableize($this->name)];
		parent::init();
	}

}
