<?php
/**
 * BlogEntriesEdit
 */
App::uses('BlogsAppController', 'Blogs.Controller');

/**
 * BlogEntriesEdit Controller
 *
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link     http://www.netcommons.org NetCommons Project
 * @license  http://www.netcommons.org/license.txt NetCommons License
 * @property NetCommonsWorkflow $NetCommonsWorkflow
 * @property PaginatorComponent $Paginator
 * @property BlogEntry $BlogEntry
 * @property BlogCategory $BlogCategory
 * @property NetCommonsComponent $NetCommons
 */
class BlogEntriesEditController extends BlogsAppController {

/**
 * @var array use models
 */
	public $uses = array(
		'Blogs.BlogEntry',
		'Categories.Category',
		'Workflow.WorkflowComment',
	);

/**
 * Components
 *
 * @var array
 */
	public $components = array(
		'NetCommons.Permission' => array(
			//アクセスの権限
			'allow' => array(
				'add,edit,delete' => 'content_creatable',
			),
		),
		'Workflow.Workflow',

		'Categories.Categories',
		//'Blogs.BlogEntryPermission',
		'NetCommons.NetCommonsTime',
		'Files.FileUpload',
		'Files.Download',
	);

/**
 * @var array helpers
 */
	public $helpers = array(
		//'NetCommons.Token',
		'NetCommons.BackTo',
		'NetCommons.NetCommonsForm',
		'Workflow.Workflow',
		'NetCommons.NetCommonsTime',
		//'Likes.Like',
	);

/**
 * add method
 *
 * @return void
 */
	public function add() {
		$this->set('isEdit', false);

		$blogEntry = $this->BlogEntry->getNew();
		$this->set('blogEntry', $blogEntry);

		if ($this->request->is('post')) {
			$this->BlogEntry->create();
			$this->request->data['BlogEntry']['blog_key'] = ''; // https://github.com/NetCommons3/NetCommons3/issues/7 対策

			// set status
			$status = $this->Workflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = Current::read('Block.id');
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];
			if (($result = $this->BlogEntry->saveEntry(Current::read('Block.id'), Current::read('Frame.id'), $this->request->data))) {
				$url = NetCommonsUrl::actionUrl(
					array(
						'controller' => 'blog_entries',
						'action' => 'view',
						'block_id' => Current::read('Block.id'),
						'frame_id' => Current::read('Frame.id'),
						'key' => $result['BlogEntry']['key'])
				);
				return $this->redirect($url);
			}

			$this->NetCommons->handleValidationError($this->BlogEntry->validationErrors);

		} else {
			$this->request->data = $blogEntry;
			$this->request->data['Tag'] = array();
		}

		$this->render('form');
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @throws ForbiddenException
 * @return void
 */
	public function edit() {
		$this->set('isEdit', true);
		//$key = $this->request->params['named']['key'];
		$key = $this->params['pass'][1];

		//  keyのis_latstを元に編集を開始
		$blogEntry = $this->BlogEntry->findByKeyAndIsLatest($key, 1);
		if (empty($blogEntry)) {
			//  404 NotFound
			throw new NotFoundException();
		}

		if ($this->request->is(array('post', 'put'))) {

			$this->BlogEntry->create();
			$this->request->data['BlogEntry']['blog_key'] = ''; // https://github.com/NetCommons3/NetCommons3/issues/7 対策

			// set status
			$status = $this->Workflow->parseStatus();
			$this->request->data['BlogEntry']['status'] = $status;

			// set block_id
			$this->request->data['BlogEntry']['block_id'] = Current::read('Block.id');
			// set language_id
			$this->request->data['BlogEntry']['language_id'] = $this->viewVars['languageId'];

			$data = $this->request->data;

			unset($data['BlogEntry']['id']); // 常に新規保存

			if ($this->BlogEntry->saveEntry(Current::read('Block.id'), Current::read('Frame.id'), $data)) {
				$url = NetCommonsUrl::actionUrl(
					array(
						'controller' => 'blog_entries',
						'action' => 'view',
						'frame_id' => Current::read('Frame.id'),
						'block_id' => Current::read('Block.id'),
						'key' => $data['BlogEntry']['key']
					)
				);

				return $this->redirect($url);
			}

			$this->NetCommons->handleValidationError($this->BlogEntry->validationErrors);

		} else {

			$this->request->data = $blogEntry;
			if ($this->BlogEntry->canEditWorkflowContent($blogEntry) === false) {
				throw new ForbiddenException(__d('net_commons', 'Permission denied'));
			}

		}

		$this->set('blogEntry', $blogEntry);
		$this->set('isDeletable', $this->BlogEntry->canDeleteWorkflowContent($blogEntry));

		$comments = $this->BlogEntry->getCommentsByContentKey($blogEntry['BlogEntry']['key']);
		$this->set('comments', $comments);

		$this->render('form');
	}

/**
 * delete method
 *
 * @throws ForbiddenException
 * @throws InternalErrorException
 * @return void
 */
	public function delete() {
		$key = $this->request->data['BlogEntry']['key'];

		$this->request->allowMethod('post', 'delete');

		$blogEntry = $this->BlogEntry->findByKeyAndIsLatest($key, 1);

		// 権限チェック
		if ($this->BlogEntry->canDeleteWorkflowContent($blogEntry) === false) {
			throw new ForbiddenException(__d('net_commons', 'Permission denied'));
		}

		if ($this->BlogEntry->deleteEntryByKey($key) === false) {
			throw new InternalErrorException(__d('net_commons', 'Internal Server Error'));
		}
		return $this->redirect(
			NetCommonsUrl::actionUrl(
				array('controller' => 'blog_entries', 'action' => 'index', 'frame_id' => Current::read('Frame.id'), 'block_id' => Current::read('Block.id'))));
	}

