<?php
/**
 * View/Elements/entry_meta_infoテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/entry_meta_infoテスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\test_app\Plugin\TestBlogs\Controller
 */
class TestViewElementsEntryMetaInfoController extends AppController {

/**
 * entry_meta_info
 *
 * @return void
 */
	public function entry_meta_info() {
		$this->autoRender = true;
	}

}
