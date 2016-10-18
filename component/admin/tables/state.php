<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Table
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * State table
 *
 * @package     RedSHOP.Backend
 * @subpackage  Table.State
 * @since       2.0.0.2.2
 */
class RedshopTableState extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  $db  Database driver object.
	 *
	 * @since  11.1
	 */
	public function __construct($db)
	{
		parent::__construct('#__redshop_state', 'id', $db);
	}

	/**
	 * Function check
	 * 
	 * @return boolean
	 */
	public function check()
	{
		$db = JFactory::getDbo();

		$query = $db->getQuery(true);
		$query->select([$db->qn('id'), $db->qn('state_3_code')])
			->from($db->qn('#__redshop_state'))
			->where(
				$db->qn('state_3_code') . ' = ' . $db->q($this->state_3_code)
				. ' AND ' . $db->qn('id') . ' != ' . $db->q($this->id)
				. ' AND ' . $db->qn('country_id') . ' = ' . $db->q($this->country_id)
				);

		$db->setQuery($query);

		$xid = intval($db->loadResult());

		if ($xid)
		{
			$this->_error = JText::_('COM_REDSHOP_STATE_CODE3_ALREADY_EXISTS');
			JError::raiseWarning('', $this->_error);

			return false;
		}
		else
		{
			$query = $db->getQuery(true);

			$query->select([$db->qn('id'), $db->qn('state_3_code'), $db->qn('state_2_code')])
			->from($db->qn('#__redshop_state'))
			->where(
				$db->qn('state_2_code') . ' = ' . $db->q($this->state_2_code)
				. ' AND ' . $db->qn('id') . ' != ' . $db->q($this->id)
				. ' AND ' . $db->qn('country_id') . ' = ' . $db->q($this->country_id)
				);

			$db->setQuery($query);
			$xid = intval($db->loadResult());

			if ($xid)
			{
				$this->_error = JText::_('COM_REDSHOP_STATE_CODE2_ALREADY_EXISTS');
				JError::raiseWarning('', $this->_error);

				return false;
			}
		}

		return true;
	}
}
