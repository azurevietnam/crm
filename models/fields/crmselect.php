<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package     Joomla.Administrator
 * @subpackage  com_users
 * @since       1.6
 */
class JFormFieldCrmSelect extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var        string
	 * @since   1.6
	 */
	protected $type = 'CrmSelect';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 * @since   1.6
	 */
	protected function getOptions()
	{
		$options = array();

		$db = JFactory::getDbo();
		$user = JFactory::getUser();
		$query = $db->getQuery(true)
			->select('a.'.$this->getAttribute('value', 'id').' AS value, a.'.$this->getAttribute('text', 'title').' AS text')
			->from($this->getAttribute('table', '').' AS a');
		
		if ($this->getAttribute('where', '')) {
			$query->where($this->getAttribute('where', ''));
		}
		
		// Get the options.
		$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}
		
		$select = new stdClass();
		$select->value = '';
		$select->text = JText::_($this->getAttribute('description', ''));
		$options = array_merge( array($select), $options);
		
		return $options;
	}
}
