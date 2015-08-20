<?php
/**
 * Created by PhpStorm.
 * User: ryuji
 * Date: 2015/07/23
 * Time: 19:23
 */

/**
 * Class BlogEntryPermissionComponent
 */
class BlogEntryPermissionComponent extends Component {

/**
 * @var Controller 呼び出し元コントローラ startupでセット
 */
	protected $_controller = null;

/**
 * startup
 *
 * @param Controller $controller 呼び出し元コントローラ
 *
 * @return void
 */
	public function startup(Controller $controller) {
		$this->_controller = $controller;
	}

/**
 * 編集の権限チェック
 *
 * @param array $blogEntry コンテンツデータ
 * @return bool
 */
	public function canEdit($blogEntry) {
		if ($this->_controller->viewVars['contentEditable']) {
			// 編集権限あり　＝＞OK
		} elseif ($this->_controller->viewVars['contentCreatable']) {
			// 作成権限あり→自分の記事ならOK
			if ($blogEntry['BlogEntry']['created_user'] !== $this->_controller->Auth->user('id')) {
				return false;
			}
		} else {
			return false;
		}
		return true;
	}

/**
 * 削除権限チェック
 *
 * @param array $blogEntry コンテンツデータ
 * @return bool
 */
	public function canDelete($blogEntry) {
		// 編集できるかチェック
		if ($this->canEdit($blogEntry)) {
			// 公開権限あれば削除OK
			if ($this->_controller->viewVars['contentPublishable']) {
				return true;
			}
			// 公開権限無しなら一度も公開されてなければ削除OK
			if ($this->_controller->BlogEntry->yetPublish($blogEntry)) {
				return true;
			}
		}
		// 上記以外削除NG
		return false;
	}

	// ε(　　　　 v ﾟωﾟ)　＜他でも使えるようにするには、ModelをControllerから確保だな
	//protected function _getModel() {
	//	if (isset($this->Controller->{$this->Controller->modelClass})) {
	//		return $this->Controller->{$this->Controller->modelClass};
	//	}
	//
	//	$className = null;
	//	$name = $this->Controller->uses[0];
	//	if (strpos($this->Controller->uses[0], '.') !== false) {
	//		list($name, $className) = explode('.', $this->Controller->uses[0]);
	//	}
	//	if ($className) {
	//		return $this->Controller->{$className};
	//	}
	//
	//	return $this->Controller->{$name};
	//
	//}
}