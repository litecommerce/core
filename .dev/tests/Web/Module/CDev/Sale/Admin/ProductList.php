<?php
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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.10
 *
 * @resource product
 */

class XLite_Web_Module_CDev_Sale_Admin_ProductList extends XLite_Web_Admin_AAdmin
{


    function testPutOnSale()
    {
        $this->logIn();

        $this->open('admin.php?target=product_list');
        #Check checkboxes
        $products = $this->getProducts();
        foreach ($products as $product) {
            $this->click("//input[@name='select[" . $product->getId() . "]']");
        }

        $success = 'Products information has been successfully updated';
        $min = 'Minimum limit is broken';
        $max = 'Maximum limit is broken';
        $examples = array(
            array('percent' => 1000, 'price' => 'old', 'message' => $max),
            array('percent' => -10, 'price' => 'old', 'message' => $min),
            array('percent' => 0, 'price' => 'old', 'message' => $success),
            array('percent' => 50, 'price' => '50%', 'message' => $success),

            array('sale_price' => -10, 'price' => 'old', 'message' => $min),
            array('sale_price' => 1000, 'price' => 'old', 'message' => $success),
            array('sale_price' => 0, 'price' => 0, 'message' => $success),
            array('sale_price' => 1, 'price' => 1, 'message' => $success),
        );
        #iteration:

        foreach ($examples as $example) {
            #Click Put on sale
            $this->click('css=.sale-selected-button');
            $this->waitForPopUpDialog();
            #Data
            if (isset($example['sale_price'])) {
                $this->type('css=#sale-price-value-sale_price', $example['sale_price']);
            }
            else {
                $this->click('css=#sale-price-percent-off');
                sleep(1);
                $this->type('css=#sale-price-value-sale_percent', $example['percent']);
            }
            #Click save
            $this->click('css=.ui-dialog.popup button.action span');
            sleep(1);
            #check sale and msg
            $this->waitForLocalCondition(
                'jQuery(":contains(\''.$example['message'].'\')").length > 0',
                10000,
                'Message is not present'
            );
            //$this->waitForTextPresent($example['message']);

            foreach ($products as $product) {
                $this->assertElementPresent('css=#product-sale-label-' . $product->getId());
                if ($example['price'] !== 'old') {
                    $this->assertJqueryNotPresent('#product-sale-label-' . $product->getId() . ' .product-name-sale-label-disabled', 'No sale label');
                }
                else {
                    $this->assertJqueryPresent('#product-sale-label-' . $product->getId() . ' .product-name-sale-label-disabled', 'Sale label is shown');
                }
            }
        }
    }

    /**
     * @return XLite\Model\Product[]
     */
    private function getProducts()
    {
        #First 5 products with price > 10 and < 500 sorted by name asc
        return \XLite\Core\Database::getRepo('XLite\Model\Product')->createQueryBuilder('p')->andWhere('p.price > 10')->andWhere('p.price < 500')->orderBy('translations.name', 'asc')->setMaxResults(5)->getResult();
    }
}