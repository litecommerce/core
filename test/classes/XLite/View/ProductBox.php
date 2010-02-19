<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product sidebar box widget
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
 * Product sidebar box widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_ProductBox extends XLite_View_SideBarBox
{
    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getHead()
    {
        return 'Product';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return 'product_box';
    }

    /**
     * Return current product Id 
     * 
     * @param int $productId passed Id
     *  
     * @return int
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getProductId($productId)
    {
        return isset($productId) ? $productId : $this->attributes['productId'];
    }

    /**
     * Get product 
     * 
     * @return XLite_Model_Product
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProduct($productId = null)
    {
        return XLite_Model_CachingFactory::getObject('XLite_Model_Product', $this->getProductId($productId));
    }

	/**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
		parent::defineWidgetParams();

        $this->widgetParams += array(
			new XLite_Model_WidgetParam_String('productId', 0, 'Product Id'),
		);
    }


    /**
     * Set some attributes
     *
     * @param array $attributes widget params
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    public function __construct(array $attributes = array())
    {
        $this->attributes['productId'] = 0;

        parent::__construct($attributes);
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getProduct()->is('available');
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attrs attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attrs)
    {
        $conditions = array(
            array(
                self::ATTR_CONDITION => !isset($attrs['productId']) || !is_numeric($attrs['productId']),
                self::ATTR_MESSAGE   => 'Product Id is not numeric',
            ),
            array(
                self::ATTR_CONDITION => 0 >= ($attrs['productId'] = intval($attrs['productId'])),
                self::ATTR_MESSAGE   => 'Product Id must be a positive integer',
            ),
            array(
                self::ATTR_CONDITION => !$this->getProduct($attrs['productId'])->isPersistent,
                self::ATTR_MESSAGE   => 'Product with Id #' . $attrs['productId'] . ' is not found',
            ),
        );

        return parent::validateAttributes($attrs) + $this->checkConditions($conditions);
    }
}

