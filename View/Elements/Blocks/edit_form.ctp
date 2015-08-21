<?php
/**
 * Blocks edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>
<?php echo $this->Form->hidden('Frame.id', array(
		'value' => $frameId,
	)); ?>

<?php echo $this->Form->hidden('Block.id', array(
		'value' => $block['id'],
	)); ?>

<?php echo $this->Form->hidden('Block.key', array(
		'value' => $block['key'],
	)); ?>

<?php echo $this->Form->hidden('Block.language_id', array(
		'value' => $languageId,
	)); ?>

<?php echo $this->Form->hidden('Block.room_id', array(
		'value' => $roomId,
	)); ?>

<?php echo $this->Form->hidden('Block.plugin_key', array(
		'value' => $this->params['plugin'],
	)); ?>

<?php echo $this->Form->hidden('Blog.id', array(
		'value' => isset($blog['id']) ? (int)$blog['id'] : null,
	)); ?>

<?php echo $this->Form->hidden('Blog.key', array(
		'value' => isset($blog['key']) ? $blog['key'] : null,
	)); ?>

<?php echo $this->Form->hidden('BlogSetting.id', array(
		'value' => isset($blogSetting['id']) ? (int)$blogSetting['id'] : null,
	)); ?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->input(
				'Blog.name', array(
					'type' => 'text',
					'label' => __d('blogs', 'Blog Name') . $this->element('NetCommons.required'),
					'error' => false,
					'class' => 'form-control',
					'autofocus' => true,
					'value' => (isset($blog['name']) ? $blog['name'] : '')
				)
			); ?>
	</div>

	<div class="col-xs-12">
		<?php echo $this->element(
			'NetCommons.errors', [
				'errors' => $this->validationErrors,
				'model' => 'Blog',
				'field' => 'name',
			]); ?>
	</div>
</div>

	<div class="row form-group">
		<div class="col-xs-12">
			<?php echo $this->Form->checkbox('BlogSetting.use_sns', array(
					'div' => false,
					//'hiddenField' => false,
					'checked' => isset($blogSetting['useSns']) ? (int)$blogSetting['useSns'] : null
				)
			); ?>
			<?php echo $this->Form->label('BlogSetting.use_sns', __d('blogs', 'Use sns')); ?>
		</div>
	</div>



	<div class="row form-group">
		<div class="col-xs-12">
			<?php echo $this->Form->checkbox('BlogSetting.use_comment', array(
					'div' => false,
					//'hiddenField' => false,
					'checked' => isset($blogSetting['useComment']) ? (int)$blogSetting['useComment'] : null
				)
			); ?>
			<?php echo $this->Form->label('BlogSetting.use_comment', __d('blogs', 'Use content comment')); ?>
		</div>
	</div>

	<div class="row form-group">
		<div class="col-xs-12">
			<?php echo $this->Form->checkbox('BlogSetting.use_like', array(
					'div' => false,
					'checked' => isset($blogSetting['useLike']) ? (int)$blogSetting['useLike'] :null,
					'ng-click' => 'useLike()'
				)
			); ?>

			<?php echo $this->Form->label('BlogSetting.use_like',
				'<span class="glyphicon glyphicon-thumbs-up"> </span> ' .
				__d('likes', 'Use like button')
			); ?>
		</div>

		<div class="col-xs-11 col-xs-offset-1">
			<?php echo $this->Form->checkbox('BlogSetting.use_unlike', array(
					'div' => false,
					'checked' => isset($blogSetting['useUnlike']) ? (int)$blogSetting['useUnlike'] :null,
					'ng-disabled' => ! (isset($blogSetting['useLike']) ? (int)$blogSetting['useLike'] : 0)
				)
			); ?>

			<?php echo $this->Form->label('BlogSetting.use_unlike',
				'<span class="glyphicon glyphicon-thumbs-down"> </span> ' .
				__d('likes', 'Use unlike button')
			); ?>
		</div>
	</div>


<?php
echo $this->element('Categories.edit_form', array(
	'categories' => isset($categories) ? $categories : null
));
