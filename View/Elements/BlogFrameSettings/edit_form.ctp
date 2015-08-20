<?php
/**
 * blog block index template
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Ryo Ozawa <ozawa.ryo@withone.co.jp>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->Form->hidden('Frame.id', array(
	'value' => $frameId,
	)); ?>

<?php
if (isset($blogFrameSetting['id'])) {
	echo $this->Form->hidden('BlogFrameSetting.id', array(
		'value' => (int)$blogFrameSetting['id'],
	));
}
?>
<?php echo $this->Form->hidden('BlogFrameSetting.frame_key', array(
	'value' => $frameKey,
	)); ?>

<div class="row form-group">
	<div class="col-xs-12">
		<?php echo $this->Form->label(__d('blogs', 'Show articles per page')); ?>
	</div>
	<div class="col-xs-12">
		<?php echo $this->Form->select('BlogFrameSetting.posts_per_page',
				BlogFrameSetting::getDisplayNumberOptions(),
				array(
					//'label' => false,
					'type' => 'select',
					'class' => 'form-control',
					'value' => $blogFrameSetting['postsPerPage'],
					//'legend' => false,
					'empty' => false,
				)
			); ?>
	</div>
</div>

