<?php
/**
 * BlogEntryPermissionComponentTest::testCanDelete()のテスト
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('NetCommonsCakeTestCase', 'NetCommons.TestSuite');

/**
 * BlogEntryPermissionComponentTest::testCanDelete()のテスト
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\Case\_Test\Case\Controller\Component\BlogEntryPermissionComponentTest
 */
class BlogsTestCaseControllerComponentBlogEntryPermissionComponentTestTestCanDeleteTest extends NetCommonsCakeTestCase {

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'blogs';

/**
 * testCanDelete()のテスト
 *
 * @return void
 */
	public function testTestCanDelete() {
		//データ生成
		$canEdit = null;
		$contentPublishable = null;
		$yetPublish = null;
		$accessUserId = null;
		$expected = null;

		//テスト実施
		//$result = $this->testCanDelete($canEdit, $contentPublishable, $yetPublish, $accessUserId, $expected);

		//チェック
		//TODO:assertを書く
		//debug($result);
	}

}
