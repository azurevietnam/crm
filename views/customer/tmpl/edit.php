<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();

$script = "
	jQuery(document).ready(function ($){
		$('#jform_type').change(function(){
			if($(this).val() == 1) {
				$('#image').css('display', 'none');
				$('#custom').css('display', 'block');
			} else {
				$('#image').css('display', 'block');
				$('#custom').css('display', 'none');
			}
		}).trigger('change');
	});";
// Add the script to the document head.
JFactory::getDocument()->addScriptDeclaration($script);
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'customer.cancel' || document.formvalidator.isValid(document.id('banner-form')))
		{
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_crm&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="banner-form" class="form-validate">

	
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Thông tin khách hàng', true)); ?>
		<div class="row-fluid">
			<div class="span8">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('name'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('name'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('email'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('email'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('mobile'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('mobile'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('address'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('address'); ?></div>
				</div>
				
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>
			</div>
			<div class="span3">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('category_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('category_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('type_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('type_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('company_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('company_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('avatar'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('avatar'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('birthday'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('birthday'); ?></div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>
