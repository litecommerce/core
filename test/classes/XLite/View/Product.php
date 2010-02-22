<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Product widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Product extends XLite_View_Dialog
{
    /**
     * Title
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $head = 'Catalog';

    /**
     * Base href 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $href = 'cart.php';

    /**
     * Initilization
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        parent::initView();

        $this->body = 'product_details.tpl';
        $this->visible = 'product' == $this->target
            && 0 < $this->product_id
            && $this->getProduct()->get('available');

        $this->showLocationPath = true;
    }

    /**
     * Get product 
     * 
     * @return XLite_Model_Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct()
    {
        return new XLite_Model_Product($this->product_id);
    }

    /**
     * Get previous product 
     * 
     * @return XLite_Model_Product
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
     * @return XLite_Model_Product
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
        foreach (XLite::$controller->getCategory()->getProducts() as $p) {
            if ($found) {
                $this->nextProduct = $p;
                break;
            }
            if ($currentProduct->get('product_id') == $p->get('product_id')) {
                $this->previousProduct = $prev;
                $found = true;
            }
            $prev = $p;
        }
    }
}

