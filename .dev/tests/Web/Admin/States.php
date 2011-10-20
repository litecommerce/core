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

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_States extends XLite_Web_Admin_AAdmin
{

    const ADD_STATE_CODE_INPUT  = "//input[@type='text' and @name='code']";
    const ADD_STATE_INPUT       = "//input[@type='text' and @name='state']";
    const ADD_STATE_BUTTON      = "//input[@type='hidden' and @name='action' and @value='add']/../table/tbody/tr/td/button[@type='submit']";
    const DELETE_BUTTON         = "//span[text()='Delete selected']/../../button[@type='button']";

    const COUNTRY_SELECTOR = "//form[@method='get' and @name='select_country_form']/select[@name='country_code']";

    const CHECKBOX_TEST1 = "//input[@type='text' and @value='TEST1']/../../td/input[@class='state_ids' and @type='checkbox' and @name='delete_states[]']";
    const CHECKBOX_TEST2 = "//input[@type='text' and @value='TEST2']/../../td/input[@class='state_ids' and @type='checkbox' and @name='delete_states[]']";

    protected function setUp()
    {
        parent::setUp();

    }

    protected function tearDown()
    {
        parent::tearDown();
    }


    public function testStatesManage()
    {
        $this->logIn();

        $this->open('admin.php?target=states');

        $this->assertElementPresent(
            $this->getOptionCountrySelected('US', 'United States'),
            'US is not selected as country'
        );

        $this->select(
            self::COUNTRY_SELECTOR,
            'Zimbabwe'
        );

        $this->waitForPageToLoad(10000, 'Change country load');

        $this->assertElementPresent(
            $this->getOptionCountrySelected('ZW', 'Zimbabwe'),
            'ZW is not selected as country'
        );

        $this->type(self::ADD_STATE_CODE_INPUT, 'TT1');
        $this->type(self::ADD_STATE_INPUT, 'TEST1');

        $this->clickAndWait(self::ADD_STATE_BUTTON);

        $this->type(self::ADD_STATE_CODE_INPUT, 'TT2');
        $this->type(self::ADD_STATE_INPUT, 'TEST2');

        $this->clickAndWait(self::ADD_STATE_BUTTON);

        $this->assertElementPresent(
            "//input[@type='text' and @value='TEST1']",
            "Test1 state was not added"
        );

        $this->assertElementPresent(
            "//input[@type='text' and @value='TEST2']",
            "Test2 state was not added"
        );

        $this->click(
            "//input[@id='select_states' and @type='checkbox']"
        );

        $this->assertEquals($this->isChecked(self::CHECKBOX_TEST1), "First checkbox is not checked");
        $this->assertEquals($this->isChecked(self::CHECKBOX_TEST2), "Second checkbox is not checked");

        $this->click(
            "//input[@id='select_states' and @type='checkbox']"
        );

        $this->assertEquals(!$this->isChecked(self::CHECKBOX_TEST1), "First checkbox is checked");
        $this->assertEquals(!$this->isChecked(self::CHECKBOX_TEST2), "Second checkbox is checked");

        $this->check(
            self::CHECKBOX_TEST1
        );

        $this->check(
            self::CHECKBOX_TEST2
        );

        $this->clickAndWait(self::DELETE_BUTTON);

        $this->assertElementNotPresent(
            "//input[@type='text' and @value='TEST1']",
            "Test1 state was not removed"
        );

        $this->assertElementNotPresent(
            "//input[@type='text' and @value='TEST2']",
            "Test2 state was not removed"
        );

    }


    protected function getOptionCountrySelected($countryCode, $country)
    {
        return "//form[@method='get' and @name='select_country_form']/select[@name='country_code']/option[@selected='selected' and @value='$countryCode' and text()='$country']";
    }
}
