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
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View;

/**
 * Product details widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 */
class Product extends Dialog
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return $this->getProduct()->getName();
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'product_details';
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getProduct()->isAvailable();
    }

    /**
     * Get previous product 
     * 
     * @return \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPreviousProduct()
    {
        if (!isset($this->previousProduct)) {
            $this->detectPrevNext();
        }

        return $this->previousProduct;
    }

    /**
     * Get next product 
     * 
     * @return \XLite\Model\Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNextProduct()
    {
        if (!isset($this->nextProduct)) {
            $this->detectPrevNext();
        }

        return $this->nextProduct;
    }

    /**
     * Detect previous and next product
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function detectPrevNext()
    {
        $this->previousProduct = false;
        $this->nextProduct = false;
        $currentProduct = $this->getProduct();
        $found = false;
        $prev = false;

        foreach ($this->getCategory()->getProducts() as $p) {
            if ($found) {
                $this->nextProduct = $p;
                break;
            }
            if ($currentProduct->getProductId() == $p->getProductId()) {
                $this->previousProduct = $prev;
                $found = true;
            }
            $prev = $p;
        }
    }

    /**
     * Check - available product for sale or not
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isAvailableForSale()
    {
        return true;
    }


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'product';
    
        return $result;
    }
}
