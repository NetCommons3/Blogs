<?php
/**
 * View/Elements/BlogFrameSettings/edit_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/BlogFrameSettings/edit_formテスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\test_app\Plugin\TestBlogs\Controller
 */
class TestViewElementsBlogFrameSettingsEditFormController extends AppController {

/**
 * @var array Helpers
 */
	public $helpers = [
		'NetCommons.DisplayNumber',
	];

/**
 * edit_form
 *
 * @return void
 */
	public function edit_form() {
		$this->autoRender = true;

		$this->request->data = [
			'Frame' => [
				'id' => 1,
				'key' => 'frame_key_1',
			],
			'BlogFrameSetting' => [
				'id' => 6,
				'frame_key' => 'frame_key_1',
				'articles_per_page' => 10,
			],
		];
	}

}
