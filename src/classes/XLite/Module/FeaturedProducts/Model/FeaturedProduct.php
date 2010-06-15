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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * XLite_Module_FeaturedProducts_Model_FeaturedProduct 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_FeaturedProducts_Model_FeaturedProduct extends XLite_Model_Abstract
{
    /**
     * fields 
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $fields = array(
        'product_id'  => 0,
        'category_id' => 0,
        'order_by'    => 0,
    );
    
    /**
     * primaryKey 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $primaryKey = array('category_id','product_id');
    
    /**
     * alias 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $alias = 'featured_products';
    
    /**
     * product 
     * 
     * @var    XLite_Model_Product
     * @access protected
     * @since  3.0.0
     */
    protected $product = null;


    /**
     * defaultOrder
     *
     * @var    string
     * @access public
     * @since  3.0.0
     */
    public $defaultOrder = 'order_by';


    /**
     * getProduct 
     * FIXME - must be protected; see Module/FeaturedProducts/Model/Category.php
     * 
     * @return XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProduct()
    {
        if (!isset($this->product)) {
            $this->product = new XLite_Model_Product($this->get('product_id'));
        }

        return $this->product;
    }

    /**
     * Filter 
     * FIXME - must be protected;
     * but current approach does not allow this;
     * see Module/FeaturedProducts/ModelCategory.php
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function filter()
    {
        return $this->getProduct()->isExists() ? $this->getProduct()->filter() : false;
    }
}

