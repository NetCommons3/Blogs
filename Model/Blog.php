<?php
/**
 * Blog Model
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
 * Blog Model
 *
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @package NetCommons\Blogs\Model
 */
class Blog extends BlogsAppModel {

/**
 * use tables
 *
 * @var string
 */
	public $useTable = 'blogs';

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
		'Blocks.Block' => array(
			'name' => 'Blog.name',
			'loadModels' => array(
				'Like' => 'Likes.Like',
				'BlockSetting' => 'Blocks.BlockSetting',
				'Category' => 'Categories.Category',
				'CategoryOrder' => 'Categories.CategoryOrder',
				'WorkflowComment' => 'Workflow.WorkflowComment',
			)
		),
		'Categories.Category',
		'NetCommons.OriginalKey',
		//多言語
		'M17n.M17n' => array(
			'keyField' => 'block_id'
		),
	);

/**
 * belongsTo associations
 *
 * @var array
 */
	public $belongsTo = array(
		'Block' => array(
			'className' => 'Blocks.Block',
			'foreignKey' => 'block_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
	);

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
		$this->validate = array_merge($this->validate, array(
			//'block_id' => array(
			//	'numeric' => array(
			//		'rule' => array('numeric'),
			//		'message' => __d('net_commons', 'Invalid request.'),
			//		//'allowEmpty' => false,
			//		//'required' => true,
			//	)
			//),
			'key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),

			//status to set in PublishableBehavior.

			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog name')),
					'required' => true
				),
			),
		));

		//if (! parent::beforeValidate($options)) {
		//	return false;
		//}

		if (isset($this->data['BlogSetting'])) {
			$this->BlogSetting->set($this->data['BlogSetting']);
			if (! $this->BlogSetting->validates()) {
				$this->validationErrors = array_merge($this->validationErrors,
					$this->BlogSetting->validationErrors);
				return false;
			}
		}

		if (isset($this->data['BlogFrameSetting']) && ! $this->data['BlogFrameSetting']['id']) {
			$this->BlogFrameSetting->set($this->data['BlogFrameSetting']);
			if (! $this->BlogFrameSetting->validates()) {
				$this->validationErrors = array_merge($this->validationErrors,
					$this->BlogFrameSetting->validationErrors);
				return false;
			}
		}

		return parent::beforeValidate($options);
	}

/**
 * Called after each successful save operation.
 *
 * @param bool $created True if this save created a new record
 * @param array $options Options passed from Model::save().
 * @return void
 * @throws InternalErrorException
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#aftersave
 * @see Model::save()
 */
	public function afterSave($created, $options = array()) {
		//BlogSetting登録
		if (isset($this->BlogSetting->data['BlogSetting'])) {
			$this->BlogSetting->set($this->BlogSetting->data['BlogSetting']);
			$this->BlogSetting->save(null, false);
		}

		//BlogFrameSetting登録
		if (isset($this->BlogFrameSetting->data['BlogFrameSetting'])
			&& ! $this->BlogFrameSetting->data['BlogFrameSetting']['id']) {
			if (! $this->BlogFrameSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}
		}

		parent::afterSave($created, $options);
	}

/**
 * Create blog data
 *
 * @return array
 */
	public function createBlog() {
		$this->loadModels(['BlogSetting' => 'Blogs.BlogSetting']);

		$blog = $this->createAll(array(
			'Blog' => array(
				'name' => __d('blogs', 'New blog %s', date('YmdHis')),
			),
			'Block' => array(
				'room_id' => Current::read('Room.id'),
				'language_id' => Current::read('Language.id'),
			),
		));

		return ($blog + $this->BlogSetting->createBlockSetting());
	}

/**
 * Get blog data
 *
 * @return array
 */
	public function getBlog() {
		$this->loadModels(['BlogSetting' => 'Blogs.BlogSetting']);

		$blog = $this->find('first', array(
			'recursive' => 0,
			'conditions' => $this->getBlockConditionById(),
		));
		if (! $blog) {
			return $blog;
		}
		$blogSetting = $this->BlogSetting->getBlogSetting();
		if ($blogSetting) {
			$blog = $blog + $blogSetting;
		}
		return $blog;
	}

/**
 * Save blogs
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveBlog($data) {
		$this->loadModels([
			'BlogSetting' => 'Blogs.BlogSetting',
			'BlogFrameSetting' => 'Blogs.BlogFrameSetting',
		]);

		//トランザクションBegin
		$this->begin();

		//バリデーション
		$this->set($data);
		if (! $this->validates()) {
			return false;
		}

		try {
			//登録処理
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

/**
 * Delete blogs
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteBlog($data) {
		$this->loadModels([
			'Blog' => 'Blogs.Blog',
			'BlogEntry' => 'Blogs.BlogEntry',
		]);

		//トランザクションBegin
		$this->begin();

		try {
			$conditions = array($this->alias . '.key' => $data['Blog']['key']);
			if (! $this->deleteAll($conditions, false, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->BlogEntry->blockKey = $data['Block']['key'];
			$blogEntryConditions = array(
				$this->BlogEntry->alias . '.blog_key' => $data['Blog']['key']
			);
			if (! $this->BlogEntry->deleteAll($blogEntryConditions, false, true)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Blockデータ削除
			$this->deleteBlock($data['Block']['key']);

			//トランザクションCommit
			$this->commit();

		} catch (Exception $ex) {
			//トランザクションRollback
			$this->rollback($ex);
		}

		return true;
	}

}