	public function import() {
		App::uses('CsvFileReader', 'Files.Utility');
		if ($this->request->is(array('post', 'put'))) {
			$file = $this->FileUpload->getTemporaryUploadFile('import_csv');
			debug($file);
			$reader = new CsvFileReader($file);
			foreach($reader as $row){
				debug($row);
			}
		}
	}

	public function regist() {
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$path = '/var/www/app/app/Plugin/Files/Test/Fixture/logo.gif';
		$path2 = TMP . 'logo.gif';
		copy($path, $path2);
		$UploadFile->registByFilePath($path2, 'blogs', 'content_key..', 'photo');
	}

	public function attach_file() {
		$UploadFile = ClassRegistry::init('Files.UploadFile');
		$path = '/var/www/app/app/Plugin/Files/Test/Fixture/logo.gif';
		$path2 = TMP . 'logo.gif';
		copy($path, $path2);
		$data = $this->BlogEntry->findByIsLatest(true);
		//$file = new File($path2);
		$this->BlogEntry->attachFile($data, 'pdf', $path2);

		$savedData = $this->BlogEntry->findById($data['BlogEntry']['id']);
		debug($savedData);
	}

	public function temporary_download() {
		App::uses('TemporaryFile', 'Files.Utility');
		$file = new TemporaryFile();
		$file->append('test');

		//$this->Download = $this->Components->load('Files.Download');
		//return $this->Download->downloadFile($file, ['name', 'test.txt']);

		$this->response->file($file->path, ['name' => 'test.txt']);
		return $this->response;
	}


	/**
	 * 配列のCSV出力例
	 *
	 * @return CakeResponse|null
	 */
	public function csv_download2() {

		if ($this->request->is(array('post', 'put'))) {
			App::uses('CsvFileWriter', 'Files.Utility');

			$data = [
					['データID', 'タイトル', '本文', '作成日時'],
					[1, '薪だなつくりました', '薪だなつくるの大変だったよ', '2015-10-01 10:00:00'],
					[2, '薪ストーブ点火', '寒くなってきたので薪ストーブに火入れましたよ', '2015-12-01 13:00:00'],
			];
			$csvWriter = new CsvFileWriter();
			foreach($data as $line){
				$csvWriter->add($line);
			}
			$csvWriter->close();

			return $csvWriter->download('export.csv');
		}

	}

	/**
	 * Modelから取得したデータの指定カラムだけCSV出力する例
	 *
	 * @return CakeResponse
	 */
	public function csv_download3() {
		App::uses('CsvFileWriter', 'Files.Utility');

		$header = [
			'BlogEntry.id' => 'データID',
			'BlogEntry.title' => 'タイトル',
			'BlogEntry.body1' => '本文1',
			'BlogEntry.publish_start' => '公開日時'
		];
		$result = $this->BlogEntry->find('all');

		$csvWriter = new CsvFileWriter(['header' => $header]);
		foreach($result as $data){
			$csvWriter->addModelData($data);
		}
		$csvWriter->close();

		//$zip = new ZipArchive();
		//$tmpFile = new TemporaryFile();
		//$zip->open($tmpFile->path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
		//$zip->addFile($csvWriter->path);
		//$zip->close();

		// パスワード


		//return $csvWriter->download('export.csv');
		return $csvWriter->zipDownload('test.zip', 'foo.csv', 'pass');
	}

}

