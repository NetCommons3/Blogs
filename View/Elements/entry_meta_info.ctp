<div class="blogs_entry_meta">
	<div>

		<?php echo __d(
			'blogs',
			'posted : %s',
			$this->Date->dateFormat($blogEntry['BlogEntry']['publish_start'])
		); ?>&nbsp;

		<?php echo $this->NetCommonsHtml->handleLink($blogEntry, array('avatar' => true)); ?>&nbsp;

		<?php if (isset($blogEntry['CategoriesLanguage']['category_id'])):?>
			<?php echo __d('blogs', 'Category') ?>:<?php echo $this->NetCommonsHtml->link(
				$blogEntry['CategoriesLanguage']['name'],
				array(
					'controller' => 'blog_entries',
					'action' => 'index',
					'frame_id' => Current::read('Frame.id'),
					'category_id' => $blogEntry['BlogEntry']['category_id']
				)
			); ?>
		<?php endif; ?>

	</div>
</div>
