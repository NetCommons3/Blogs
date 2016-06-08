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
echo $this->Html->script(
	'/blogs/js/blogs.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>
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

<div class="blogEntries index nc-content-list" ng-controller="Blogs.Entries" ng-init="init(<?php echo Current::read('Frame.id') ?>)">
	<h1 class="blogs_blogTitle"><?php echo $listTitle ?></h1>

	<div class="clearfix blogs_navigation_header">
		<div class="pull-left">
			<span class="dropdown">
				<button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-expanded="true">
					<?php echo $filterDropDownLabel ?>
					<span class="caret"></span>
				</button>
				<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
					<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $this->NetCommonsHtml->url(
							array(
								'action' => 'index',
								'frame_id' => Current::read('Frame.id'),
							)
						);?>"><?php echo __d('blogs', 'All Entries') ?></a></li>
					<li role="presentation" class="dropdown-header"><?php echo __d('blogs', 'Category') ?></li>

					<?php echo $this->Category->dropDownToggle(array(
						'empty' => false,
						'displayMenu' => false,
						$this->NetCommonsHtml->url(array('action' => 'index')),
					)); ?>

					<li role="presentation" class="divider"></li>

					<li role="presentation" class="dropdown-header"><?php echo __d('blogs', 'Archive')?></li>
					<?php foreach($yearMonthOptions as $yearMonth => $label): ?>

						<li role="presentation"><a role="menuitem" tabindex="-1" href="<?php echo $this->NetCommonsHtml->url(
								array(
									'action' => 'year_month',
									'frame_id' => Current::read('Frame.id'),
									'year_month' => $yearMonth,
								)
							);?>"><?php echo $label ?></a></li>
					<?php endforeach ?>
				</ul>
			</span>
			<?php echo $this->DisplayNumber->dropDownToggle(); ?>
			<?php /* 表示件数 */ ?>


		</div>

		<?php if (Current::permission('content_creatable')) : ?>
		<div class="pull-right">
			<?php
			$addUrl = $this->NetCommonsHtml->url(array(
				'controller' => 'blog_entries_edit',
				'action' => 'add',
				'frame_id' => Current::read('Frame.id')
			));
			echo $this->Button->addLink('',
				$addUrl,
			array('tooltip' => __d('blogs', 'Add entry')));
			?>
		</div>
		<?php endif ?>

	</div>

	<div>
		<!--記事一覧-->
		<?php if (count($blogEntries) == 0): ?>
			<p>
				<?php echo __d('net_commons', '%s is not.', __d('blogs', 'BlogEntry')); ?>
			</p>
		<?php endif?>
		<?php foreach ($blogEntries as $blogEntry): ?>

			<div class="blogs_entry" ng-controller="Blogs.Entries.Entry">


				<div class="blogs_entry_status">
					<?php echo $this->Workflow->label($blogEntry['BlogEntry']['status']); ?>
				</div>

				<article>
					<h2 class="blogs_entry_title">
						<?php echo $this->TitleIcon->titleIcon($blogEntry['BlogEntry']['title_icon']); ?>
						<?php echo $this->Html->link(
							$blogEntry['BlogEntry']['title'],
							$this->NetCommonsHtml->url(
								array(
									'controller' => 'blog_entries',
									'action' => 'view',
									//'frame_id' => Current::read('Frame.id'),
									'key' => $blogEntry['BlogEntry']['key']
								)
							)
						);
						?>
					</h2>
					<?php echo $this->element('entry_meta_info', array('blogEntry' => $blogEntry)); ?>

					<div class="blogs_entry_body1">
						<?php echo $blogEntry['BlogEntry']['body1']; ?>
					</div>
					<?php if ($blogEntry['BlogEntry']['body2']) : ?>
						<div ng-hide="isShowBody2">
							<a ng-click="showBody2()"><?php echo __d('blogs', 'Read more'); ?></a>
						</div>
						<div ng-show="isShowBody2">
							<?php echo $blogEntry['BlogEntry']['body2'] ?>
						</div>
						<div ng-show="isShowBody2">
							<a ng-click="hideBody2()"><?php echo __d('blogs', 'Close'); ?></a>
						</div>
					<?php endif ?>
					<?php echo $this->element('entry_footer', array('blogEntry' => $blogEntry, 'index' => true)); ?>
				</article>

			</div>


		<?php endforeach; ?>
	</div>

	<?php echo $this->element('NetCommons.paginator') ?>
</div>
