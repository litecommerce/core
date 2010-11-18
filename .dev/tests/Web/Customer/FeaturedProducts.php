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

class XLite_Web_Customer_FeaturedProducts extends XLite_Web_Customer_ACustomer
{
    /**
     * Check main page
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function testMainPage()
    {
        $this->open('store/main');

        $this->assertElementPresent(
            "//h2[text()='Featured products']",
            'check bestsellers title'
        );
        
        $this->assertElementPresent(
            "//h2[text()='Featured products']"
            . "/following-sibling::div[@class='content']"
            . "/div[@class='items-list']"
            . "/div[@class='products']",
            'check featured products block'
        );

        $this->assertElementPresent(
            "//h2[text()='Featured products']"
            . "/following-sibling::div[@class='content']"
            . "/div[@class='items-list']"
            . "/div[@class='products']"
            . "/table[@class='products-grid grid-3-columns']",
            'Check table'
        );

        foreach ($this->getFeaturedProducts() as $product) {

            $id = $product->getProduct()->getProductId();

            $this->assertElementPresent(
                "//h2[text()='Featured products']"
                . "/following-sibling::div[@class='content']"
                . "/descendant::div[@class='product productid-$id ui-draggable']",
                'Check ' . $id . ' element'
            );
        }
    }

    //
    public function testCatPage()
    {   
        $this->open('toys/');

        foreach ($this->getFeaturedProducts(1004) as $product) {

            $id = $product->getProduct()->getProductId();

            $this->assertElementPresent(
                "//h2[text()='Featured products']"
                . "/following-sibling::div[@class='content']"
                . "/descendant::div[@class='product productid-$id ui-draggable']",
                'Check ' . $id . ' element (category id: 1004)'
            );  

        }   
    }


    /** 
     *  Wrapper for the REPO getFeaturedProducts method
     * 
     * @param int|null $cat Category ID  
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFeaturedProducts($cat = 0)
    {   
        return \XLite\Core\Database::getRepo('XLite\Module\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($cat);
    }   

}
