<?php
/**
 * @package     Redshop
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.viewlegacy');

/**
 * Base view.
 *
 * @package     Redshob.Libraries
 * @subpackage  View
 * @since       1.5
 */
class RedshopViewAdmin extends JViewLegacy
{
	/**
	 * Layout used to render the component
	 *
	 * @var  string
	 */
	protected $componentLayout = 'component.admin';

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a Error object.
	 */
	public function display($tpl = null)
	{
		$render = RedshopLayoutHelper::render(
			$this->componentLayout,
			array(
				'view' => $this,
				'tpl' => $tpl
			)
		);

		if ($render instanceof Exception)
		{
			return $render;
		}

		echo $render;

		return true;
	}
}