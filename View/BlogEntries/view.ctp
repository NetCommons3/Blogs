<?php echo $this->element('shared_header'); ?>

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
<?php echo $this->BackTopage->backToPageButton(__d('blogs', 'Move list')) ?>
<div class="blogs_entry_status">
	<?php echo $this->element(
		'NetCommons.status_label',
		array('status' => $blogEntry['BlogEntry']['status'])
	); ?>
</div>

<article>
	<h1><?php echo h($blogEntry['BlogEntry']['title']); ?></h1>

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
				array('controller' => 'blog_entries', 'action' => 'tag', $frameId, 'id' => $blogTag['id'])
			); ?>&nbsp;
		<?php endforeach; ?>
	</div>
	<?php endif ?>

	<div>
		<?php /* コンテンツコメント */ ?>
		<div class="row">
			<div class="col-xs-12">
				<?php echo $this->element('ContentComments.index', array(
					'formName' => 'Blog',
					'useComment' => $blogSetting['useComment'],
					'contentCommentCnt' => $blogEntry['ContentCommentCnt']['cnt'],
				)); ?>
			</div>
		</div>
	</div>
</article>


