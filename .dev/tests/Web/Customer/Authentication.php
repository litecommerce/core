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

/**
 * Test authentication functions:
 * - login page
 * - login popup
 * - restore password page
 * - restore password popup
 * - logout link
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_Web_Customer_Authentication extends XLite_Web_Customer_ACustomer
{

    /**
     * Check whether the popup forms (Login and Password Recovery) are correctly displayed and have all necessary fields
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testPopupForms()
    {
        // Open the home page
        $this->open('');

        // By default popup forms should be hidden
        $this->assertJqueryNotPresent(
            ".blockUI form#user-login:visible",
            "Login form is visible without clicking Login link"
        );
        $this->assertJqueryNotPresent(
            ".blockUI form#user-pass:visible",
            "Password form is visible without clicking Login link"
        );

        // Popup the login form
        $this->assertElementPresent(
            "link=Log in",
            "Login link is missing on the home page"
        );
        $this->click("link=Log in");
        $this->assertJqueryPresent(
            ".blockUI form#user-login:visible",
            "Popup login form is not visible after clicking Login link"
        );
        $this->assertJqueryNotPresent(
            ".blockUI form#user-pass:visible",
            "Password form is visible without clicking Password link"
        );

        // Check Login form elements
        $this->assertElementPresent(
            "css=.blockUI form#user-login input[name='name']",
            "Login form: username field is missing"
        );
        $this->assertElementPresent(
            "css=.blockUI form#user-login input[name='pass']",
            "Login form: password field is missing"
        );
        $this->assertElementPresent(
            "css=.blockUI form#user-login .form-submit",
            "Login form: submit button is missing"
        );

        // Check the close button
         $this->assertElementPresent(
            "css=.blockUI a.close-link",
            "Close button is not displayed for the login popup box"
        );
        $this->click("css=.blockUI a.close-link");
        // Wait while the popup box is fading black
        sleep(10);
        $this->assertJqueryNotPresent(
            ".blockUI form#user-login:visible",
            "Login form is visible after closing the popup window"
        );

        // Reopen the popup box 
        $this->click("link=Log in");
        $this->assertJqueryPresent(
            ".blockUI form#user-login:visible",
            "Popup login form is not visible after clicking Login link the second time"
        );
  
        // Switch to Password Recovery form
        $this->assertElementPresent(
            "link=Forgot password?",
            "Password link is missing on the home page"
        );
        $this->click("link=Forgot password?");
        $this->assertJqueryPresent(
            ".blockUI form#user-pass:visible",
            "Password form is not visible after clicking Password link"
        );
        $this->assertJqueryNotPresent(
            ".blockUI form#user-login:visible",
            "Login form is visible after switching to Password form"
        );

        // Check the password form elements
        $this->assertElementPresent(
            "css=.blockUI form#user-pass input[name='name']",
            "Password form: username field is missing"
        );
        $this->assertElementPresent(
            "css=.blockUI form#user-pass .form-submit",
            "Password form: submit button is missing"
        );

        // Check the close button
         $this->assertElementPresent(
            "css=.blockUI a.close-link",
            "Close button is not displayed for the password form"
        );
        $this->click("css=.blockUI a.close-link");
        // Wait while the popup box is fading black
        sleep(10);
        $this->assertJqueryNotPresent(
            ".blockUI form#user-pass:visible",
            "Password form is visible after closing the popup window"
        );
        
    }

    /**
     * Check whether the popup login form functions correctly
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testPopupLogin()
    {
        // Popup the login form
        $this->open('');
        $this->assertElementPresent(
            "link=Log in",
            "Login link is missing on the home page"
        );
        $this->click("link=Log in");
        $this->assertJqueryPresent(
            ".blockUI form#user-login:visible",
            "Popup login form is not visible after clicking Login link"
        );
 
        // Submit wrong credentials
        $this->type("css=.blockUI form#user-login input[name='name']", "wrong");
        $this->type("css=.blockUI form#user-login input[name='pass']", "master");
        $this->submitAndWait("css=.blockUI form#user-login");
        $this->assertElementPresent(
            "css=form#user-login",
            "Login form is not shown after submitting a wrong username"
            
        );
        $this->assertElementPresent(
            "css=div.error",
            "Error message is not shown after submitting a wrong username"
        );

         // Reopen the popup login form
        $this->open('');
        $this->click("link=Log in");

        // Submit correct credentials (make sure there is master/master user in the database!)
        $this->type("css=.blockUI form#user-login input[name='name']", "master");
        $this->type("css=.blockUI form#user-login input[name='pass']", "master");
        $this->submitAndWait("css=.blockUI form#user-login");
        $this->assertElementNotPresent(
            "css=.blockUI form#user-login",
            "Login form is shown for a signed-in user"
            
        );
        $this->assertElementNotPresent(
            "link=Log in",
            "Log in link is shown for a signed-in user"
        );
       $this->assertTextPresent(
            "Hello, master",
            "'Greatings' text is missing"
        );

        // Check Your Account link
        $this->assertElementPresent(
            "link=Your account",
            "Your Account link is not shown for a signed-in user"
        );
        $this->clickAndWait("link=Your account");
        $this->assertElementPresent(
            "//h1[@id='page-title'][text()='master']",
            "'My Account' link opens a page that doesn't show a user name in its title"
        );

    }

    /**
     * Check whether the popup Password Recovery form functions correctly
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testPopupRecoveryPassword()
    {
        // Popup the password form
        $this->open('');
        $this->assertElementPresent(
            "link=Log in",
            "Login link is missing on the home page"
        );
        $this->click("link=Log in");
        $this->assertElementPresent(
            "link=Forgot password?",
            "Password link is missing on the home page"
        );
        $this->click("link=Forgot password?");
        $this->assertJqueryPresent(
            ".blockUI form#user-pass:visible",
            "Password form is not visible after clicking Password link"
        );

        // Submit wrong credentials
        $this->assertElementPresent(
            "css=.blockUI form#user-pass input[name='name']",
            "password field"
        );
        $this->type("css=.blockUI form#user-pass input[name='name']", "wrong");
        $this->submitAndWait("css=.blockUI form#user-pass");
        $this->assertElementPresent(
            "css=form#user-pass",
            "Recovery Password form is not shown after submitting a wrong username"
            
        );
        $this->assertElementPresent(
            "css=div.error",
            "Error message is not shown after submitting a wrong username in Password form"
        );

        // Reopen the form
        $this->open('');
        $this->click("link=Log in");
        $this->click("link=Forgot password?");

        // Submit correct credentials
        $this->type("css=.blockUI form#user-pass input[name='name']", "master");
        $this->submitAndWait("css=.blockUI form#user-pass");
        $this->assertTextPresent(
            "instructions*sent*to*mail",
            "Confirmation message is not shown after submitting Password form"
        );

    }

}
