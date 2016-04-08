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
 * View to edit a banner.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.5
 */
class CrmViewOrder extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	/**
	 * Display the view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		$this->addToolbar();
		
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
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);

		// Since we don't track these assets at the item level, use the category id.
		$canDo		= JHelperContent::getActions('com_crm');

		JToolbarHelper::title($isNew ? JText::_('Thêm mới') : JText::_('Sửa'), 'bookmark banners');

		// If not checked out, can save the item.
		if ( ($canDo->get('core.edit') || $canDo->get('core.create')))
		{
			JToolbarHelper::apply('order.apply');
			JToolbarHelper::save('order.save');
			
		}

		if (empty($this->item->id))
		{
			JToolbarHelper::cancel('order.cancel');
		}
		else
		{
			JToolbarHelper::cancel('order.cancel', 'JTOOLBAR_CLOSE');
		}
		
	}
}
