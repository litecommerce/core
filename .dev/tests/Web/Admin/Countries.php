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
 * @since      3.0.0
 */

require_once __DIR__ . '/AAdmin.php';

class XLite_Web_Admin_Countries extends XLite_Web_Admin_AAdmin
{
    /**
     * Buttons for Countries managing
     */
    const UPDATE_BUTTON = '//span[text()="Update"]/../../button[@type="submit" and @class="main-button"]';
    const DELETE_BUTTON = '//span[text()="Delete selected"]/../../button[@type="button"]';
    const ADD_BUTTON    = '//span[text()="Add new"]/../../button[@type="submit"]';



    /**
     * Add form inputs
     */
    const ADD_CODE      = '//input[@type="text" and @name="code" and @value=""]';
    const ADD_COUNTRY   = '//input[@type="text" and @name="country" and @value=""]';




    public function testCountriesManage()
    {
        $this->logIn();

        $this->open('admin.php?target=countries');

        $this->assertElementPresent(
            $this->getCountryLabel('ZW'),
            'No ZW label!'
        );

        $this->type(self::ADD_CODE, 'ZZ');
        $this->type(self::ADD_COUNTRY, 'Test country');

        $this->clickAndWait(self::ADD_BUTTON);

        $this->assertElementPresent(
            $this->getCountryLabel('ZZ'),
            'No ZZ label!'
        );

        $this->type('//input[@type="text" and @name="countries[ZZ][country]"]', 'Test country2');

        $this->clickAndWait(self::UPDATE_BUTTON);

        $this->assertElementPresent(
            '//input[@type="text" and @value="Test country2" and @name="countries[ZZ][country]"]',
            'No update for ZZ label'
        );

        $this->check('//input[@type="checkbox" and @name="delete_countries[]" and @value="ZZ"]');

        $this->clickAndWait(self::DELETE_BUTTON);

        $this->assertElementNotPresent(
            $this->getCountryLabel('ZZ'),
            'ZZ label present!!'
        );


    }

    protected function getCountryLabel($code)
    {
        return '//a[@title="Click here to view states of country" and @href="admin.php?target=states&country_code=' . $code . '" and text()="' . $code . '"]';
    }


}
