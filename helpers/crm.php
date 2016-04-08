<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Content component helper.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_content
 * @since       1.6
 */
class CrmHelper extends JHelperContent
{
	public static $extension = 'com_content';

	/**
	 * Configure the Linkbar.
	 *
	 * @param   string  $vName  The name of the active view.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	public static function addSubmenu($vName)
	{
		JHtmlSidebar::addEntry(
			JText::_('Báo cáo'),
			'index.php?option=com_crm&view=reports',
			$vName == 'reports');
		
		JHtmlSidebar::addEntry(
			JText::_('Đơn đặt hàng'),
			'index.php?option=com_crm&view=orders',
			$vName == 'orders'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('Khách hàng'),
			'index.php?option=com_crm&view=customers',
			$vName == 'customers'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('Công ty'),
			'index.php?option=com_crm&view=companys',
			$vName == 'companys'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('Phân loại'),
			'index.php?option=com_crm&view=types',
			$vName == 'types'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('Ngành nghề'),
			'index.php?option=com_crm&view=categorys',
			$vName == 'categorys'
		);
		
		JHtmlSidebar::addEntry(
			JText::_('Hàng hóa - Dịch vụ'),
			'index.php?option=com_crm&view=services',
			$vName == 'services');
		
		JHtmlSidebar::addEntry(
			JText::_('Hướng dẫn - Giới thiệu'),
			'index.php?option=com_crm&view=about',
			$vName == 'about');
	}

	/**
	 * Applies the content tag filters to arbitrary text as per settings for current user group
	 *
	 * @param   text  $text  The string to filter
	 *
	 * @return  string  The filtered string
	 *
	 * @deprecated  4.0  Use JComponentHelper::filterText() instead.
	*/
	public static function filterText($text)
	{
		JLog::add('ContentHelper::filterText() is deprecated. Use JComponentHelper::filterText() instead.', JLog::WARNING, 'deprecated');

		return JComponentHelper::filterText($text);
	}
}
