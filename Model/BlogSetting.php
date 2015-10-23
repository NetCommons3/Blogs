<?php
/**
 * BlogSetting Model
 *
 * @property Block $Block
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */

App::uses('BlogsAppModel', 'Blogs.Model');

/**
 * BlogSetting Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blogs\Model
 */
class BlogSetting extends BlogsAppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array();

/**
 * use behaviors
 *
 * @var array
 */
	public $actsAs = array(
		'Blocks.BlockRolePermission',
	);

/**
 * Get blog setting data
 *
 * @param string $blogKey blogs.key
 * @return array
 */
	public function getBlogSetting($blogKey) {
		$conditions = array(
			'blog_key' => $blogKey
		);

		$blogSetting = $this->find('first', array(
				'recursive' => -1,
				'conditions' => $conditions,
			)
		);

		return $blogSetting;
	}

/**
 * Save blog_setting
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveBlogSetting($data) {
		$this->loadModels([
			'BlogSetting' => 'Blogs.BlogSetting',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			$this->rollback();
			return false;
		}

		try {
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
