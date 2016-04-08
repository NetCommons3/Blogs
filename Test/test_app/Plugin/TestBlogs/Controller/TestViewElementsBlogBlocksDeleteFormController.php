<?php
/**
 * View/Elements/BlogBlocks/delete_formテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/BlogBlocks/delete_formテスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\test_app\Plugin\TestBlogs\Controller
 */
class TestViewElementsBlogBlocksDeleteFormController extends AppController {

/**
 * delete_form
 *
 * @return void
 */
	public function delete_form() {
		$block = [
			'id' => 10,
			'key' => 'block_key_10'
		];
		$this->set('block', $block);
		$blog = [
			'key' => 'blog_key_10'
		];
		$this->set('blog', $blog);
		$this->autoRender = true;
	}

}
