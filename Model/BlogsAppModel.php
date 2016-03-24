<?php
/**
 * BlogAppModel
 */
App::uses('AppModel', 'Model');

/**
 * Class BlogsAppModel
 */
class BlogsAppModel extends AppModel {

/**
 * @var null 新規空データ
 */
	protected $_newRecord = null;


/**
 * バリデートメッセージ多言語化対応のためのラップ
 *
 * @param array $options options
 * @return bool
 */
	public function beforeValidate($options = array()) {
		$this->validate = Hash::merge(
			$this->validate,
			$this->_getValidateSpecification()
		);
		return parent::beforeValidate($options);
	}

/**
 * バリデート仕様を返す（継承した各モデルで実装）
 *
 * @return array
 */
	protected function _getValidateSpecification() {
		return array();
	}

}
