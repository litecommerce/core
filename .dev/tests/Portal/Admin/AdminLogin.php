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
 * @subpackage Portal
 * @author     Creative Development LLC <info@cdev.ru>
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.1.0
 */

require_once PATH_TESTS . '/Portal/Page.php';
require_once PATH_TESTS . '/Portal/Component.php';

class Portal_AdminLogin extends Portal_Page
{
    public function __construct()
    {
        $config = $this->getConfig();
        $this->url = $config['admin']['http_location'] . '/admin.php?target=login';

        // Define components
        $this->components[] = new Portal_Component('fldUsername', '//input[@name="login" and @type="text"]');
        $this->components[] = new Portal_Component('fldPassword', '//input[@name="password" and @type="password"]');
        $this->components[] = new Portal_Component('btnLogin', '//button[@class="main-button" and @type="submit"]');
        
        parent::__construct();
    }

    public function open()
    {
        $this->getBrowser()->open($this->url);
    }
    
    public function login($username = NULL, $password = NULL)
    {
        if (is_null($username) || is_null($password)) {
            $config = $this->getConfig();
            $username = $config['admin']['username'];
            $password = $config['admin']['password'];
        }
        
        $this->fldUsername->enter($username);
        $this->fldPassword->enter($password);
        $this->btnLogin->press();
    }
}
