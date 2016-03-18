<?php
echo $this->Html->css(
	'/blogs/css/blogs.css',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
); ?>
<?php
// Like
echo $this->Html->script(
	'/likes/js/likes.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
echo $this->Html->css(
	'/likes/css/style.css',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>
<?php echo $this->BackTo->pageLinkButton(__d('blogs', 'Move list'), array('icon' => 'list')) ?>
<div class="blogs_entry_status">
	<?php echo $this->Workflow->label($blogEntry['BlogEntry']['status']); ?>
</div>

<article>
	<h1>
		<?php echo $this->TitleIcon->titleIcon($blogEntry['BlogEntry']['title_icon']); ?>

		<?php echo h($blogEntry['BlogEntry']['title']); ?>
	</h1>

	<?php echo $this->element('entry_meta_info'); ?>

	<div>
		<?php echo $this->element('BlogEntries/edit_link', array('status' => $blogEntry['BlogEntry']['status'])); ?>
	</div>


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
			<?php echo $this->Html->link(
				$blogTag['name'],
				$this->NetCommonsHtml->url(array('controller' => 'blog_entries', 'action' => 'tag', 'frame_id' => Current::read('Frame.id'), 'id' => $blogTag['id']))
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


