<?php
/**
 * @copyright  Copyright (C) 2010-2012 redCOMPONENT.com. All rights reserved.
 * @license    GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 *
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

jimport('joomla.filesystem.file');

class newslettersubscrController extends JController
{
	function __construct( $default = array())
	{
		parent::__construct( $default );
	}

	function cancel()
	{
		$this->setRedirect( 'index.php' );
	}

	function display()
    {

		parent::display();
	}

	function importdata()
    {
        $post = JRequest::get ( 'post' );

		$option = JRequest::getVar ('option');

		$file = JRequest::getVar('file', 'array' , 'files', 'array');

		$model = $this->getModel('newslettersubscr');

		$filetype = strtolower(JFile::getExt($file['name']));

		$separator = JRequest::getVar('separator',",");

		if($filetype == 'csv'){

			$src = $file['tmp_name'];

			$dest = JPATH_ADMINISTRATOR.DS.'components/'.$option.'/assets'.DS.$file['name'];

			JFile::upload($src, $dest);

			$newsletter_id = $post['newsletter_id'];

			$row = 0;

			$handle = fopen($dest, "r");

		while (($data = fgetcsv($handle, 1000, $separator)) !== FALSE) {
				if($data[0]!="" && $data[1]!="")
				{
					if ($row != 0 ){
						$success = $model->importdata($newsletter_id,$data[0],$data[1]);
					}
				    $row++;
				}

			}

			fclose($handle);

			if($success){
				unlink($dest);
				$msg = JText::_('COM_REDSHOP_DATA_IMPORT_SUCCESS' );
				$this->setRedirect ( 'index.php?option=' . $option . '&view=newslettersubscr', $msg );
			}else{
				$msg = JText::_('COM_REDSHOP_ERROR_DATA_IMPORT' );
				$this->setRedirect ( 'index.php?option=' . $option . '&view=newslettersubscr&task=import_data', $msg );
			}
		}else{
			$msg = JText::_('COM_REDSHOP_FILE_EXTENTION_WRONG' );
			$this->setRedirect ( 'index.php?option=' . $option . '&view=newslettersubscr&task=import_data', $msg );
		}
    }
}
