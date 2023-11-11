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
foreach ([1, 5, 10, 20] as $value) {
	if ($value === 1) {
		$unitLabel = __d('blogs', '%s article');
	} else {
		$unitLabel = __d('blogs', '%s articles');
	}
	$options[$value] = sprintf($unitLabel, $value);
}
?>

<?php echo $this->NetCommonsForm->hidden('Frame.id'); ?>
<?php echo $this->NetCommonsForm->hidden('Frame.key'); ?>

<?php echo $this->NetCommonsForm->hidden('BlogFrameSetting.id'); ?>
<?php echo $this->NetCommonsForm->hidden('BlogFrameSetting.frame_key'); ?>

<?php echo $this->DisplayNumber->select('BlogFrameSetting.articles_per_page', array(
	'label' => __d('net_commons', 'Display the number of each page'),
	'options' => $options,
)); ?>
<?php //echo $this->DisplayNumber->select('BlogFrameSetting.comments_per_page', array(
//	'label' => __d('blogs', 'Show comments per page'),
//	'unit' => array(
//		'single' => __d('blogs', '%s article'),
//		'multiple' => __d('blogs', '%s articles')
//	),
//));
