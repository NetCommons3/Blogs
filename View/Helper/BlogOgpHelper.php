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

/**
 * use helpers
 *
 * @var array helpers
 */
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
		'width' => 100,
		'height' => 100
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
 * @var array twitter card の設定
 */
	private $__twitterCardSetting = [
		// デフォルトタイプ
		'default' => 'summary_large_image',
		// 横幅がこのサイズ未満だったらタイプをsummaryにする
		'large_image_min_width' => 600
	];

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
		$this->__twitterCardType = $this->__twitterCardSetting['default'];
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

		if (substr($imageUrl, 0, 2) === '//') {
			// "//"はじまりならプロトコルが省略されてるだけなのでhttpかhttpsを追加する
			$protocol = 'http:';
			if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) {
				$protocol = 'https:';
			}
			$imageUrl = $protocol . $imageUrl;
			return $imageUrl;
		}

		// ルートパス
		if (substr($imageUrl, 0, 1) === '/') {
			// "/" はじまりならルートパスなのでhttpホスト名を追加する
			$imageUrl = "https://" . Configure::read('App.cacheDomain') . $imageUrl;
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
				$imageUrl = str_replace('&amp;', '&', $imageUrl);

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

						// twitter card のsummary_large_image画像は幅600px以上となっているので、それ以下ならsummaryにする
						if ($width < $this->__twitterCardSetting['large_image_min_width']) {
							$this->__twitterCardType = 'summary';
							// twitter cardタイプをsummaryにしたら、wysiwyg画像なら smallをtwitter:imageに指定する
							if ($this->__isWysiwygImage($imageUrl)) {
								$smallImageUrl = $this->__getWysiwygSmallImageUrl($imageUrl);
								$ogpParams['twitter:image'] = $smallImageUrl;
							}
						}

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
 * Wysiwyg画像URLからsmall画像のURLを返す
 *
 * @param string $imageUrl wysiwyg画像のフルURL
 * @return string small 画像のフルurl
 */
	private function __getWysiwygSmallImageUrl($imageUrl) {
		// 末尾が文字列だったらサイズ指定されてる
		$lastSlashPos = strrpos($imageUrl, '/');
		$lastPath = substr($imageUrl, $lastSlashPos + 1);
		// 末尾が数値だったら画像IDなのでサイズ指定を後ろにつける
		if (preg_match('/^[0-9]+$/', $lastPath)) {
			return $imageUrl . '/small';
		}
		$withOutSizeUrl = substr($imageUrl, 0, $lastSlashPos);
		$url = $withOutSizeUrl . '/small';
		return $url;
	}

/**
 * Wysiwygの画像URLか
 *
 * @param string $imageUrl 画像のフルURL
 * @return bool
 */
	private function __isWysiwygImage($imageUrl) {
		if (strstr($imageUrl, FULL_BASE_URL . '/wysiwyg/image/download') !== false) {
			return true;
		}
		return false;
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
		$contentUrl = "https://" . Configure::read('App.cacheDomain') . $this->NetCommonsHtml->url(
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
