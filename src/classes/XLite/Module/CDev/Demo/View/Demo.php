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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\Demo\View;

/**
 * Demo panel widget
 *
 *
 * @ListChild (list="admin.main.page.header_wrapper", zone="admin", weight="1")
 * @ListChild (list="layout.main", zone="customer", weight="1")
 */
class Demo extends \XLite\View\AView
{
    /**
     * Get additional header
     *
     * @return string
     */
    public static function getAdditionalHeader()
    {
        return <<<HTML
<div class="demo-header">
This LiteCommerce Admin zone demo has been created for illustrative purposes only. No changes made in the demo will be reflected in the Customer zone
</div>
<style type="text/css">
<!--
body {
    padding-top: 34px;
}
.demo-header {
    color: #802418;
    background-color: #eaeae6;
    padding: 10px 0px;
    border-bottom: 1px solid #d2d2cf;
    font-weight: bold;
    width: 100%;
    position: fixed;
    top: 0px;
    left: 0px;
    right: 0px;
    text-align: center;
    z-index: 90000;
}

#topMenu {
    top: 74px;
}

#top-links {
    top: 34px;
}

#header .sw-version {
    top: 46px;
}

-->
</style>
HTML;
    }

    /**
     * Display 
     * 
     * @param string $template Tempalte OPTIONAL
     *  
     * @return void
     */
    public function display($template = null)
    {
        echo (static::getAdditionalHeader());
    }

    /**
     * Get default template 
     * 
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return null;
    }

}
