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
//JHtml::_('formbehavior.chosen', 'select');

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
		if (task == 'order.cancel' || document.formvalidator.isValid(document.id('banner-form')))
		{
			Joomla.submitform(task, document.getElementById('banner-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_crm&view=order&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="banner-form" class="form-validate">

	
	<div class="form-horizontal">
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('Thông tin đơn hàng', true)); ?>
		<div class="row-fluid">
			<div class="span8">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('customer_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('customer_id'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('start_date'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('start_date'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('end_date'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('end_date'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('notify'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('notify'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
				</div>
			</div>
			<div class="span3">
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('state'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('state'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('created'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('created'); ?></div>
				</div>
				<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('created_by'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
				</div>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'services', JText::_('Chi tiết Đơn hàng', true)); ?>
		<div class="row-fluid">
			<div class="span5">
				<h3 style="margin:3px 0px;">Dịch vụ</h3>
				<small>Các dịch vụ, sản phẩm trong đơn hàng này.<br/>Click vào nút "Thêm" để thêm dữ liệu</small><hr style="margin:3px 0px"/>
				<table class="table table-striped table-services" style="width:100%">
					<thead>
					<tr>
						<th>Tên Dịch vụ - Sản phẩm</th>
						<th>Ghi chú</th>
						<th>Giá bán</th>
						<th>Xóa</th>
					</tr>
					</thead>
					<tbody>
					<?php if (isset($this->item->orderServices)) : ?>
					<?php foreach ($this->item->orderServices as $service):?>
						<script type="text/javascript">
							jQuery(document).ready(function(){ addService('<?php echo $service->service_id;?>','<?php echo $service->price;?>','<?php echo $service->description;?>'); });
						</script>
					<?php endforeach;?>
					<?php endif;?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2" style="font-weight: bold; text-align: right;">Tổng</td>
							<td colspan="2" class="table-services-total" style="font-weight: bold; text-align: right;"></td>
						</tr>
						<tr class="table-services-add">
							<td colspan="4">
							<div class="btn-wrapper pull-left">
								<button class="btn btn-small btn-success btn-add-service" onclick="return addService('','0','');">
								<span class="icon-new icon-white"></span>Thêm</button>
							</div>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<div class="span2"></div>
			<div class="span5">
				<h3 style="margin:3px 0px">Thanh toán</h3>
				<small>Đơn hàng có thể thanh toán nhiều đợt<br/>Click vào nút "Thêm" để thêm dữ liệu</small>
				<hr style="margin:3px 0px"/>
				<table class="table table-striped table-payments" style="width:100%">
					<thead><tr>
						<th>Ngày</th>
						<th>Số tiền</th>
						<th>Ghi chú</th>
						<th>Thanh toán</th>
						<th>Xóa</th>
					</tr></thead>
					
					<tbody>
					<?php if (isset($this->item->orderPayments)) : ?>
					<?php foreach ($this->item->orderPayments as $payment):?>
						<script type="text/javascript">
							jQuery(document).ready(function(){ 
								addPayment('<?php echo $payment->payment_date;?>', '<?php echo $payment->amount;?>','<?php echo $payment->description;?>','<?php echo $payment->state;?>'); });
						</script>
					<?php endforeach;?>
					<?php endif;?>
					<tr class="table-payment-add">
						<td colspan="5">
						<div class="btn-wrapper pull-left">
							<button class="btn btn-small btn-success btn-add-payment" onclick="return addPayment('', '0', '', '');">
							<span class="icon-new icon-white"></span>Thêm</button>
						</div>
						</td>
					</tr></tbody>
				</table>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>

	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<div class="add-row" style="display:none">
	<table>
	<tbody>
	<tr>
		<td><?php echo $this->form->getInput('services'); ?></td>
		<td><textarea name="jform[services][description][]" style="width:100px; hieght:40px;">description_value</textarea></td>
		<td><input type="text" name="jform[services][price][]" class="total_item" value="price_value" style="width:50px; text-align: right;"/></td>
		<td><a href="#" class="remove-row">Xóa</a></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="add-row-payment" style="display:none">
	<table>
	<tbody>
	<tr>
		<td><input type="text" name="jform[payments][payment_date][]" value="payment_date_value" style="width:80px" id="inputdate">
		<button id="img_inputdate" class="btn" type="button"><i class="icon-calendar"></i></button>
		</td>
		<td><input type="text" name="jform[payments][amount][]" value="amount_value" style="width:50px; text-align: right;"/></td>
		<td><textarea name="jform[payments][description][]" style="width:100px; hieght:40px;">description_value</textarea></td>
		<td><select name="jform[payments][state][]" style="width:140px;"><option value="0">Chưa thanh toán</option><option value="1">Đã thanh toán</option></select></td>
		<td><a href="#" class="remove-row">Xóa</a></td>
	</tr>
	</tbody>
	</table>
</div>

<script type="text/javascript" >
	var index = 0;
	jQuery(document).ready(function(){
		
	});

	// Sự kiện thêm dịch vụ
	function addService(service_id, price, description){
		html = jQuery('.add-row table tbody').html();
		html = html.replace('remove-row', 'remove-row remove-row-item');
		html = html.replace('"jform[services]"', '"jform[services][id][]"');
		html = html.replace('selected="selected"', '');
		
		html = html.replace('value="'+service_id+'"', 'value="'+service_id+'" selected="selected"');
		html = html.replace('price_value', price);
		html = html.replace('description_value', description);
		
		jQuery('.table-services tbody').append( html );
		setTotalService();
		setEvents();
		return false;
	}

	// Sự kiện thêm thanh toán
	function addPayment(payment_date, amount, description, state){
		index++;
		html = jQuery('.add-row-payment table tbody').html();
		html = html.replace('remove-row', 'remove-row remove-row-item');
		html = html.replace('inputdate', 'inputdate' + index);
		html = html.replace('img_inputdate', 'img_inputdate' + index);
		html = html.replace('selected="selected"', '');
		
		html = html.replace('payment_date_value', payment_date);
		html = html.replace('amount_value', amount);
		html = html.replace('description_value', description);
		html = html.replace('value="'+state+'"', 'value="'+state+'" selected="selected"');
		
		jQuery('.table-payment-add').before( html );
		
		Calendar.setup({
			// Id of the input field
			inputField: 'inputdate' + index,
			// Format of the input field
			ifFormat: "%Y-%m-%d",
			// Trigger for the calendar (button ID)
			button: 'img_inputdate' + index,
			// Alignment (defaults to "Bl")
			align: "Tl",
			singleClick: true,
			firstDay: 1
			});
		
		setEvents();
		return false;
	}

	// Tính tổng giá trị
	function setTotalService(){
		var total = 0;
		jQuery.each( jQuery('.table-services .total_item'), function(index, object) {
			total += parseInt(object.value);
		});
		
		jQuery('.table-services-total').html(total);
	}
	
	// Sự kiện xóa dòng dữ liệu
	function setEvents(){
		jQuery('.total_item').keyup(function(){
			setTotalService();
		});
		
		jQuery('.remove-row-item').click(function(){
			jQuery(this).parent().parent().remove();
			setTotalService();
			return false;
		});
	}
</script>
