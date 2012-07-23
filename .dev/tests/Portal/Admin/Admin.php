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

namespace Portal\Admin;

require_once PATH_TESTS . '/Portal/Autoload.php';

class Admin extends \Portal\Page
{
    public function __construct() {
        $this->url = "http://localhost/xlite/src/admin.php";
        // Define components
        $this->components[] = new \Portal\Link('lnkOrders', \Selenium\Locator::xpath('//div[@class="dialog-content"]/ul[@class="admin-panel"]/li/a[@href="admin.php?target=order_list"]'));
        
        parent::__construct();
    }

    public function open()
    {
        // Open authentication page and login
        $page = new \Portal\Admin\AdminLogin;
        $page->open();
        $page->login();
    }
}
