<?php
/**
 * @package     redSHOP
 * @subpackage  Views
 *
 * @copyright   Copyright (C) 2008 - 2012 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die('Restricted access');

class orderreddesignVieworderreddesign extends JViewLegacy
{
    public function display($tpl = null)
    {
        global $context;

        $app = JFactory::getApplication();

        require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'order.php');
        $order_function = new order_functions();

        $document = JFactory::getDocument();
        $document->setTitle(JText::_('COM_REDSHOP_ORDER'));

        JToolBarHelper::title(JText::_('COM_REDSHOP_ORDER_MANAGEMENT'), 'redshop_order48');
        JToolBarHelper::custom('allstatus', 'save.png', 'save_f2.png', 'Change Status to All', true);
        JToolBarHelper::custom('export_data', 'save.png', 'save_f2.png', 'Export Data', false);
        JToolBarHelper::deleteList();

        $uri = JFactory::getURI();

        $filter_order     = $app->getUserStateFromRequest($context . 'filter_order', 'filter_order', ' cdate ');
        $filter_order_Dir = $app->getUserStateFromRequest($context . 'filter_order_Dir', 'filter_order_Dir', '');
        $filter_status    = $app->getUserStateFromRequest($context . 'filter_status', 'filter_status', '', 'word');

        $lists['order']     = $filter_order;
        $lists['order_Dir'] = $filter_order_Dir;
        $orders             = $this->get('Data');
        $pagination         = $this->get('Pagination');

        $lists['filter_status'] = $order_function->getstatuslist('filter_status', $filter_status, 'class="inputbox" size="1" onchange="document.adminForm.submit();"');

        $this->user = JFactory::getUser();
        $this->assignRef('lists', $lists);
        $this->assignRef('orders', $orders);
        $this->assignRef('pagination', $pagination);
        $this->request_url = $uri->toString();

        parent::display($tpl);
    }
}
