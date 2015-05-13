<?php echo $this->element('shared_header'); ?>
<?php echo $this->Html->css('/blogs/css/blogs.css'); ?>

<?php echo $this->Html->link(
	__d('blogs', 'Move list'),
	array('controller' => 'blog_entries', 'action' => 'index', $frameId)
); ?>

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
		<?php if ($contentCreatable): ?>

			<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Edit'); ?>">
		<a href="<?php echo $this->Html->url(
			array('controller' => 'blog_entries_edit', 'action' => 'edit', $frameId, 'origin_id' => $blogEntry['BlogEntry']['origin_id'])
		) ?>" class="btn btn-primary">
			<span class="glyphicon glyphicon-edit"> </span>
		</a>
	</span>

		<?php endif ?>
	</div>


	<div>
		<?php echo $blogEntry['BlogEntry']['body1']; ?>
	</div>
	<div>
		<?php echo $blogEntry['BlogEntry']['body2']; ?>
	</div>

	<?php echo $this->element('entry_footer'); ?>

	<!-- Tags -->
	<div>
		<?php echo __d('blogs', 'tag'); ?>
		<?php foreach ($blogEntry['Tag'] as $blogTag): ?>
			<?php echo $this->Html->link(
				$blogTag['name'],
				array('controller' => 'blog_entries', 'action' => 'tag', $frameId, 'id' => $blogTag['id'])
			); ?>&nbsp;
		<?php endforeach; ?>

	</div>

	<div>
		<!-- ε(　　　　 v ﾟωﾟ)　＜ Coreで開発されたらコメント機能を組み込む-->
		<?php //echo $this->element('Comments.index'); ?>

	</div>
</article>


