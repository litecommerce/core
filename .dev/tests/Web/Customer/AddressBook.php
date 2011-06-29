<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * @category   LiteCommerce
 * @package    Tests
 * @subpackage Web
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_AddressBook extends XLite_Web_Customer_ACustomer
{

    const ADD_BUTTON = '//button[@class="add-address action"]';

    const SAVE_BUTTON = "//div[@class='model-form-buttons']/div[@class='button submit']/button[@type='submit']";

    const DELETE_BUTTON = '//div[@class="delete-action"]/button';

    const PROCEED_BUTTON = "//button[@class='button-proceed' and @type='submit']";

    const CHANGE_BUTTON = "//div[@class='change-action']/button[@type='button']";



    /**
     * Test adding to address book
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function testAddressBookAdd()
    {
        $this->logIn();

        $this->open('user/1/address-book');

        while ($this->isElementPresent(self::DELETE_BUTTON)) {

            $this->click(self::DELETE_BUTTON);

            $this->waitForLocalCondition(
                "jQuery('#ui-dialog-title-2').length > 0",
                10000,
                "No delete popup"
            );

            $this->clickAndWait(self::PROCEED_BUTTON);
        }

        $this->assertElementPresent(self::ADD_BUTTON, 'No add to address book button');

        $this->click(self::ADD_BUTTON);

        $this->waitForLocalCondition(
            "jQuery('#ui-dialog-title-2').length > 0",
            10000,
            "No add address popup"
        );  

        $this->enterAddress('', 'test1');

        $this->clickAndWait(self::SAVE_BUTTON);

        $this->click(self::DELETE_BUTTON); 

        $this->waitForLocalCondition(
            "jQuery('#ui-dialog-title-2').length > 0",
            10000,
            "No delete popup"
        );

        $this->clickAndWait(self::PROCEED_BUTTON);

        $this->assertElementNotPresent("//li[@class='address-text-cell address-text-street']/ul[@class='address-text']/li[@class='address-text-value' and text()='street-test1']", 'address was not removed');

        $this->click(self::ADD_BUTTON);

        $this->waitForLocalCondition(
            "jQuery('#ui-dialog-title-2').length > 0",
            10000,
            "No add address popup"
        );  

        $this->enterAddress('', 'test3');

        $this->clickAndWait(self::SAVE_BUTTON);

        $this->click(self::CHANGE_BUTTON);

        $this->waitForLocalCondition(
            "jQuery('#ui-dialog-title-2').length > 0",
            10000,
            "No change address popup"
        );

        $id = $this->getJSExpression("jQuery('form.address-form input[name=\"address_id\"]').val()");

        $this->enterAddress($id, 'test5');

        $this->clickAndWait(self::SAVE_BUTTON);

        $street = $this->getJSExpression("jQuery('.address-text-street .address-text-value').eq(0).text().trim()");

        $this->assertTrue('street-test5' == $street, 'No update: ' . $street);

    }

    private function enterAddress($id, $value = 'test', $number = '11111') 
    {
        foreach (
            array(
                'firstname',
                'lastname',
                'street',
                'city',
            ) as $name
        ) {
            $this->type("//input[@id='$id-$name']", $name . '-' . $value);
        }

        foreach (
            array(
                'zipcode',
                'phone',
            ) as $name
        ) { 
            $this->type("//input[@id='$id-$name']", $number);
        }   

        $this->select("//select[@name='$id" . "_state_id']", 'value=148');
    }

}
