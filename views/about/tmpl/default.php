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
<form action="<?php echo JRoute::_('index.php?option=com_crm&view=types'); ?>" method="post" name="adminForm" id="adminForm">
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
		<?php require_once JPATH_COMPONENT_ADMINISTRATOR.'/helpers/html/sidebar.php';?>
	</div>
	<div id="j-main-container" class="span10">
		<?php
		// Search tools bar
		//echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		<table style="width:100%;">
			<tr valign="top">
				<td width="50%">
					<h3>Hướng dẫn cài đặt, sử dụng</h3>
					<iframe width="400" height="200" src="//www.youtube.com/embed/Gx-yS7SNQVo" frameborder="0" allowfullscreen></iframe>
				</td>
				<td>
					<h3>Phần mềm quản lý khách hàng</h3>
					Phiên bản: 1.0<br/>
					Tác giả: Chu văn Vinh - 0978.999.256<br/><br/><br/>
					
					<strong>Mọi chi tiết xin vui lòng liên hệ</strong><br/><br/>
					<img src="http://thietkewebphp.net/templates/digitalstar/css/images/logo.png" style="float:left; margin-right:10px; width:180px; margin-bottom: 10px;"/>
					<strong>Trung tâm phát triển CNTT Việt Nam</strong><br/>
					Địa chỉ: 230/118/26 Định Công Thượng - Hà Nội<br/>
					Điện thoại: Mr Vinh - 0978.999.256<br/>
					Website: <a href="http://thietkewebphp.net" target="_blank">http://thietkewebphp.net</a>
				</td>
			</tr>
		</table>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
