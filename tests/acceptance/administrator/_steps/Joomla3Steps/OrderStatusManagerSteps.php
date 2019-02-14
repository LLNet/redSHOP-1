<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace AcceptanceTester;
/**
 * Class OrderStatusManagerSteps
 *
 * @package  AcceptanceTester
 *
 * @link     http://codeception.com/docs/07-AdvancedUsage#StepObjects
 *
 * @since    2.4
 */
class OrderStatusManagerSteps extends AdminManagerJoomla3Steps
{
	public function createOrderStatus($orderStatusName,$orderStatusCode)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
		$I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	public function createOrderStatusMissingName($orderStatusCode)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusCode, $orderStatusCode);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageNameFieldRequired, 30, \OrderStatusManagerPage::$selectorMissing);
	}

	public function createOrderStatusMissingCode($orderStatusName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->click(\OrderStatusManagerPage::$buttonNew);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $orderStatusName);
		$I->click(\OrderStatusManagerPage::$buttonSave);
		$I->waitForText(\OrderStatusManagerPage::$messageCodeFieldRequired, 30, \OrderStatusManagerPage::$selectorMissing);
	}

	public function editOrderStatus($orderStatusName, $changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($orderStatusName);
		$I->click(\OrderStatusManagerPage::$editButton);
		$I->waitForElement(\OrderStatusManagerPage::$orderstatusName, 30);
		$I->fillField(\OrderStatusManagerPage::$orderstatusName, $changeName);
		$I->click(\OrderStatusManagerPage::$buttonSaveClose);
		$I->waitForText(\OrderStatusManagerPage::$messageSaveSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	public function searchOrderStatus($orderStatusName)
	{
		$I = $this;
		$I->wantTo('Search the Order Status');
		$I->click(\OrderStatusManagerPage::$buttonReset);
		$I->fillField(\OrderStatusManagerPage::$filterSearch, $orderStatusName);
		$I->presskey(\OrderStatusManagerPage::$filterSearch, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN, \Facebook\WebDriver\WebDriverKeys::ENTER);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonCheckIn);
	}


	public function changeStatusUnpublish($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonUnpublish);
		$I->waitForText(\OrderStatusManagerPage::$messageUnpublishSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	public function changeStatusPublish($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonPublish);
		$I->waitForText(\OrderStatusManagerPage::$messagePublishSuccess, 30, \OrderStatusManagerPage::$selectorSuccess);
	}

	public function deleteOrderStatus($changeName)
	{
		$I = $this;
		$I->amOnPage(\OrderStatusManagerPage::$URL);
		$I->searchOrderStatus($changeName);
		$I->checkAllResults();
		$I->click(\OrderStatusManagerPage::$buttonDelete);
		$I->wantTo('Test with delete Order Status but then cancel');
		$I->cancelPopup();

		$I->wantTo('Test with delete Order Status then accept');
		$I->click(\OrderStatusManagerPage::$buttonDelete);
		$I->acceptPopup();
		$I->waitForText(\OrderStatusManagerPage::$messageDelete, 60, \OrderStatusManagerPage::$selectorSuccess);
		$I->dontSee($changeName);
	}
}