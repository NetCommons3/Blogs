<div class="clearfix blogs_entry_reaction">
	<div class="pull-left">
		<?php if ($blogSetting['use_sns']) : ?>

			<?php $contentUrl = FULL_BASE_URL . $this->NetCommonsHtml->url(array(
					'action' => 'view',
					'frame_id' => Current::read('Frame.id'),
					'key' => $blogEntry['BlogEntry']['key'],
				));
			?>
			<!--Facebook-->
			<?php echo $this->SnsButton->facebook($contentUrl);?>
			<!--Twitter-->
			<div class="pull-left">
				<?php echo $this->SnsButton->twitter($contentUrl, $blogEntry['BlogEntry']['title']);?>
			</div>
		<?php endif ?>

		<div class="pull-left">
			<?php if (isset($index) && ($index === true)) : ?>
				<span class="blogs__content-comment-count">
			<?php echo $this->ContentComment->count($blogEntry); ?>
		</span>
			<?php endif ?>
		</div>

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


</div>
