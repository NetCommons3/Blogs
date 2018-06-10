<?php
echo $this->NetCommonsHtml->css([
	'/blogs/css/blogs.css',
	'/likes/css/style.css',
]);
echo $this->NetCommonsHtml->script([
	'/likes/js/likes.js',
]);
// ポートフォワードしていて内部サーバから同じURLにアクセスできないときに置換するマッピング表 これは開発環境用
$urlMapping4localServer = [
		'http://127.0.0.1:9090' => 'http://127.0.0.1',
		'http://app.local:9090' => 'http://app.local',
];
$remoteUrls = array_keys($urlMapping4localServer);
$localUrls = $urlMapping4localServer;

$ogTitle = $blogEntry['BlogEntry']['title'];
$contentUrl = FULL_BASE_URL . $this->NetCommonsHtml->url(array(
					'action' => 'view',
					'frame_id' => Current::read('Frame.id'),
					'key' => $blogEntry['BlogEntry']['key'],
				));
$ogUrl = $contentUrl;
// 90文字程度
$ogDescription = $this->Text->truncate(strip_tags($blogEntry['BlogEntry']['body1']), '90');
// body1からイメージリストを取り出す
// 最初に規定サイズ以上だった画像をogImageに採用する
$pattern = '/<img.*?src\s*=\s*[\"|\'](.*?)[\"|\'].*?>/i';
$content = $blogEntry['BlogEntry']['body1'];

if (preg_match_all( $pattern, $content, $images )) {
	foreach($images[1] as $imageUrl) {
		if (substr($imageUrl, 0, 4) === 'http') {
			// 絶対URLなのでそのまま
		} elseif (substr($imageUrl, 0, 1) === '/') {
			// "/" はじまりならルートパスなのでhttpホスト名を追加する
			$host = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']) ? 'https://' : 'http://';
			$host .= $_SERVER['HTTP_HOST'];
			$imageUrl = $host . $imageUrl;
		}
		$localUrl = str_replace($remoteUrls, $localUrls, $imageUrl);
		// TODO 相対パスの変換
		// TODO ルートパスの変換
		// NC3テスト環境用
		// 規定サイズ以上か…
		$size = getimagesize($localUrl);
		$width = $size[0];
		$height = $size[1];

		if ($width >= 200 && $height >= 200) {
			$ogImageUrl = $imageUrl;

			echo $this->NetCommonsHtml->meta(
				['property' => 'og:image', 'content' => $ogImageUrl],
				null,
				['inline' => false]);
			break;
		}
	}

}

$twitterCardType = 'summary_large_image';
// TwitterCard
echo $this->NetCommonsHtml->meta(
		['name' => 'twitter:card', 'content' => $twitterCardType],
		null,
		['inline' => false]);
// OGP
echo $this->NetCommonsHtml->meta(
	['property' => 'og:url', 'content' => $ogUrl],
	null,
	['inline' => false]);
echo $this->NetCommonsHtml->meta(
	['property' => 'og:title', 'content' => $ogTitle],
	null,
	['inline' => false]);
echo $this->NetCommonsHtml->meta(
	['property' => 'og:description', 'content' => $ogDescription],
	null,
	['inline' => false]);
?>

<header class="clearfix">
	<div class="pull-left">
		<?php echo $this->LinkButton->toList(); ?>
	</div>
	<div class="pull-right">
		<?php echo $this->element('BlogEntries/edit_link', array('status' => $blogEntry['BlogEntry']['status'])); ?>
	</div>

</header>

<article>

	<div class="blogs_view_title clearfix">
		<?php echo $this->NetCommonsHtml->blockTitle(
				$blogEntry['BlogEntry']['title'],
				$blogEntry['BlogEntry']['title_icon'],
				array('status' => $this->Workflow->label($blogEntry['BlogEntry']['status']))
			); ?>
	</div>

	<?php echo $this->element('entry_meta_info'); ?>



	<div>
		<?php echo $blogEntry['BlogEntry']['body1']; ?>
	</div>
	<div>
		<?php echo $blogEntry['BlogEntry']['body2']; ?>
	</div>

	<?php echo $this->element('entry_footer'); ?>

	<!-- Tags -->
	<?php if (isset($blogEntry['Tag'])) : ?>
	<div>
		<?php echo __d('blogs', 'tag'); ?>
		<?php foreach ($blogEntry['Tag'] as $blogTag): ?>
			<?php echo $this->NetCommonsHtml->link(
				$blogTag['name'],
				array('controller' => 'blog_entries', 'action' => 'tag', 'frame_id' => Current::read('Frame.id'), 'id' => $blogTag['id'])
			); ?>&nbsp;
		<?php endforeach; ?>
	</div>
	<?php endif ?>

	<div>
		<?php /* コンテンツコメント */ ?>
		<?php echo $this->ContentComment->index($blogEntry); ?>
		<!--<div class="row">-->
		<!--	<div class="col-xs-12">-->
		<!--		--><?php //echo $this->element('ContentComments.index', array(
		//			'pluginKey' => $this->request->params['plugin'],
		//			'contentKey' => $blogEntry['BlogEntry']['key'],
		//			'isCommentApproved' => $blogSetting['use_comment_approval'],
		//			'useComment' => $blogSetting['use_comment'],
		//			'contentCommentCnt' => $blogEntry['ContentCommentCnt']['cnt'],
		//			'redirectUrl' => $this->NetCommonsHtml->url(array('plugin' => 'blogs', 'controller' => 'blog_entries', 'action' => 'view', 'frame_id' => Current::read('Frame.id'), 'key' => $blogEntry['BlogEntry']['key'])),
		//		)); ?>
		<!--	</div>-->
		<!--</div>-->
	</div>
</article>


