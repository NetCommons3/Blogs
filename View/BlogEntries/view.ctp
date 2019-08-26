<?php
echo $this->NetCommonsHtml->css([
	'/blogs/css/blogs.css',
	'/likes/css/style.css',
]);
echo $this->NetCommonsHtml->script([
	'/likes/js/likes.js',
]);
//debug($this->NetCommonsHtml->url());
echo $this->BlogOgp->ogpMetaByBlogEntry($blogEntry);
?>

<header class="clearfix">
	<div class="pull-left">
		<?php echo $this->LinkButton->toList(); ?>
	</div>
	<div class="pull-right">
		<?php echo $this->element('Blogs.BlogEntries/edit_link', array('status' => $blogEntry['BlogEntry']['status'])); ?>
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

	<?php echo $this->element('Blogs.entry_meta_info'); ?>



	<div class="clearfix">
		<?php echo $blogEntry['BlogEntry']['body1']; ?>
	</div>

	<div class="clearfix">
		<?php echo $blogEntry['BlogEntry']['body2']; ?>
	</div>

	<?php echo $this->element('Blogs.entry_footer'); ?>

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


