<?php
/**
 * Blog Model
 *
 * @property Block $Block
 * @property BlogQuestionAnswer $BlogQuestionAnswer
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
				'Comment' => 'Comments.Comment',
			)
		),
		'NetCommons.OriginalKey'
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

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
		)
	);

	///**
	// * hasMany associations
	// *
	// * @var array
	// */
	//	public $hasMany = array(
	//		'BlogQuestion' => array(
	//			'className' => 'BlogQuestion',
	//			'foreignKey' => 'blog_id',
	//			'dependent' => false,
	//			'conditions' => '',
	//			'fields' => '',
	//			'order' => '',
	//			'limit' => '',
	//			'offset' => '',
	//			'exclusive' => '',
	//			'finderQuery' => '',
	//			'counterQuery' => ''
	//		)
	//	);

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
			'key' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'required' => true,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'block_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => __d('net_commons', 'Invalid request.'),
					'allowEmpty' => false,
					'on' => 'update', // Limit validation to 'create' or 'update' operations
				),
			),
			'name' => array(
				'notBlank' => array(
					'rule' => array('notBlank'),
					'message' => sprintf(__d('net_commons', 'Please input %s.'), __d('blogs', 'Blog Name')),
					'allowEmpty' => false,
					'required' => true,
				),
			),
			'is_auto_translated' => array(
				'boolean' => array(
					'rule' => array('boolean'),
					'message' => __d('net_commons', 'Invalid request.'),
				),
			),
		));

		return parent::beforeValidate($options);
	}

/**
 * Get blog data
 *
 * @param int $blockId blocks.id
 * @param int $roomId rooms.id
 * @return array
 */
	public function getBlog($blockId, $roomId) {
		$conditions = array(
			'Block.id' => $blockId,
			'Block.room_id' => $roomId,
		);

		$blog = $this->find('first', array(
				'recursive' => 0,
				'conditions' => $conditions,
			)
		);

		return $blog;
	}

/**
 * Save blog
 *
 * @param array $data received post data
 * @return bool True on success, false on validation errors
 * @throws InternalErrorException
 */
	public function saveBlog($data) {
		$this->loadModels([
			'Blog' => 'Blogs.Blog',
			'BlogSetting' => 'Blogs.BlogSetting',
			'Category' => 'Categories.Category',
			'Block' => 'Blocks.Block',
			'Frame' => 'Frames.Frame',
		]);

		//トランザクションBegin
		$this->setDataSource('master');
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		try {
			//バリデーション
			if (! $this->validateBlog($data, ['blogSetting', 'block', 'category'])) {
				$dataSource->rollback();
				return false;
			}

			//ブロックの登録
			$block = $this->Block->saveByFrameId($data['Frame']['id']);

			//登録処理
			$this->data['Blog']['block_id'] = (int)$block['Block']['id'];
			if (! $blog = $this->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$this->BlogSetting->data['BlogSetting']['blog_key'] = $blog['Blog']['key'];
			if (! $this->BlogSetting->save(null, false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			$data['Block']['id'] = (int)$block['Block']['id'];
			$data['Block']['key'] = $block['Block']['key'];
			$this->Category->saveCategories($data);

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
 * validate blog
 *
 * @param array $data received post data
 * @param array $contains Optional validate sets
 * @return bool True on success, false on validation errors
 */
	public function validateBlog($data, $contains = []) {
		$this->set($data);
		$this->validates();
		if ($this->validationErrors) {
			return false;
		}

		if (in_array('blogSetting', $contains, true)) {
			if (! $this->BlogSetting->validateBlogSetting($data)) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->BlogSetting->validationErrors);
				return false;
			}
		}

		if (in_array('block', $contains, true)) {
			if (! $this->Block->validateBlock($data)) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Block->validationErrors);
				return false;
			}
		}

		if (in_array('category', $contains, true)) {
			if (! isset($data['Categories'])) {
				$data['Categories'] = [];
			}
			if (! $data = $this->Category->validateCategories($data)) {
				$this->validationErrors = Hash::merge($this->validationErrors, $this->Category->validationErrors);
				return false;
			}
		}
		return true;
	}

/**
 * Delete blog
 *
 * @param array $data received post data
 * @return mixed On success Model::$data if its not empty or true, false on failure
 * @throws InternalErrorException
 */
	public function deleteBlog($data) {
		$this->setDataSource('master');

		$this->loadModels([
			'Blog' => 'Blogs.Blog',
			'BlogSetting' => 'Blogs.BlogSetting',
			'BlogEntry' => 'Blogs.BlogEntry',
			'Block' => 'Blocks.Block',
			'Category' => 'Categories.Category',
		]);

		//トランザクションBegin
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		$conditions = array(
			$this->alias . '.key' => $data['Blog']['key']
		);
		$blogs = $this->find('list', array(
				'recursive' => -1,
				'conditions' => $conditions,
			)
		);
		$blogs = array_keys($blogs);

		try {
			if (! $this->deleteAll(array($this->alias . '.key' => $data['Blog']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (! $this->BlogSetting->deleteAll(array($this->BlogSetting->alias . '.blog_key' => $data['Blog']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			if (! $this->BlogEntry->deleteAll(array($this->BlogEntry->alias . '.blog_key' => $data['Blog']['key']), false)) {
				throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
			}

			//Categoryデータ削除
			$this->Category->deleteByBlockKey($data['Block']['key']);

			//Blockデータ削除
			$this->Block->deleteBlock($data['Block']['key']);

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

}
