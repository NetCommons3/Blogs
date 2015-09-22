<div class="clearfix blogs_entry_reaction">
	<div class="pull-left">
		<?php if ($blogSetting['useSns']) : ?>

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
			<!--view only-->
			<?php if (isset($index) && ($index === true)) : ?>
				<?php if ($blogSetting['useLike']) : ?>
					<div class="inline-block text-muted">
						<span class="glyphicon glyphicon-thumbs-up"></span>
						<?php echo isset($blogEntry['BlogEntry']['like_counts']) ? (int)$blogEntry['BlogEntry']['like_counts'] : 0; ?>
					</div>
				<?php endif; ?>

				<?php if ($blogSetting['useUnlike']) : ?>
					<div class="inline-block text-muted">
						<span class="glyphicon glyphicon-thumbs-down"></span>
						<?php echo isset($blogEntry['BlogEntry']['unlike_counts']) ? (int)$blogEntry['BlogEntry']['unlike_counts'] : 0; ?>
					</div>
				<?php endif; ?>
			<?php else : ?>
				<!--post like-->
				<div >
					<div <?php echo $this->element('Likes.like_init_attributes', array(
						'contentKey' => $blogEntry['BlogEntry']['key'],
						'disabled' => !(! isset($blogEntry['Like']) && $blogEntry['BlogEntry']['status'] === NetCommonsBlockComponent::STATUS_PUBLISHED),
						'likeCounts' => (int)$blogEntry['BlogEntry']['like_counts'],
						'unlikeCounts' => (int)$blogEntry['BlogEntry']['unlike_counts'],
					)); ?>>
						<?php if ($blogSetting['useLike']) : ?>
							<div class="inline-block">
								<?php echo $this->element('Likes.like_button', array('isLiked' => Like::IS_LIKE)); ?>
							</div>
						<?php endif; ?>

						<?php if ($blogSetting['useUnlike']) : ?>
							<div class="inline-block">
								<?php echo $this->element('Likes.like_button', array('isLiked' => Like::IS_UNLIKE)); ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
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
