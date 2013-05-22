
<?php
/**
 * @version		$Id: edit_metadata.php 2012-06-28 09:22:27
 * @subpackage	com_backup
 * @author		woondroo
 * @email		wengebin@hotmail.com
 */

defined('_JEXEC') or die;
?>
<ul class='adminformlist'>
	<li><?php echo $this->form->getLabel('metadesc'); ?>
	<?php echo $this->form->getInput('metadesc'); ?></li>

	<li><?php echo $this->form->getLabel('metakey'); ?>
	<?php echo $this->form->getInput('metakey'); ?></li>

	<?php foreach($this->form->getGroup('metadata') as $field): ?>
		<?php if ($field->hidden): ?>
			<li><?php echo $field->input; ?></li>
		<?php else: ?>
			<li><?php echo $field->label; ?>
			<?php echo $field->input; ?></li>
		<?php endif; ?>
	<?php endforeach; ?>
</ul>