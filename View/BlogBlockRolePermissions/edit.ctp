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

<div class="modal-body">
	<?php echo $this->element('NetCommons.setting_tabs', $settingTabs); ?>

	<div class="tab-content">
		<?php echo $this->element('Blocks.setting_tabs', $blockSettingTabs); ?>

		<?php echo $this->element('Blocks.edit_form', array(
				'controller' => 'BlogBlockRolePermission',
				'action' => 'edit' . '/' . $frameId . '/' . $blockId,
				'callback' => 'Blogs.BlockRolePermissions/edit_form',
				'cancelUrl' => '/blogs/blog_blocks/index/' . $frameId,
			)); ?>
	</div>
</div>
