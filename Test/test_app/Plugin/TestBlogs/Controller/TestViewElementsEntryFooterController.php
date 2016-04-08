<?php
/**
 * View/Elements/entry_footerテスト用Controller
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('AppController', 'Controller');

/**
 * View/Elements/entry_footerテスト用Controller
 *
 * @author Ryuji AMANO <ryuji@ryus.co.jp>
 * @package NetCommons\Blogs\Test\test_app\Plugin\TestBlogs\Controller
 */
class TestViewElementsEntryFooterController extends AppController {

/**
 * @var array Model
 */
	public $uses = [
		'Likes.Like',
	];

/**
 * @var array Helpers
 */
	public $helpers = [
		'NetCommons.SnsButton',
		'Likes.Like',
		'ContentComments.ContentComment' => array(
			'viewVarsKey' => array(
				'contentKey' => 'blogEntry.BlogEntry.key',
				'contentTitleForMail' => 'blogEntry.BlogEntry.title',
				'useComment' => 'blogSetting.use_comment',
				'useCommentApproval' => 'blogSetting.use_comment_approval'
			)
		),
	];

/**
 * entry_footer
 *
 * @return void
 */
	public function entry_footer() {
		$this->autoRender = true;

		$blogSetting = [
			'use_sns' => true,
			'use_like' => true,

		];
		$blogEntry = [
			'BlogEntry' => [
				'key' => 'content_key_1',
				'status' => WorkflowComponent::STATUS_PUBLISHED,
			]
		];
		$this->set('blogSetting', $blogSetting);
		$this->set('blogEntry', $blogEntry);
		$this->set('index', true);
	}

/**
 * snsボタン使わない設定のテスト用アクション
 *
 * @return void
 */
	public function not_use_sns() {
		$this->autoRender = true;

		$blogSetting = [
			'use_sns' => false,
			'use_like' => true,

		];
		$blogEntry = [
			'BlogEntry' => [
				'key' => 'content_key_1',
				'status' => WorkflowComponent::STATUS_PUBLISHED,
			]
		];
		$this->set('blogSetting', $blogSetting);
		$this->set('blogEntry', $blogEntry);
		$this->set('index', true);
		$this->render('entry_footer');
	}

/**
 * index以外での表示
 *
 * @return void
 */
	public function not_index() {
		$this->autoRender = true;

		$blogSetting = [
			'use_sns' => false,
			'use_like' => true,

		];
		$blogEntry = [
			'BlogEntry' => [
				'key' => 'content_key_1',
				'status' => WorkflowComponent::STATUS_PUBLISHED,
			]
		];
		$this->set('blogSetting', $blogSetting);
		$this->set('blogEntry', $blogEntry);
		$this->set('index', false);
		$this->render('entry_footer');
	}

}
