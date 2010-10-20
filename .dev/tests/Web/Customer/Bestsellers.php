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

class XLite_Web_Customer_Bestsellers extends XLite_Web_Customer_ACustomer
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
            "//h2[text()='Bestsellers']",
            'check bestsellers title'
        );
        
        $this->assertElementPresent(
            "//div[@class='items-list XLiteModuleBestsellersViewBestsellers']",
            'check bestsellers block'
        );

        $this->assertElementPresent(
            "//div[@class='items-list XLiteModuleBestsellersViewBestsellers']/table[@class='list-body list-body-grid list-body-grid-4-columns']",
            'Check table'
        );

        $best = $this->findBestsellers();

        foreach ($best as $product) {

            $url = $product->getImageURL();

            $this->assertElementPresent(
                "//img[@src='$url']",
                'Check ' . $url . ' element'
            );  

        }
    }

    public function testCatPage()
    {   
        $this->open('downloadables/');

        $this->assertElementPresent(
            "//h2[text()='Bestsellers']",
            'check bestsellers title'
        );  
    
        $this->assertElementPresent(
            "//div[@class='items-list XLiteModuleBestsellersViewBestsellers']",
            'check bestsellers block'
        );  

        $best = $this->findBestsellers();

        $best1 = $this->findBestsellers(0, 3002);

        foreach ($best1 as $product) {

            $url = $product->getImageURL();

            $this->assertElementPresent(
                "//img[@src='$url']",
                'Check ' . $url . ' element'
            );  

        }   

        foreach ($best as $product) {

            if (!in_array($product, $best1)) {

                $url = $product->getImageURL();

                $this->assertElementNotPresent(
                    "//img[@src='$url']",
                    'Check ' . $url . ' element'
                );  

            }

        }

    }


    /** 
     *  Wrapper for the REPO findBestsellers method
     * 
     * @param int $count ____param_comment____
     * @param int $cat   ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function findBestsellers($count = 0, $cat = 0)
    {   
        return \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->findBestsellers($count, $cat);
    }   

}
