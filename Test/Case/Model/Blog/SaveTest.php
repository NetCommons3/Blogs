<?php
/**
 * Blog::beforeSave()とafterSave()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsModelTestCase', 'NetCommons.TestSuite');
App::uses('NetCommonsSaveTest', 'NetCommons.TestSuite');
App::uses('BlogFixture', 'Blogs.Test/Fixture');

/**
 * Blog::beforeSave()とafterSave()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\Model\Blog
 */
class BlogSaveTest extends NetCommonsSaveTest {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog',
		'plugin.blogs.blog_entry',
		'plugin.blogs.blog_frame_setting',
		'plugin.blogs.blog_setting',
		'plugin.categories.category',
		'plugin.categories.category_order',
		'plugin.workflow.workflow_comment',
		'plugin.likes.like',
	);

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'blogs';

/**
 * Model name
 *
 * @var string
 */
	protected $_modelName = 'Blog';

/**
 * Method name
 *
 * @var string
 */
	protected $_methodName = 'saveBlog';

/**
 * テストDataの取得
 *
 * @param string $blogKey bbsKey
 * @return array
 */
	private function __getData($blogKey = 'blog_1') {
		$frameId = '6';
		$frameKey = 'frame_3';
		$blockId = '2';
		$blockKey = 'block_1';
		$blogId = '2';
		if ($blogKey === 'blog_1') {
			$blogId = '2';
			$blogSettingId = '1';
		} else {
			$blogId = null;
			$blogSettingId = null;
		}

		$data = array(
			'Frame' => array(
				'id' => $frameId
			),
			'Block' => array(
				'id' => $blockId,
				'key' => $blockKey,
				'language_id' => '2',
				'room_id' => '1',
				'plugin_key' => $this->plugin,
				'public_type' => '1',
			),
			'Blog' => array(
				'id' => $blogId,
				'key' => $blogKey,
				'name' => 'blogName',
				'block_id' => $blockId,
				//'bbs_article_count' => '0',
				//'bbs_article_modified' => null,
			),
			'BlogSetting' => array(
				'id' => $blogSettingId,
				'blog_key' => $blogKey,
				'use_comment' => '1',
				'use_like' => '1',
				'use_unlike' => '1',
			),
			'BlogFrameSetting' => array(
				'id' => $blogId,
				'frame_key' => $frameKey,
				'articles_per_page' => 10,
				//'comments_per_page' => 10,
			),
		);

		return $data;
	}

/**
 * SaveのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *
 * @return array
 */
	public function dataProviderSave() {
		return array(
			array($this->__getData()), //修正
			array($this->__getData(null)), //新規
		);
	}

/**
 * SaveのExceptionErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *  - mockMethod Mockのメソッド
 *
 * @return array
 */
	public function dataProviderSaveOnExceptionError() {
		return array(
			array($this->__getData(), 'Blogs.Blog', 'save'),
			array($this->__getData(null), 'Blogs.BlogSetting', 'save'),
			array($this->__getData(null), 'Blogs.BlogFrameSetting', 'save'),
		);
	}

/**
 * SaveのValidationErrorのDataProvider
 *
 * ### 戻り値
 *  - data 登録データ
 *  - mockModel Mockのモデル
 *
 * @return array
 */
	public function dataProviderSaveOnValidationError() {
		return array(
			array($this->__getData(), 'Blogs.Blog'),
			array($this->__getData(), 'Blogs.BlogSetting'),
			array($this->__getData(null), 'Blogs.BlogFrameSetting'),
		);
	}

}
