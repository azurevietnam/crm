<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');

$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', 'com_banners.category');
$archived	= $this->state->get('filter.state') == 2 ? true : false;
$trashed	= $this->state->get('filter.state') == -2 ? true : false;
$params		= (isset($this->state->params)) ? $this->state->params : new JObject;
$saveOrder	= $listOrder == 'a.ordering';

if ($saveOrder)
{
	$saveOrderingUrl = 'index.php?option=com_banners&task=banners.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'articleList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);
}

$sortFields = $this->getSortFields();
?>
<script type="text/javascript">
	Joomla.orderTable = function()
	{
		table = document.getElementById("sortTable");
		direction = document.getElementById("directionTable");
		order = table.options[table.selectedIndex].value;
		if (order != '<?php echo $listOrder; ?>')
		{
			dirn = 'asc';
		}
		else
		{
			dirn = direction.options[direction.selectedIndex].value;
		}
		Joomla.tableOrdering(order, dirn, '');
	}
</script>
<script type="text/javascript" src="<?php echo JUri::root()?>administrator/components/com_crm/assets/draw.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo JUri::root()?>administrator/components/com_crm/assets/chart/dhtmlxchart.css"/>
<script src="<?php echo JUri::root()?>administrator/components/com_crm/assets/chart/dhtmlxchart.js"></script>
<script type="text/javascript">
	var dataOrders = [
		<?php if(count($this->orders)): $k=0; $max=1; $min=1; ?>
		<?php 
		foreach($this->orders as $month=>$order): 
		$k++;
		if ( $order->total > $max ) $max = $order->total;
		if ( $order->total < $min && $order->total != 0 ) $min = $order->total;
		?>
			{id:<?php echo $k;?>, sales:<?php echo $order->total;?>, year:'<?php echo $month;?>'}<?php if( $k != count($this->orders) ):?>,<?php endif;?>
		<?php endforeach;?>
		<?php endif; ?>
    ];
	window.onload = function(){
        var chartOrders =  new dhtmlXChart({
            view:"line",
            container:"chart_orders",
            value:"#sales#",
            tooltip:{
                template:"#sales#"
            },
            item:{
                borderColor: "#1293f8",
                color: "#ffffff"
            },
            line:{
                color:"#1293f8",
                width:3
            },
            xAxis:{
                template:"#year#"
            },
            offset:0,
            yAxis:{
                start:<?php echo $min-1;?>,
                end:<?php echo $max+10;?>,
                step:<?php echo round( $max/$min )+1;?>,
                template:function(obj){
                    return (obj%2?"":obj)
                }
            }
        });
        chartOrders.parse(dataOrders,"json");
    }

	var dataPayments = [
          		<?php if(count($this->payments)): $k=0; $max=1; $min=1; ?>
          		<?php 
          		foreach($this->payments as $month=>$payment): 
          		$k++;
          		if ( $payment->total > $max ) $max = $payment->total;
          		if ( $payment->total < $min && $payment->total != 0 ) $min = $payment->total;
          		?>
          			{id:<?php echo $k;?>, sales:<?php echo $payment->total;?>, year:'<?php echo $month;?>'}<?php if( $k != count($this->payments) ):?>,<?php endif;?>
          		<?php endforeach;?>
          		<?php endif; ?>
              ];
          jQuery(document).ready(function(){
                  var chartPayments =  new dhtmlXChart({
                      view:"line",
                      container:"chart_payments",
                      value:"#sales#",
                      tooltip:{
                          template:"#sales#",
                          backgroundColor:"blue"
                      },
                      item:{
                          borderColor: "#1293f8",
                          color: "#ffffff"
                      },
                      line:{
                          color:"#1293f8",
                          width:3
                      },
                      xAxis:{
                          template:"#year#"
                      },
                      offset:0,
                      yAxis:{
                          start:<?php echo $min-1;?>,
                          end:<?php echo $max+10;?>,
                          step:<?php echo round( $max/5 );?>,
                          template:function(obj){
                              return (obj%2?"":obj)
                          }
                      }
                  });
                  chartPayments.parse(dataPayments,"json");
		});
      
    </script>

<form action="<?php echo JRoute::_('index.php?option=com_crm&view=reports'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		<?php require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html/sidebar.php';?>
	</div>
	<div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		
		<table style="width: 100%; margin-top:0px;">
			<tr valign="top">
				<td style="width: 50%">
					<h3>Thống kê Đơn hàng</h3>
					<div id="chart_orders" style="width: 500px; height: 250px;"></div>
				</td>
				<td>
					<h3>Thống kê Tiền thu được</h3>
					<div id="chart_payments" style="width: 500px; height: 250px;"></div>
				</td>
			</tr>
			<tr valign="top">
				<td style="width: 50%">
					<h3>Đơn hàng sắp hết hạn</h3>
					<table style="width:90%" class="table table-striped table-services">
						<thead>
							<tr>
								<th>STT</th>
								<th>Khách hàng</th>
								<th>Mobile</th>
								<th>Ngày hết hạn</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($this->expiryOrders)) : ?>
								<?php foreach($this->expiryOrders as $key=>$order) : ?>
								<tr>
									<td><?php echo $key+1;?></td>
									<td><a href="index.php?option=com_crm&view=order&layout=edit&id=<?php echo $order->id;?>" target="_blank"><?php echo $order->c_name;?></a></td>
									<td><?php echo $order->c_mobile;?></td>
									<td><?php echo date('d-m-Y', strtotime($order->end_date));?></td>
								</tr>
								<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
				</td>
				<td>
					<h3>Các khoản tiền sắp đến đợt thu</h3>
					<table style="width:90%" class="table table-striped table-services">
						<thead>
							<tr>
								<th>STT</th>
								<th>Khách hàng</th>
								<th>Mobile</th>
								<th>Ngày dự kiến thanh toán</th>
							</tr>
						</thead>
						<tbody>
							<?php if(count($this->expiryPayments)) : ?>
								<?php foreach($this->expiryPayments as $key=>$payment) : ?>
								<tr>
									<td><?php echo $key+1;?></td>
									<td><a href="index.php?option=com_crm&view=order&layout=edit&id=<?php echo $payment->order_id;?>" target="_blank"><?php echo $payment->c_name;?></a></td>
									<td><?php echo $payment->c_mobile;?></td>
									<td><?php echo date('d-m-Y', strtotime($payment->payment_date));?></td>
								</tr>
								<?php endforeach;?>
							<?php endif;?>
						</tbody>
					</table>
				</td>
			</tr>
		</table>
	</div>
	
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<?php echo JHtml::_('form.token'); ?>
</form>
