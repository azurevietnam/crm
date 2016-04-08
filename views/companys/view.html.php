<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * View class for a list of banners.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.6
 */
class CrmViewCompanys extends JViewLegacy
{
	protected $categories;

	protected $items;

	protected $pagination;

	protected $state;

	/**
	 * Method to display the view.
	 *
	 * @param   string  $tpl  A template file to load. [optional]
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @since   1.6
	 */
	public function display($tpl = null)
	{
		$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		CrmHelper::addSubmenu('companys');

		$this->addToolbar();

		// Include the component HTML helpers.
		JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

		$this->sidebar = JHtmlSidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolbar()
	{
		
		$canDo = JHelperContent::getActions('com_crm');
		$user = JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('Quản lý Công ty'), 'banners.png');

		if ($canDo->get('core.create'))
		{
			JToolbarHelper::addNew('company.add');
		}

		if (($canDo->get('core.edit')))
		{
			JToolbarHelper::editList('company.edit');
		}
		
		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('Bạn có chắc chắn muốn xóa?', 'companys.delete', 'Xóa');
		}
		
		/* if ($user->authorise('core.admin', 'com_crm'))
		{
			JToolbarHelper::preferences('com_crm');
		} */
		
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 *
	 * @since   3.0
	 */
	protected function getSortFields()
	{
		return array(
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.state' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_BANNERS_HEADING_NAME'),
			'a.sticky' => JText::_('COM_BANNERS_HEADING_STICKY'),
			'client_name' => JText::_('COM_BANNERS_HEADING_CLIENT'),
			'impmade' => JText::_('COM_BANNERS_HEADING_IMPRESSIONS'),
			'clicks' => JText::_('COM_BANNERS_HEADING_CLICKS'),
			'a.language' => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
