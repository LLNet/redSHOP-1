<?php
/**
 * @package     redSHOP
 * @subpackage  Controllers
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die ('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';

class voucher_detailController extends RedshopCoreController
{
    public function __construct($default = array())
    {
        parent::__construct($default);
        $this->registerTask('add', 'edit');
    }

    public function edit()
    {
        $this->input->set('view', 'voucher_detail');
        $this->input->set('layout', 'default');
        $this->input->set('hidemainmenu', 1);

        parent::display();
    }

    public function apply()
    {
        $this->save(1);
    }

    public function save($apply = 0)
    {
        $post               = $this->input->getArray($_POST);
        $option             = $this->input->getString('option', '');
        $cid                = $this->input->post->get('cid', array(0), 'array');
        $post['start_date'] = strtotime($post['start_date']);

        if ($post ['end_date'])
        {
            $post ['end_date'] = strtotime($post ['end_date']) + (23 * 59 * 59);
        }

        $post ['voucher_id'] = $cid[0];
        $model               = $this->getModel('voucher_detail');
        if ($post['old_voucher_code'] != $post['voucher_code'])
        {
            $code = $model->checkduplicate($post['voucher_code']);
            if ($code)
            {
                $msg = JText::_('COM_REDSHOP_CODE_IS_ALREADY_IN_USE');
                $this->app->redirect('index.php?option=' . $option . '&view=voucher_detail&task=edit&cid=' . $post ['voucher_id'], $msg);
            }
        }

        if ($row = $model->store($post))
        {
            $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_SAVED');
        }
        else
        {
            $msg = JText::_('COM_REDSHOP_ERROR_SAVING_VOUCHER_DETAIL');
        }

        if ($apply == 1)
        {
            $this->setRedirect('index.php?option=' . $option . '&view=voucher_detail&task=edit&cid[]=' . $row->voucher_id, $msg);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
        }
    }

    public function remove()
    {

        $option = $this->input->getString('option', '');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_DELETE'));
        }

        $model = $this->getModel('voucher_detail');
        if (!$model->delete($cid))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }
        $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_DELETED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
    }

    public function publish()
    {
        $option = $this->input->getString('option', '');

        $cid = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_PUBLISH'));
        }

        $model = $this->getModel('voucher_detail');
        if (!$model->publish($cid, 1))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_PUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
    }

    public function unpublish()
    {
        $option = $this->input->getString('option', '');
        $cid    = $this->input->post->get('cid', array(0), 'array');

        if (!is_array($cid) || count($cid) < 1)
        {
            throw new RuntimeException(JText::_('COM_REDSHOP_SELECT_AN_ITEM_TO_UNPUBLISH'));
        }

        $model = $this->getModel('voucher_detail');
        if (!$model->publish($cid, 0))
        {
            echo "<script> alert('" . $model->getError(true) . "'); window.history.go(-1); </script>\n";
        }

        $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_UNPUBLISHED_SUCCESSFULLY');
        $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
    }

    public function cancel()
    {
        $option = $this->input->getString('option', '');

        $msg = JText::_('COM_REDSHOP_VOUCHER_DETAIL_EDITING_CANCELLED');
        $this->setRedirect('index.php?option=' . $option . '&view=voucher', $msg);
    }
}
