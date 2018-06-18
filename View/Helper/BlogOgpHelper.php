<?php
/**
 * BlogOgpHelper
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */

/**
 * BlogOgpHelper.php
 *
 * @author   Ryuji AMANO <ryuji@ryus.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 */
class BlogOgpHelper extends AppHelper {

	public $helpers = [
		'NetCommons.NetCommonsHtml',
		'Text',
	];

/**
 * ローカルサーバから画像にアクセスするときにURL変換が必用な場合にURL変換マップを定義する
 *
 * @var array
 */
	private $__urlMap = [
		'search' => [],
		'replace' => []
	];

/**
 * og:imageに使う画像の最低サイズを指定。
 *
 * @var array
 */
	private $__minSize = [
		'width' => 200,
		'height' => 200
	];

/**
 * og:description の長さを指定。
 *
 * @var int
 */
	private $__descriptionLength = 90;

/**
 * Twitter Card type
 *
 * @var string
 */
	private $__twitterCardType = 'summary_large_image';

/**
 * Default Constructor
 *
 * @param View $View The View this helper is being attached to.
 * @param array $settings Configuration settings for the helper.
 */
	public function __construct(View $View, $settings = array()) {
		// ポートフォワードしていて内部サーバから同じURLにアクセスできないときに置換するマッピング表を読みこむ
		// 必用に応じて、
		// application.ymlで下記の様にリモートアクセスのURLとローカルサーバからのアクセスURLのマッピング表を用意する
		// ServerSetting:
		//  localUrlMap :
		//    http://127.0.0.1:9090: http://localhost
		$localUrlMap = Configure::read('ServerSetting.localUrlMap');
		if ($localUrlMap) {
			$this->__urlMap = [
				'search' => array_keys($localUrlMap),
				'replace' => $localUrlMap
			];
		}
		parent::__construct($View, $settings);
	}

/**
 * OGPタグ出力
 *
 * @param array $blogEntry BlogEntry data
 * @return string output html
 */
	public function ogpMetaByBlogEntry($blogEntry) {
		$ogpParams = $this->__getOgpParams($blogEntry);

		// body1からイメージリストを取り出す
		// 最初に規定サイズ以上だった画像をogImageに採用する
		$content = $blogEntry['BlogEntry']['body1'];
		$ogpParams = array_merge($ogpParams, $this->__getOgImageParams($content));

		// TwitterCard
		$ogpParams['twitter:card'] = $this->__twitterCardType;

		$output = $this->__makeMeta($ogpParams);
		return $output;
	}

/**
 * Metaタグ生成
 *
 * @param array $ogpParams property => contentの連想配列
 * @return string
 */
	private function __makeMeta($ogpParams) {
		$output = '';
		foreach ($ogpParams as $key => $value) {
			$output .= $this->NetCommonsHtml->meta(
				['property' => $key, 'content' => $value],
				null,
				['inline' => false]
			);
		}
		return $output;
	}

/**
 * サーバからアクセス可能なローカルURLへ変換したURLを返す
 *
 * @param string $imageUrl url
 * @return string local url
 */
	private function __getLocalAccessUrl($imageUrl) {
		$localUrl = str_replace($this->__urlMap['search'], $this->__urlMap['replace'], $imageUrl);
		return $localUrl;
	}

/**
 * img urlを絶対URLに変換する
 *
 * @param string $imageUrl (http....image|/dir/dir../image|../../....image)
 * @return string
 */
	private function __convertFullUrl($imageUrl) {
		// フルURL
		if (substr($imageUrl, 0, 4) === 'http') {
			return $imageUrl;
		}

		// ルートパス
		if (substr($imageUrl, 0, 1) === '/') {
			// "/" はじまりならルートパスなのでhttpホスト名を追加する
			$imageUrl = FULL_BASE_URL . $imageUrl;
			return $imageUrl;
		}

		// 相対パスの変換
		$currentPath = $this->NetCommonsHtml->url();
		$currentPathDirs = explode('/', $currentPath);
		//最後を除外
		array_pop($currentPathDirs);
		$currentUrlDir = implode('/', $currentPathDirs) . '/';
		$imageUrl = $this->NetCommonsHtml->url($currentUrlDir . $imageUrl, true);
		return $imageUrl;
	}

/**
 * og:image関連パラメータを取得
 *
 * @param string $content imgタグを含むHTML
 * @return array og:imageパラメータの連想配列
 *  セットする画像が見つかれば og:image, og:image:width,og:image:heightをキーとした連想配列
 *  セットする画像が見つからないときは空配列を返す
 */
	private function __getOgImageParams($content) {
		$pattern = '/<img.*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i';

		$ogpParams = [];
		if (preg_match_all($pattern, $content, $images)) {
			foreach ($images[1] as $imageUrl) {
				$imageUrl = $this->__convertFullUrl($imageUrl);

				$localUrl = $this->__getLocalAccessUrl($imageUrl);

				// 規定サイズ以上か…
				// @codingStandardsIgnoreStart
				// phpcs:disable
				// 画像がよみとれないこともあるので@でwarningを抑止している
				$size = @getimagesize($localUrl);
				// phpcs:enable
				// @codingStandardsIgnoreEnd
				if ($size) {
					$width = $size[0];
					$height = $size[1];

					if ($width >= $this->__minSize['width'] && $height >= $this->__minSize['height']) {
						$ogImageUrl = $imageUrl;

						$ogpParams['og:image'] = $ogImageUrl;
						$ogpParams['og:image:width'] = $width;
						$ogpParams['og:image:height'] = $height;
						return $ogpParams;
					}
				}
			}
		}
		return $ogpParams;
	}

/**
 * BlogEntryデータからOGPパラメータを返す
 *
 * @param array $blogEntry BlogEntry data
 * @return array
 */
	private function __getOgpParams($blogEntry) {
		$ogpParams = [];
		$ogpParams['og:title'] = $blogEntry['BlogEntry']['title'];
		$contentUrl = FULL_BASE_URL . $this->NetCommonsHtml->url(
				array(
					'action' => 'view',
					'frame_id' => Current::read('Frame.id'),
					'key' => $blogEntry['BlogEntry']['key'],
				)
			);
		$ogpParams['og:url'] = $contentUrl;
		// og:descriptionは90文字程度
		$ogpParams['og:description'] = $this->Text->truncate(
			strip_tags($blogEntry['BlogEntry']['body1']),
			$this->__descriptionLength
		);
		return $ogpParams;
	}
}
