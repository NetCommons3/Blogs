<div class="blogCategories index">
	<h2><?php echo __('Blog Categories'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('block_id'); ?></th>
			<th><?php echo $this->Paginator->sort('key'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('created_user'); ?></th>
			<th><?php echo $this->Paginator->sort('created'); ?></th>
			<th><?php echo $this->Paginator->sort('modified_user'); ?></th>
			<th><?php echo $this->Paginator->sort('modified'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($blogCategories as $blogCategory): ?>
	<tr>
		<td><?php echo h($blogCategory['BlogCategory']['id']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($blogCategory['Block']['name'], array('controller' => 'blocks', 'action' => 'view', $blogCategory['Block']['id'])); ?>
		</td>
		<td><?php echo h($blogCategory['BlogCategory']['key']); ?>&nbsp;</td>
		<td><?php echo h($blogCategory['BlogCategory']['name']); ?>&nbsp;</td>
		<td><?php echo h($blogCategory['BlogCategory']['created_user']); ?>&nbsp;</td>
		<td><?php echo h($blogCategory['BlogCategory']['created']); ?>&nbsp;</td>
		<td><?php echo h($blogCategory['BlogCategory']['modified_user']); ?>&nbsp;</td>
		<td><?php echo h($blogCategory['BlogCategory']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $blogCategory['BlogCategory']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $blogCategory['BlogCategory']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $blogCategory['BlogCategory']['id']), null, __('Are you sure you want to delete # %s?', $blogCategory['BlogCategory']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Blog Category'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Blocks'), array('controller' => 'blocks', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Block'), array('controller' => 'blocks', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Blog Entries'), array('controller' => 'blog_entries', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Blog Entry'), array('controller' => 'blog_entries', 'action' => 'add')); ?> </li>
	</ul>
</div>
