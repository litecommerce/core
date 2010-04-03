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


/**
 * XLite_View_ProductSelect is a popup product search & select dialog.
 * Syntax:  <widget class="XLite_View_ProductSelect" formName="offerForm" formField="addBonusProduct">
 * where formName is a 'name' attribute of the form tag, formField is a form field name. A hidden field named $formField.'_id'
 * will be created to hold the selected product id.
 * Optional parameters are: 
 *    label - the "Select product ..." replacement;
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_ProductSelect extends XLite_View_Abstract
{	
    /*
     * Widget parameters names
     */
    const PARAM_FORM_NAME = 'formName';
    const PARAM_FORM_FIELD = 'formName';
    const PARAM_LABEL = 'label';
    const PARAM_PRODUCT = 'product';
    const PARAM_REMOVE_BUTTON = 'removeButton';

    /**
     * product 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $product = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_product.tpl';
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
            self::PARAM_FORM_NAME     => new XLite_Model_WidgetParam_String('Form name', ''),
            self::PARAM_FORM_FIELD    => new XLite_Model_WidgetParam_String('Form field', ''),
            self::PARAM_LABEL         => new XLite_Model_WidgetParam_String('Form label', 'Select product'),
            self::PARAM_PRODUCT       => new XLite_Model_WidgetParam_Object('Product object', null),
            self::PARAM_REMOVE_BUTTON => new XLite_Model_WidgetParam_Bool('Display Remove button', false),
        );
    }

    /**
     * getName 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getName()
    {
        return $this->getParam(self::PARAM_FORM_NAME) . $this->getParam(self::PARAM_FORM_FIELD);
    }
    
	/**
	 * getProduct 
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getProduct()
    {
        if (is_null($this->product)) {

            if (!is_null($this->getParam(self::PARAM_PRODUCT))) {
                $this->product = $this->getParam(self::PARAM_PRODUCT);

            } else {
                $this->product = ($productId = $this->get($this->getParam(self::PARAM_FORM_FIELD) . '_id')) ? new XLite_Model_Product($productId) : null;
            }
        }

        return $this->product;
	}

}

