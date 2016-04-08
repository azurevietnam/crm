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
 * Banner model.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_banners
 * @since       1.6
 */
class CrmModelOrder extends JModelAdmin
{
	/**
	 * @var    string  The prefix to use with controller messages.
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_CRM_CATEGORY';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_crm.order';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			
			$user = JFactory::getUser();

			return parent::canDelete($record);
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();

		return parent::canEditState($record);
	}

	/**
	 * Returns a JTable object, always creating it.
	 *
	 * @param   string  $type    The table type to instantiate. [optional]
	 * @param   string  $prefix  A prefix for the table class name. [optional]
	 * @param   array   $config  Configuration array for model. [optional]
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'Order', $prefix = 'CrmTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form. [optional]
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not. [optional]
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_crm.order', 'order', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('banner.id'))
		{
			// Existing record. Can only edit in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		}
		else
		{
			// New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('publish_up', 'disabled', 'true');
			$form->setFieldAttribute('publish_down', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');
			$form->setFieldAttribute('sticky', 'disabled', 'true');

			// Disable fields while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('publish_up', 'filter', 'unset');
			$form->setFieldAttribute('publish_down', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
			$form->setFieldAttribute('sticky', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 *
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_crm.edit.order.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		$this->preprocessData('com_crm.order', $data);

		return $data;
	}
	
	/**
	 * Method to get a single record.
	 *
	 * @param   integer    The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 */
	public function getItem($pk = null)
	{
		$pk = (!empty($pk)) ? $pk : (int) $this->getState($this->getName() . '.id');
	
		if (isset($this->_item[$pk])) {
			return $this->_item[$pk];
		}
		$item 	= parent::getItem($pk);
		$db 	= JFactory::getDbo();
		$user 	= JFactory::getUser();
	
		if ($item) {
				
			if ($item->id) {
				$query = $db->getQuery(true);
				$query->select('*')
						->from('#__crm_order_service')
						->where('order_id = '.$item->id)
						->order('id ASC');
				$db->setQuery($query);
				$item->orderServices = $db->loadObjectList();
				
				$query = $db->getQuery(true);
				$query->select('*')
						->from('#__crm_order_payment')
						->where('order_id = '.$item->id)
						->order('id ASC');
				$db->setQuery($query);
				$item->orderPayments = $db->loadObjectList();
			}
		}
	
		$this->_item[$pk] = $item;
	
		return $item;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */

	public function save($data)
	{
		$app = JFactory::getApplication();
		$db	= JFactory::getDbo();
		
		if (parent::save($data))
		{
			$id = (int) $this->getState($this->getName() . '.id');
			$db->setQuery('DELETE FROM #__crm_order_service WHERE order_id = '.$id);
			$db->execute();
			
			$db->setQuery('DELETE FROM #__crm_order_payment WHERE order_id = '.$id);
			$db->execute();
			
			if ( isset($data['services']) && isset($data['services']['id']) ) {
				$services = $data['services'];
				
				foreach($services['id'] as $key=>$serviceId) {
					$serviceObject = new stdClass();
					
					$serviceObject->order_id = $id;
					$serviceObject->customer_id = $data['customer_id'];
					$serviceObject->service_id 	= $serviceId;
					$serviceObject->price 		= $services['price'][$key];
					$serviceObject->description = $services['description'][$key];
					
					$db->insertObject('#__crm_order_service', $serviceObject, 'id');
				}
			}
			
			if ( isset($data['payments']) && isset($data['payments']['payment_date']) ) {
				$payments = $data['payments'];
				
				foreach($payments['payment_date'] as $key=>$paymentDate) {
					$paymentObject = new stdClass();
					
					$paymentObject->order_id = $id;
					$paymentObject->customer_id 	= $data['customer_id'];
					$paymentObject->amount 			= $payments['amount'][$key];
					$paymentObject->payment_date 	= $paymentDate;
					$paymentObject->description 	= $payments['description'][$key];
					$paymentObject->state		 	= $payments['state'][$key];
						
					$db->insertObject('#__crm_order_payment', $paymentObject, 'id');
				}
			}
			
			return true;
		}

		return false;
	}
}
