<?php
/**
 * Block index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<article class="block-setting-body">
	<?php echo $this->BlockTabs->main(BlockTabsHelper::MAIN_TAB_BLOCK_INDEX); ?>

	<?php echo $this->BlockIndex->description(); ?>

	<div class="tab-content">
		<div class="text-right">
			<?php echo $this->Button->addLink(); ?>
		</div>

		<?php echo $this->Form->create('', array(
			'url' => NetCommonsUrl::actionUrl(array('plugin' => 'frames', 'controller' => 'frames', 'action' => 'edit'))
		)); ?>

		<?php echo $this->Form->hidden('Frame.id'); ?>

		<table class="table table-hover">
			<thead>
			<tr>
				<th></th>
				<th>
					<?php echo $this->Paginator->sort('Blog.name', __d('blogs', 'Blog name')); ?>
				</th>
				<!--<th class="text-right">-->
				<!--	--><?php //echo $this->Paginator->sort('Blog.blog_article_count', __d('blogs', 'Article count')); ?>
				<!--</th>-->
				<th>
					<?php echo $this->Paginator->sort('Block.modified', __d('net_commons', 'Updated date')); ?>
				</th>
			</tr>
			</thead>
			<tbody>
			<?php foreach ($blogs as $blog) : ?>
				<tr<?php echo ($this->data['Frame']['block_id'] === $blog['Block']['id'] ? ' class="active"' : ''); ?>>
					<td>
						<?php echo $this->BlockForm->displayFrame('Frame.block_id', $blog['Block']['id']); ?>
					</td>
					<td>
						<?php echo $this->NetCommonsHtml->editLink($blog['Blog']['name'], array('block_id' => $blog['Block']['id'])); ?>
					</td>
					<!--<td class="text-right">-->
					<!--	--><?php //echo h($blog['Blog']['blog_article_count']); ?>
					<!--</td>-->
					<td>
						<?php echo $this->Date->dateFormat($blog['Block']['modified']); ?>
					</td>
					<!--</td>-->
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
		<?php echo $this->Form->end(); ?>

		<?php echo $this->element('NetCommons.paginator'); ?>
	</div>
</article>




