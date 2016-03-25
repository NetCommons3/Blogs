<?php
/**
 * BlogSettings edit template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->element('Blocks.form_hidden'); ?>

<?php echo $this->Form->hidden('Blog.id'); ?>
<?php echo $this->Form->hidden('Blog.key'); ?>
<?php echo $this->Form->hidden('BlogSetting.id'); ?>
<?php echo $this->Form->hidden('BlogFrameSetting.id'); ?>
<?php echo $this->Form->hidden('BlogFrameSetting.frame_key'); ?>
<?php echo $this->Form->hidden('BlogFrameSetting.articles_per_page'); ?>
<?php //echo $this->Form->hidden('BlogFrameSetting.comments_per_page'); ?>

<?php echo $this->NetCommonsForm->input('Blog.name', array(
		'type' => 'text',
		'label' => __d('blogs', 'Blog name'),
	)); ?>

<?php echo $this->element('Blocks.public_type'); ?>

<?php echo $this->NetCommonsForm->inlineCheckbox('BlogSetting.use_comment', array(
			'label' => __d('blogs', 'Use comment')
	)); ?>

<?php echo $this->Like->setting('BlogSetting.use_like', 'BlogSetting.use_unlike');?>

<!-- TODO もっと整理できる？-->
<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->input('BlogSetting.use_sns', array(
				//'div' => false,
				'type' => 'checkbox',
				//'hiddenField' => false,
				'checked' => isset($blogSetting['use_sns']) ? (int)$blogSetting['use_sns'] : null
			)
		); ?>
		<?php //echo $this->Form->label('BlogSetting.use_sns', __d('blogs', 'Use sns')); ?>

		<?php echo $this->Form->checkbox('BlogSetting.use_sns', array(
				'div' => false,
				//'hiddenField' => false,
				'checked' => isset($blogSetting['use_sns']) ? (int)$blogSetting['use_sns'] : null
			)
		); ?>
		<?php echo $this->Form->label('BlogSetting.use_sns', __d('blogs', 'Use sns')); ?>
	</div>
</div>


<?php
echo $this->element('Categories.edit_form', array(
	'categories' => isset($categories) ? $categories : null
));
