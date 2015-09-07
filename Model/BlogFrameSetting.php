<?php
/**
 * BlogFrameSetting Model
 *
 *
 * @author   Jun Nishikawa <topaz2@m0n0m0n0.com>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 */

App::uses('BlogsAppModel', 'Blogs.Model');

/**
 * Summary for BlogFrameSetting Model
 */
class BlogFrameSetting extends BlogsAppModel {

/**
 * フレームキーからフレーム設定を返す
 *
 * @param string $frameKey フレームキー
 * @return mixed
 */
	public function getSettingByFrameKey($frameKey) {
		$setting = $this->findByFrameKey($frameKey);
		if ($setting) {
			return $setting['BlogFrameSetting'];
		} else {
			// 設定データがまだないときはつくる
			$this->create();
			$data = array(
				'BlogFrameSetting' => array(
					'frame_key' => $frameKey
				)
			);
			$this->save($data);
			$setting = $this->findByFrameKey($frameKey);
			return $setting['BlogFrameSetting'];
		}
	}

/**
 * Called during validation operations, before validation. Please note that custom
 * validation rules can be defined in $validate.
 *
 * @param array $options Options passed from Model::save().
 * @return bool True if validate operation should continue, false to abort
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#beforevalidate
 * @see Model::save()
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge($this->validate, array(
			'frame_key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				)
			),
			'posts_per_page' => array(
				'number' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'required' => true,
				)
			),
			//'comments_per_page' => array(
			//	'number' => array(
			//		'rule' => array('notBlank'),
			//		'message' => __d('net_commons', 'Invalid request.'),
			//		'required' => true,
			//	)
			//),
		));
		return parent::beforeValidate($options);
	}

/**
 * save blog
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function saveBlogFrameSetting($data) {
		$this->loadModels([
			'BlogFrameSetting' => 'Bloges.BlogFrameSetting',
		]);

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//バリデーション
			if (!$this->validateBlogFrameSetting($data)) {
				$dataSource->rollback();
				return false;
			}

			//登録処理
			if (! $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
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
 * validate blog_frame_setting
 *
 * @param array $data received post data
 * @return bool True on success, false on error
 */
	public function validateBlogFrameSetting($data) {
		$this->set($data);
		$this->validates();
		return $this->validationErrors ? false : true;
	}

/**
 * getDisplayNumberOptions
 *
 * @return array
 */
	public static function getDisplayNumberOptions() {
		return array(
			1 => __d('blogs', '%s article', 1),
			5 => __d('blogs', '%s articles', 5),
			10 => __d('blogs', '%s articles', 10),
			20 => __d('blogs', '%s articles', 20),
			50 => __d('blogs', '%s articles', 50),
			100 => __d('blogs', '%s articles', 100),
		);
	}
}
