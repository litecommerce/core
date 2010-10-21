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

require_once __DIR__ . '/AProductList.php';

class XLite_Web_Customer_CategoryProducts extends XLite_Web_Customer_AProductList
{

    protected $widgetContainerClass = 'category-products';
    
    protected $widgetClass = '\\XLite\\View\\ItemsList\\Product\\Customer\\Category';

    protected function openTestPage()
    {
        $category = $this->getTestCategory();
        $url = $this->getCategoryURL($category->getCategoryId());
        $this->open($url);
    }

    protected function countAllTestProducts()
    {
        return $this->getTestCategory()->getProductsNumber();
    }

    protected function getAllTestProducts()
    {
        return $this->getTestCategory()->getProducts();
    }

    protected function getCategoryURL($id)
    {
        return "store/category//category_id-$id";
    }


    /**
     * Returns ID of a category having more products than other categories
     * 
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTestCategory()
    {
        $categories = \XLite\Core\Database::getRepo('XLite\Model\Category')->findByEnabled(true);

        $category = null;
        $max = 0;

        foreach ($categories as $one) {
            $count = $one->getProductsNumber();
            if ($count && ($count > $max) && ($count < 50)) {
                $category = $one;
                $max = $count;
            }
        }

        return $category;

    }


}
