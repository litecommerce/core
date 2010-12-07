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
 * XLite_Web_Customer_Breadcrumbs 
 * 
 * @package Tests
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Web_Customer_Breadcrumbs extends XLite_Web_Customer_ACustomer
{
    /**
     * mainBlockLocator 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mainBlockLocator = '//div[@id="breadcrumbs" and @class="clear-block"]';


    /**
     * Check location line
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testLocationLine()
    {
        $this->skipCoverage();
        $this->open('store/product//product_id-4045/category_id-4004');

        // Main block
        $this->assertElementPresent(
            $this->mainBlockLocator,
            'check main breadcrumbs block'
        );

        // The "Home" link
        $this->assertElementPresent(
            $this->mainBlockLocator . '/a[@class="home-link"]/img',
            'check first breadcrumb (must be "Home")'
        );

        // Second node: "Toys" (link, expandable)
        $this->assertElementPresent(
            $this->mainBlockLocator . '/div[@class="location-node expandable"]'
            . '/a[@class="location-title" and text()="Toys"]',
            'check second breadcrumb (must be "Toys", link, expandable)'
        );

        // Third node: "Puzzles" (link, not expandable)
        $this->assertElementPresent(
            $this->mainBlockLocator . '/div[@class="location-node expandable"]'
            . '/a[@class="location-title" and text()="Puzzles"]',
            'check third breadcrumb (must be "Puzzles", link, expandable)'
        );

        // Forth node: "Pyramid Brain Twist" (text)
        $this->assertElementPresent(
            $this->mainBlockLocator . '/span[contains(text(),"Pyramid Brain Twist")]',
            'check forth breadcrumb (must be "Pyramid Brain Twist", text)'
        );

        // Subnodes popup
        $this->assertElementPresent(
            $this->mainBlockLocator . '/div[@class="location-node expandable"]'
            . '/ul[@class="location-subnodes"]',
            'check the subnodes popup'
        );
    }

    /**
     * testPopupInAction 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function testPopupInAction()
    {
        $this->skipCoverage();
        $this->open('store/product//product_id-4045/category_id-4004');

        $this->assertNotVisible(
            $this->mainBlockLocator . '/div[@class="location-node expandable"]'
            . '/ul[@class="location-subnodes"]',
            'subnodes popup must not be visible when mouse is out of the "Toys" title'
        );

        // Expand the popup
        $this->mouseOver(
            $this->mainBlockLocator . '/div[@class="location-node expandable"]'
        );

        // Check popup visibility
        $this->assertJqueryPresent(
            '.location-subnodes:visible',
            'subnodes popup must be visible when mouse is over the "Toys" title'
        );
    }
}
