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
     * Title
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $head = 'Product';

    /**
     * Directory contains sidebar content
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $dir = 'product_box';

    /**
     * Product id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $product_id = 0;

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

        $this->visible = 0 < $this->product_id
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
			new XLite_Model_WidgetParam_String('product_id', 0, 'Product Id'),
		);
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

		if (!isset($attributes['product_id']) || !is_numeric($attributes['product_id'])) {
			$errors['product_id'] = 'Product Id is not numeric!';
		} else {
			$attributes['product_id'] = intval($attributes['product_id']);
		}

        if (!$errors && 0 >= $attributes['product_id']) {
            $errors['product_id'] = 'Product Id must be positive integer!';
		}

		if (!$errors) {
			$product = new XLite_Model_Product($attributes['product_id']);

			if (!$product->isPersistent) {
				$errors['product_id'] = 'Product with product Id #' . $attributes['product_id'] . ' can not found!';
			}
		}

		return $errors;
    }
}

