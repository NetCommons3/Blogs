<?php echo $this->Html->script(
	'/blogs/js/blogs.js',
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
); ?>
<?php echo $this->element('NetCommons.datetimepicker'); ?>
<?php
// 編集用
echo $this->Html->script(
	array(
		'/tags/js/tags.js',
		'/blogs/js/blogs_entry_edit.js',
		'/net_commons/js/wysiwyg.js',
	),
	array(
		'plugin' => false,
		'once' => true,
		'inline' => false
	)
);
?>
<?php
$dataJson = json_encode(
	$this->NetCommonsTime->toUserDatetimeArray($this->request->data, array('publish_start'))
);
?>
<div class="blogEntries form" ng-controller="Blogs" ng-init="init(<?php echo h($dataJson) ?>)">
	<article>
		<h1>BLOG</h1>
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
					'novalidate' => true,
					'type' => 'file',
				)
			);
			$this->NetCommonsForm->unlockField('Tag');
			?>
			<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>
			<?php echo $this->NetCommonsForm->input('key', array('type' => 'hidden')); ?>
			<!--		--><?php //echo $this->Form->hidden('Frame.id', array(
			//			'value' => Current::read('Frame.id'),
			//		)); ?>

			<div class="panel-body">

				<fieldset>

					<?php
					echo $this->NetCommonsForm->input(
						'title',
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
					// TODO Files用
					if(isset($this->request->data['UploadFile'])){
						foreach($this->request->data['UploadFile'] as $key => $uploadFile){
							echo $this->NetCommonsForm->input('UploadFile.' . $key . '.id', ['type' => 'hidden']);
							echo $this->NetCommonsForm->input('UploadFile.' . $key . '.field_name', ['type' => 'hidden']);
						}
						$originalNames = Hash::combine($this->request->data['UploadFile'], '{n}.field_name', '{n}.original_name');
					}
					?>
					<?php
					$fieldName = 'photo';
					$modelName = $this->NetCommonsForm->Form->defaultModel;
					$inputFieldName = $modelName . '.' . $fieldName;
					echo $this->NetCommonsForm->input($inputFieldName, ['type' => 'file']);
					if(isset($originalNames[$fieldName])){
						echo $originalNames[$fieldName];
						echo $this->NetCommonsForm->checkbox($inputFieldName . '.remove', ['type' => 'checkbox', 'div' => false, 'error' => false]);
						echo $this->Form->label($inputFieldName . '.remove', '削除');
					}
					?>

					<?php
					$fieldName = 'pdf';
					$modelName = $this->NetCommonsForm->Form->defaultModel;
					$inputFieldName = $modelName . '.' . $fieldName;
					echo $this->NetCommonsForm->input($inputFieldName, ['type' => 'file']);
					if(isset($originalNames[$fieldName])){
						echo $originalNames[$fieldName];
						echo $this->NetCommonsForm->checkbox($inputFieldName . '.remove', ['type' => 'checkbox', 'div' => false, 'error' => false]);
						echo $this->Form->label($inputFieldName . '.remove', '削除');
					}
					?>

					<?php
					echo $this->NetCommonsForm->input('publish_start',
						array(
							'type' => 'datetime',
							'required' => 'required',
							'label' => __d('blogs', 'Published datetime')));
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

			<div class="panel-footer" style="text-align: center">
				<?php echo $this->Workflow->buttons('BlogEntry.status'); ?>
			</div>

			<?php echo $this->NetCommonsForm->end() ?>
			<?php if ($isEdit && $isDeletable) : ?>
				<div  class="panel-footer" style="text-align: right;">
					<?php echo $this->NetCommonsForm->create('BlogEntry',
						array(
							'type' => 'delete',
							'url' => $this->NetCommonsHtml->url(
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


