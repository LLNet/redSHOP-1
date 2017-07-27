<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManufacturerManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class ManufacturerManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=manufacturer';

	public static $detailsTab = "//a[contains(text(), 'Details')]";

	public static $manufacturerName = "//input[@id='manufacturer_name']";

	public static $manufacturerSuccessMessage = 'Manufacturer Detail Saved';

	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $manufacturerStatePath = "//div[@id='editcell']/div[2]/table/tbody/tr/td[5]/a";

	public static $xpathName="//div[@id='editcell']/div[2]/table/tbody/tr/td[2]/a";


	public static $productPerPage="//input[@id='product_per_page']";

    //button

    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $unpublishButton = "Unpublish";

    public static $publishButton = "Publish";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $saveNewButton = "Save & New";

    public static $cancelButton = "Cancel";

    public static $checkInButton = "Check-in";

    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';

}
