<?php echo $this->NetCommonsHtml->script([
	'/blogs/js/blogs.js',
	'/blogs/js/blogs_entry_edit.js',
	'/tags/js/tags.js',
]); ?>
<?php
$dataJson = json_encode(
	$this->NetCommonsTime->toUserDatetimeArray($this->request->data, array('BlogEntry.publish_start'))
);
?>
<div class="blogEntries form" ng-controller="Blogs" ng-init="init(<?php echo h($dataJson) ?>)">
	<article>
		<h1><?php echo $blog['Blog']['name'] ?></h1>
		<div class="panel panel-default">

			<?php echo $this->NetCommonsForm->create(
				'BlogEntry',
				array(
					'inputDefaults' => array(
						'div' => 'form-group',
						'class' => 'form-control',
						'error' => false,
					),
					'div' => 'form-control',
					'novalidate' => true
				)
			);
			$this->NetCommonsForm->unlockField('Tag');
			?>
			<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>
			<?php echo $this->NetCommonsForm->hidden('Frame.id', array(
						'value' => Current::read('Frame.id'),
					)); ?>
			<?php echo $this->NetCommonsForm->hidden('Block.id', array(
				'value' => Current::read('Block.id'),
			)); ?>

			<div class="panel-body">

				<fieldset>

					<?php
					echo $this->TitleIcon->inputWithTitleIcon(
						'title',
						'BlogEntry.title_icon',
						array(
							'label' => __d('blogs', 'Title'),
							'required' => 'required',
						)
					);
					?>
					<?php echo $this->NetCommonsForm->wysiwyg('BlogEntry.body1', array(
						'label' => __d('blogs', 'Body1'),
						'required' => true,
					));?>

					<label><input type="checkbox" ng-model="writeBody2"/><?php echo __d('blogs', 'Write body2') ?>
					</label>

					<div class="form-group" ng-show="writeBody2">
					<?php echo $this->NetCommonsForm->wysiwyg('BlogEntry.body2', array(
						'label' => __d('blogs', 'Body2'),
					));?>
					</div>


					<?php
					echo $this->NetCommonsForm->input('publish_start',
						array(
							'type' => 'datetime',
							'required' => 'required',
							'label' => __d('blogs', 'Published datetime'),
							'childDiv' => 'form-inline',
							//'div' => 'form-inline'
						)
					);
					?>
					<?php echo $this->Category->select('BlogEntry.category_id', array('empty' => true)); ?>

					<?php echo $this->element(
						'Tags.tag_form',
						array(
							'tagData' => isset($this->request->data['Tag']) ? $this->request->data['Tag'] : array(),
							'modelName' => 'BlogEntry',
						)
					); ?>

				</fieldset>

				<hr/>
				<?php echo $this->Workflow->inputComment('BlogEntry.status'); ?>

			</div>

			<?php echo $this->Workflow->buttons('BlogEntry.status'); ?>

			<?php echo $this->NetCommonsForm->end() ?>
			<?php if ($isEdit && $isDeletable) : ?>
				<div  class="panel-footer" style="text-align: right;">
					<?php echo $this->NetCommonsForm->create('BlogEntry',
						array(
							'type' => 'delete',
							'url' => NetCommonsUrl::blockUrl(
								array('controller' => 'blog_entries_edit', 'action' => 'delete', 'frame_id' => Current::read('Frame.id')))
						)
					) ?>
					<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>

					<span class="nc-tooltip" tooltip="<?php echo __d('net_commons', 'Delete'); ?>">
						<button class="btn btn-danger" onClick="return confirm('<?php echo __d('net_commons', 'Deleting the %s. Are you sure to proceed?', __d('blogs', 'BlogEntry')) ?>')"><span class="glyphicon glyphicon-trash"> </span></button>


					</span>
					<?php echo $this->NetCommonsForm->end() ?>
				</div>
			<?php endif ?>

		</div>

		<?php echo $this->Workflow->comments(); ?>

	</article>

</div>


