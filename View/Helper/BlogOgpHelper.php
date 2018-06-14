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

	private $__urlMap = [
		'search' => [],
		'replace' => []
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
		parent::__construct($View, $settings);
	}

/**
 * OGPタグ出力
 *
 * @param array $blogEntry BlogEntry data
 * @return string output html
 */
	public function ogpMetaByBlogEntry($blogEntry) {

		$ogTitle = $blogEntry['BlogEntry']['title'];
		$contentUrl = FULL_BASE_URL . $this->NetCommonsHtml->url(
				array(
					'action' => 'view',
					'frame_id' => Current::read('Frame.id'),
					'key' => $blogEntry['BlogEntry']['key'],
				)
			);
		$ogUrl = $contentUrl;
// 90文字程度
		$ogDescription = $this->Text->truncate(strip_tags($blogEntry['BlogEntry']['body1']), '90');
// body1からイメージリストを取り出す
// 最初に規定サイズ以上だった画像をogImageに採用する
		$pattern = '/<img.*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i';
		$content = $blogEntry['BlogEntry']['body1'];

		$output = '';
		if (preg_match_all($pattern, $content, $images)) {
			foreach ($images[1] as $imageUrl) {
				$imageUrl = $this->__convertFullUrl($imageUrl);

				$localUrl = $this->__getLocalAccessUrl($imageUrl);

				// 規定サイズ以上か…
				$size = getimagesize($localUrl);
				$width = $size[0];
				$height = $size[1];

				if ($width >= 200 && $height >= 200) {
					$ogImageUrl = $imageUrl;

					$output .= $this->NetCommonsHtml->meta(
						['property' => 'og:image', 'content' => $ogImageUrl],
						null,
						['inline' => false]
					);
					$output .= $this->NetCommonsHtml->meta(
						['property' => 'og:image:width', 'content' => $width],
						null,
						['inline' => false]
					);
					$output .= $this->NetCommonsHtml->meta(
						['property' => 'og:image:height', 'content' => $height],
						null,
						['inline' => false]
					);
					break;
				}
			}

		}

		$twitterCardType = 'summary_large_image';
		// TwitterCard
		$output .= $this->NetCommonsHtml->meta(
			['name' => 'twitter:card', 'content' => $twitterCardType],
			null,
			['inline' => false]
		);
		// OGP
		$output .= $this->NetCommonsHtml->meta(
			['property' => 'og:url', 'content' => $ogUrl],
			null,
			['inline' => false]
		);
		$output .= $this->NetCommonsHtml->meta(
			['property' => 'og:title', 'content' => $ogTitle],
			null,
			['inline' => false]
		);
		$output .= $this->NetCommonsHtml->meta(
			['property' => 'og:description', 'content' => $ogDescription],
			null,
			['inline' => false]
		);
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
}
