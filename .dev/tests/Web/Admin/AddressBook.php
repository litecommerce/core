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
 *
 * @resource admin_address_book
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_AddressBook extends XLite_Web_Admin_AAdmin
{

    const ADD_BUTTON = '//button[@class="add-address main-button"]';

    const SAVE_BUTTON = "//div[@class='model-form-buttons']/div[@class='button submit']/button[@type='submit']";

    const DELETE_BUTTON = '//button[@class="delete-address "]';

    const CHANGE_BUTTON = "//button[@class='modify-address ']";



    /**
     * Test adding to address book
     *
     * @return void
     * @access public
     * @since  1.0.0
     */
    public function testAddressBookAdd()
    {
        // $this->markTestSkipped('Awaiting for new Selenuim version. Problems with JS confirmation dialogs');

        $this->logIn();

        $this->open('admin.php?target=address_book');

        $cnt = 10;
        while ($this->isElementPresent(self::DELETE_BUTTON)) {

            // Preventing infinite loop if something wrong with deleting
            if (0 > $cnt--) {
                break;
            }

            // Set next confirmation action to 'Ok'
            $this->chooseOkOnNextConfirmation();

            // Click 'Delete address' button
            $this->click(self::DELETE_BUTTON); 

            // Check for confirmation presented
            $this->assertConfirmation('Delete this address?');

            $this->waitForPageToLoad();
        }

        $this->assertTrue(0 < $cnt, 'Counter of default address deleteion iterations is less than zero');


        $this->assertElementPresent(self::ADD_BUTTON, 'No add to address book button');

        $this->click(self::ADD_BUTTON);

        // Wait for address popup
        $this->waitForCondition(
            "selenium.isElementPresent(\"//span[@class='ui-dialog-title' and contains(text(), 'Address details')]\")",
            30000,
            'No add address popup (1)'
        );

        $this->assertElementPresent('//span[@class="ui-dialog-title" and contains(text(), "Address details")]', 'No popup with address details form (1)');

        // Enter address and submit form
        $this->enterAddress('', 'test1');

        $this->click(self::SAVE_BUTTON);

        $this->waitForPageToLoad();

        // Check if new address is presented in the addresses list
        $this->assertElementPresent("//div[@class='address-box']/descendant::td[@class='address-text']/ul[@class='address-entry']/li[@class='address-text-street']/ul[@class='address-text']/li[@class='address-text-value' and contains(text(), 'test1')]", 'New address is not presented (test1)');

        // Set next confirmation action to 'Ok'
        $this->chooseOkOnNextConfirmation();

        // Click 'Delete address' button
        $this->click(self::DELETE_BUTTON); 

        // Check for confirmation presented
        $this->assertConfirmation('Delete this address?');

        $this->waitForPageToLoad();

        $this->waitForCondition(
            "!selenium.isElementPresent(\"//li[@class='address-text-value' and contains(text(), 'test1')]\")",
            30000,
            'Waiting for new address removing (test1) failed'
        );

        $this->assertElementNotPresent('//li[@class="address-text-value" and contains(text(), "test1")]', 'New address is still presented (test1)');

        $this->click(self::ADD_BUTTON);

        // Wait for address details popup
        $this->waitForCondition(
            "selenium.isElementPresent(\"//span[@class='ui-dialog-title' and contains(text(), 'Address details')]\")",
            30000,
            'No add address popup (2)'
        );

        $this->assertElementPresent('//span[@class="ui-dialog-title" and contains(text(), "Address details")]', 'No popup with address details form (2)');

        // Enter address and submit form
        $this->enterAddress('', 'test3');

        $this->click(self::SAVE_BUTTON);

        $this->waitForPageToLoad();

        // Check if new address is presented in the addresses list
        $this->assertElementPresent("//div[@class='address-box']/descendant::td[@class='address-text']/ul[@class='address-entry']/li[@class='address-text-street']/ul[@class='address-text']/li[@class='address-text-value' and contains(text(), 'test3')]", 'New address is not presented (test3)');

        $this->click(self::CHANGE_BUTTON);

        // Wait for address details popup
        $this->waitForCondition(
            "selenium.isElementPresent(\"//span[@class='ui-dialog-title' and contains(text(), 'Address details')]\")",
            30000,
            'No add address popup (3)'
        );

        $this->assertElementPresent('//span[@class="ui-dialog-title" and contains(text(), "Address details")]', 'No popup with address details form (3)');

        $id = $this->getJSExpression("jQuery('form.address-form input[name=\"address_id\"]').val()");

        $this->enterAddress($id, 'test5');

        $this->click(self::SAVE_BUTTON);

        $this->waitForPageToLoad();


        // Check if new address is presented in the addresses list
        $this->assertElementPresent("//div[@class='address-box']/descendant::td[@class='address-text']/ul[@class='address-entry']/li[@class='address-text-street']/ul[@class='address-text']/li[@class='address-text-value' and contains(text(), 'test5')]", 'Address is not updated (test5)');
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
