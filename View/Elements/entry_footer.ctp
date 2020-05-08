<div class="clearfix blogs_entry_reaction">
	<div class="pull-left">
		<?php if ($blogSetting['use_sns']) : ?>
			<?php
				$urlPrefix = parse_url(Configure::read('App.fullBaseUrl'), PHP_URL_SCHEME) . '://';
				$contentUrl = $urlPrefix . Configure::read('App.cacheDomain') . $this->NetCommonsHtml->url(array(
					'action' => 'view',
					'frame_id' => Current::read('Frame.id'),
					'key' => $blogEntry['BlogEntry']['key'],
				));
			?>
			<?php /* パフォーマンス改善のため、一覧表示でFacebook、Twitterボタンは表示しない。詳細画面で表示する */ ?>
			<?php if (!isset($index)) : ?>
				<!--Facebook-->
				<?php echo $this->SnsButton->facebook($contentUrl);?>
				<!--Twitter-->
				<div class="pull-left">
					<?php echo $this->SnsButton->twitter($contentUrl, $blogEntry['BlogEntry']['title']);?>
				</div>
			<?php endif ?>
		<?php endif ?>

		<div class="pull-left">
			<?php if (isset($index) && ($index === true)) : ?>
				<span class="blogs__content-comment-count">
			<?php echo $this->ContentComment->count($blogEntry); ?>
		</span>
			<?php endif ?>
		</div>

		<div class="pull-left">
			<?php echo $this->Like->buttons('BlogEntry', $blogSetting, $blogEntry, array('div' => true)); ?>
		</div>
	</div>
</div>
