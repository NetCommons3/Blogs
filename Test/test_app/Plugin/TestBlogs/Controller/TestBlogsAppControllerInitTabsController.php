<?php
/**
 * BlogsAppController::initTabs()テスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogsAppController::initTabs()テスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\test_app\Plugin\TestBlogs\Controller
 */
class TestBlogsAppControllerInitTabsController extends BlogsAppController {

/**
 * initTabs
 *
 * @return void
 */
	public function initTabs() {
		$this->autoRender = true;
	}

}
