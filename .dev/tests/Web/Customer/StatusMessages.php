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
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

require_once __DIR__ . '/ACustomer.php';

class XLite_Web_Customer_StatusMessages extends XLite_Web_Customer_ACustomer
{

    public function testUserLoginErrorMessage()
    {

        $this->open('user');

        $this->submit("css=form#user-login");
        $this->waitForPageToLoad();

        $this->assertElementPresent(
            "css=#status-messages",
            "A container for status messages is missing"
        );

        $this->assertJqueryPresent(
            "#status-messages li.error:visible",
            "Error message has not appeared"
        ); 

    }

    public function testRecoveryPassStatusMessage()
    {

        $this->open('user/password');

        $this->type("css=form#user-pass input#edit-name", "master");
        $this->submit("css=form#user-pass");
        $this->waitForPageToLoad();

        $this->assertElementPresent(
            "css=#status-messages",
            "A container for status messages is missing"
        );

        $this->assertJqueryPresent(
            "#status-messages li.status:visible",
            "Error message has not appeared"
        ); 

    }

    public function testJavaScriptMessages()
    {
        $this->open('store/main');

        $this->assertJqueryNotPresent(
            "#status-messages li",
            "The container displays a wrong data"
        ); 

        $this->throwMessage('testError', 'error');
        $this->assertJqueryPresent(
            "#status-messages li.error:contains(testError)",
            "An error message is not displayed"
        ); 

        $this->throwMessage('testWarning', 'warning');
        $this->assertJqueryPresent(
            "#status-messages li.warning:contains(testWarning)",
            "A warning message is not displayed"
        ); 

        $this->throwMessage('testInfo', 'info');
        $this->assertJqueryPresent(
            "#status-messages li.status:contains(testInfo)",
            "A status message is not displayed"
        ); 

        sleep(10);

        $this->assertJqueryNotPresent(
            "#status-messages li.status:contains(testInfo)",
            "A status message is still displayed after 10 seconds"
        ); 
        $this->assertJqueryPresent(
            "#status-messages li.error:contains(testError)",
            "An error message is not displayed after 10 seconds"
        ); 
        $this->assertJqueryPresent(
            "#status-messages li.warning:contains(testWarning)",
            "A warning message is not displayed after 10 seconds"
        ); 

        $this->click("css=#status-messages a.close");
        sleep(5);

        $this->assertJqueryNotPresent(
            "#status-messages li.error:contains(testError)",
            "An error message is displayed after closing messages"
        ); 
        $this->assertJqueryNotPresent(
            "#status-messages li.warning:contains(testWarning)",
            "A warning message is displayed after closing messages"
        ); 

    }

    /**
     * Displays a message without reloading the page
     * 
     * @param string $message Message
     * @param string $type    Message type ('info', 'warning' or 'error')
     *  
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function throwMessage($message, $type = 'info')
    {
        return $this->getJSExpression("core.trigger('message', {message: '$message', type: '$type'})");
    }

}


