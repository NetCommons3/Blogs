<?php
/**
 * BlogSetting Model
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
 * Get blog setting data
 *
 * @param string $blogKey blog.key
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
 * Save blog settings
 *
 * @param array $data received post data
 * @return bool True on success, false on failure
 * @throws InternalErrorException
 */
	public function saveBlogSetting($data) {
		$this->loadModels([
			'BlogSetting' => 'Blogs.BlogSetting',
			'BlockRolePermission' => 'Blocks.BlockRolePermission',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			if (! $this->validateBlogSetting($data)) {
				$dataSource->rollback();
				return false;
			}
			foreach ($data[$this->BlockRolePermission->alias] as $value) {
				if (! $this->BlockRolePermission->validateBlockRolePermissions($value)) {
					$this->validationErrors = Hash::merge($this->validationErrors, $this->BlockRolePermission->validationErrors);
					$dataSource->rollback();
					return false;
				}
			}

			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
			foreach ($data[$this->BlockRolePermission->alias] as $value) {
				if (! $this->BlockRolePermission->saveMany($value, ['validate' => false])) {
					throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
				}
			}

			//トランザクションCommit
			$dataSource->commit();
		} catch (Exception $ex) {
			//トランザクションRollback
			$dataSource->rollback();
			CakeLog::error($ex);
			throw $ex;
		}

		return true;
	}

/**
 * validate blogSettings
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 */
	public function validateBlogSetting($data) {
		$this->set($data);
		$this->validates();
		return ($this->validationErrors) ? false : true;
	}

}
