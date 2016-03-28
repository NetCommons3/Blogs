<?php
/**
 * Blog frame setting element
 *
 * @author Noriko Arai <arai@nii.ac.jp>
 * @author Shohei Nakajima <nakajimashouhei@gmail.com>
 * @link http://www.netcommons.org NetCommons Project
 * @license http://www.netcommons.org/license.txt NetCommons License
 * @copyright Copyright 2014, NetCommons Project
 */
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Frame.key'); ?>

<?php echo $this->NetCommonsForm->hidden('BlogFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('BlogFrameSetting.frame_key'); ?>

<?php echo $this->DisplayNumber->select('BlogFrameSetting.articles_per_page', array(
	'label' => __d('blogs', 'Show articles per page'),
	'unit' => array(
		'single' => __d('blogs', '%s article'),
		'multiple' => __d('blogs', '%s articles')
	),
)); ?>
<?php //echo $this->DisplayNumber->select('BlogFrameSetting.comments_per_page', array(
//	'label' => __d('blogs', 'Show comments per page'),
//	'unit' => array(
//		'single' => __d('blogs', '%s article'),
//		'multiple' => __d('blogs', '%s articles')
//	),
//));