<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

JLoader::load('RedshopHelperAdminMail');
JLoader::load('RedshopHelperExtra_field');
JLoader::load('RedshopHelperUser');

class RedshopModelUser_detail extends JModel
{
	public $_id = null;

	public $_uid = null;

	public $_data = null;

	public $_table_prefix = null;

	public $_pagination = null;

	public $_copydata = null;

	public $_context = null;

	public function __construct()
	{
		$app = JFactory::getApplication();
		parent::__construct();

		$this->_table_prefix = '#__redshop_';
		$this->_context = 'order_id';

		$array = JRequest::getVar('cid', 0, '', 'array');
		$this->_uid = JRequest::getVar('user_id', 0);

		$limit = $app->getUserStateFromRequest($this->_context . 'limit', 'limit', $app->getCfg('list_limit'), 0);
		$limitstart = $app->getUserStateFromRequest($this->_context . 'limitstart', 'limitstart', 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		$this->setId((int) $array[0]);
	}

	public function setId($id)
	{
		$this->_id = $id;
		$this->_data = null;
	}

	public function &getData()
	{
		if ($this->_loadData())
		{
		}
		else
		{
			$this->_initData();
		}

		return $this->_data;
	}

	public function _loadData()
	{
		if (empty($this->_data))
		{
			$this->_uid = 0;
			$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info AS uf '
				. 'LEFT JOIN #__users as u on u.id = uf.user_id '
				. 'WHERE users_info_id="' . $this->_id . '" ';
			$this->_db->setQuery($query);
			$this->_data = $this->_db->loadObject();

			if (isset($this->_data->user_id))
			{
				$this->_uid = $this->_data->user_id;
			}

			if (count($this->_data) > 0 && !$this->_data->email)
			{
				$this->_data->email = $this->_data->user_email;
			}
			return (boolean) $this->_data;
		}
		return true;
	}

	public function _initData()
	{
		if (empty($this->_data))
		{
			$detail = new stdClass;

			$detail->users_info_id         = 0;
			$detail->user_id               = 0;
			$detail->id                    = 0;
			$detail->gid                   = null;
			$detail->name                  = null;
			$detail->username              = null;
			$detail->email                 = null;
			$detail->password              = null;
			$detail->usertype              = null;
			$detail->block                 = null;
			$detail->sendEmail             = null;
			$detail->registerDate          = null;
			$detail->lastvisitDate         = null;
			$detail->activation            = null;
			$detail->is_company            = null;
			$detail->firstname             = null;
			$detail->lastname              = null;
			$detail->contact_info          = null;
			$detail->address_type          = null;
			$detail->company_name          = null;
			$detail->vat_number            = null;
			$detail->tax_exempt            = 0;
			$detail->country_code          = null;
			$detail->state_code            = null;
			$detail->shopper_group_id      = null;
			$detail->published             = 1;
			$detail->address               = null;
			$detail->city                  = null;
			$detail->zipcode               = null;
			$detail->phone                 = null;
			$detail->requesting_tax_exempt = 0;
			$detail->tax_exempt_approved   = 0;
			$detail->approved              = 1;
			$detail->ean_number            = null;
			$detail->state_code_ST         = null;

			$info_id = JRequest::getVar('info_id', 0);
			$shipping = JRequest::getVar('shipping', 0);

			if ($shipping)
			{
				$query = 'SELECT * FROM ' . $this->_table_prefix . 'users_info AS uf '
					. 'LEFT JOIN #__users as u on u.id = uf.user_id '
					. 'WHERE users_info_id="' . $info_id . '" ';
				$this->_db->setQuery($query);
				$bill_data = $this->_db->loadObject();

				$detail->id = $detail->user_id = $this->_uid = $bill_data->user_id;
				$detail->email = $bill_data->user_email;
				$detail->is_company = $bill_data->is_company;
				$detail->company_name = $bill_data->company_name;
				$detail->vat_number = $bill_data->vat_number;
				$detail->tax_exempt = $bill_data->tax_exempt;
				$detail->shopper_group_id = $bill_data->shopper_group_id;
				$detail->requesting_tax_exempt = $bill_data->requesting_tax_exempt;
				$detail->tax_exempt_approved = $bill_data->tax_exempt_approved;
				$detail->ean_number = $bill_data->ean_number;
			}

			$this->_data = $detail;

			return (boolean) $this->_data;
		}

		return true;
	}

	public function storeUser($post)
	{

		$userhelper = new rsUserhelper;

		$shipping = isset($post["shipping"]) ? true : false;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;
		$post['user_email'] = $post['email1'] = $post['email'];


		$post['billisship'] = 1;

		if ($post['createaccount'])
		{
			$joomlauser = $userhelper->createJoomlaUser($post);
		}
		else
		{
			$joomlauser = $userhelper->updateJoomlaUser($post);
		}

		if (!$joomlauser)
		{
			return false;
		}

		$reduser = $userhelper->storeRedshopUser($post, $joomlauser->id, 1);

		return $reduser;
	}

	public function store($post)
	{
		$userhelper = new rsUserhelper;

		$shipping = isset($post["shipping"]) ? true : false;
		$post['createaccount'] = (isset($post['username']) && $post['username'] != "") ? 1 : 0;
		$post['user_email'] = $post['email1'] = $post['email'];

		if ($shipping)
		{
			$post['country_code_ST'] = $post['country_code'];
			$post['state_code_ST'] = $post['state_code'];
			$post['firstname_ST'] = $post['firstname'];
			$post['lastname_ST'] = $post['lastname'];
			$post['address_ST'] = $post['address'];
			$post['city_ST'] = $post['city'];
			$post['zipcode_ST'] = $post['zipcode'];
			$post['phone_ST'] = $post['phone'];

			$reduser = $userhelper->storeRedshopUserShipping($post);
		}
		else
		{
			$post['billisship'] = 1;
			$joomlauser = $userhelper->updateJoomlaUser($post);

			if (!$joomlauser)
			{
				return false;
			}
			$reduser = $userhelper->storeRedshopUser($post, $joomlauser->id, 1);
		}

		return $reduser;
	}

	public function delete($cid = array())
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);
			$query = 'DELETE FROM ' . $this->_table_prefix . 'users_info WHERE users_info_id IN ( ' . $cids . ' )';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function publish($cid = array(), $publish = 1)
	{
		if (count($cid))
		{
			$cids = implode(',', $cid);

			$query = 'UPDATE ' . $this->_table_prefix . 'users_info '
				. 'SET approved=' . intval($publish) . ' '
				. 'WHERE user_id IN ( ' . $cids . ' ) ';
			$this->_db->setQuery($query);

			if (!$this->_db->execute())
			{
				$this->setError($this->_db->getErrorMsg());

				return false;
			}
		}

		return true;
	}

	public function validate_user($user, $uid)
	{
		$query = "SELECT username FROM #__users WHERE username='" . $user . "' AND id !=" . $uid;
		$this->_db->setQuery($query);
		$users = $this->_db->loadObjectList();

		return count($users);
	}

	public function validate_email($email, $uid)
	{
		$query = "SELECT email FROM #__users WHERE email = '" . $email . "' AND id !=" . $uid;
		$this->_db->setQuery($query);
		$emails = $this->_db->loadObjectList();

		return count($emails);
	}

	public function userOrders()
	{
		$query = $this->_buildUserorderQuery();
		$list = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));

		return $list;
	}

	public function _buildUserorderQuery()
	{
		$query = "SELECT * FROM `" . $this->_table_prefix . "orders` "
			. "WHERE `user_id`='" . $this->_uid . "' "
			. "ORDER BY order_id DESC ";

		return $query;
	}

	public function getTotal()
	{
		if ($this->_id)
		{
			$query = $this->_buildUserorderQuery();
			$this->_total = $this->_getListCount($query);

			return $this->_total;
		}
	}

	public function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}
		return $this->_pagination;
	}
}
