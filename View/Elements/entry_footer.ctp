<div class="clearfix blogs_entry_reaction">
	<div class="pull-left">
		<?php if ($blogSetting['use_sns']) : ?>

			<!--Facebook-->
			<div class="fb-like pull-left" data-href="<?php echo FULL_BASE_URL ?>/blogs/blog_entries/view/<?php echo Current::read('Frame.id') ?>/origin_id:<?php echo $blogEntry['BlogEntry']['origin_id'] ?>" data-layout="button_count" data-action="like"
				 data-show-faces="false" data-share="false"></div>

			<!--Twitter-->
			<div class="pull-left">
				<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
				<script>!function (d, s, id) {
						var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https';
						if (!d.getElementById(id)) {
							js = d.createElement(s);
							js.id = id;
							js.src = p + '://platform.twitter.com/widgets.js';
							fjs.parentNode.insertBefore(js, fjs);
						}
					}(document, 'script', 'twitter-wjs');</script>
			</div>
		<?php endif ?>



		<div class="pull-left">
			<?php if (isset($index) && ($index === true)) : ?>
				<!--view only-->
				<?php echo $this->Like->display($blogSetting, $blogEntry, array('div' => true)); ?>
			<?php else : ?>
				<!--post like-->
				<?php echo $this->Like->buttons('BlogEntry', $blogSetting, $blogEntry, array('div' => true)); ?>
			<?php endif ?>
		</div>
	</div>

	<div class="pull-right">
		<?php if (isset($index) && ($index === true)) : ?>
		<span style="padding-right: 15px;">
			<span class="glyphicon glyphicon-comment" aria-hidden="true"></span> <?php echo (int)$blogEntry['ContentCommentCnt']['cnt']; ?>
		</span>
		<?php endif ?>
	</div>

</div>
