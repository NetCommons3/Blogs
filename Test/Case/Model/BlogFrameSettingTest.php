<?php
/**
 * BlogFrameSetting Test Case
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogFrameSetting', 'Blogs.Model');

/**
 * Summary for BlogFrameSetting Test Case
 *
 * @property BlogFrameSetting $BlogFrameSetting
 */
class BlogFrameSettingTest extends CakeTestCase {

/**
 * Fixtures
 *
 * @var array
 */
	public $fixtures = array(
		'plugin.blogs.blog_frame_setting',
		'plugin.users.user', // Trackableビヘイビアでテーブルが必用
	);

/**
 * setUp method
 *
 * @return void
 */
	public function setUp() {
		parent::setUp();
		$this->BlogFrameSetting = ClassRegistry::init('Blogs.BlogFrameSetting');
		// モデルからビヘイビアをはずす:
		$this->BlogFrameSetting->Behaviors->unload('Trackable');
	}

/**
 * tearDown method
 *
 * @return void
 */
	public function tearDown() {
		unset($this->BlogFrameSetting);

		parent::tearDown();
	}

/**
 * test getSettingByFrameKey
 *
 * @return void
 */
	public function testGetSettingByFrameKey() {
		$frameSetting = $this->BlogFrameSetting->getSettingByFrameKey('frame_1');
		$this->assertEquals('frame_1', $frameSetting['frame_key']);
	}

/**
 * test getSettingByFrameKey データがなければ作成される
 *
 * @return void
 */
	public function testGetSettingByFrameKeyNotFound() {
		$frameSetting = $this->BlogFrameSetting->getSettingByFrameKey('frame_key_not_found');
		$this->assertEquals('frame_key_not_found', $frameSetting['frame_key']);
		$this->assertTrue($frameSetting['id'] > 0);
	}

/**
 * test saveBlogFrameSetting
 *
 * @return void
 */
	public function testSaveBlogFrameSetting() {
		$data = $this->BlogFrameSetting->getNew();
		// バリデート失敗
		$resultFalse = $this->BlogFrameSetting->saveBlogFrameSetting($data);
		$this->assertFalse($resultFalse);

		// 保存成功
		$data['BlogFrameSetting']['frame_key'] = 'frame_key';
		$savedData = $this->BlogFrameSetting->saveBlogFrameSetting($data);
		$this->assertTrue(($savedData['BlogFrameSetting']['id'] > 0));
	}

/**
 * test saveBlogFrameSetting save失敗で例外投げられるテスト
 *
 * @return void
 */
	public function testSaveBlogFrameSettingSaveFailed() {
		$data = $this->BlogFrameSetting->getNew();
		$BlogFrameSettingMock = $this->getMockForModel('Blogs.BlogFrameSetting', ['save']);
		$BlogFrameSettingMock->expects($this->once())
			->method('save')
			->will($this->returnValue(false));
		// save fail
		$data['BlogFrameSetting']['frame_key'] = 'frame_key';
		$this->setExpectedException('InternalErrorException');
		$BlogFrameSettingMock->saveBlogFrameSetting($data);
	}

/**
 * test getDisplayNumberOptions
 *
 * @return void
 */
	public function testGetDisplayNumberOptions() {
		$array = $this->BlogFrameSetting->getDisplayNumberOptions();
		$this->assertInternalType('array', $array);
	}
}
