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
 * Extra fields list 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_ExtraFields extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT = 'product';


    /**
     * Cached extra fields list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $extraFields = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'extra_fields.tpl';
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
            self::PARAM_PRODUCT => new XLite_Model_WidgetParam_Object('Product', null, false, 'XLite_Model_Product'),
        );
    }


    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_PRODUCT)
            && $this->getExtraFields();
    }

    /**
     * Get extra fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getExtraFields()
    {
        if (is_null($this->extraFields)) {

            $this->extraFields = $this->getParam(self::PARAM_PRODUCT)->getExtraFields();

    	    /* TODO - rework
            if ($this->config->General->enable_extra_fields_inherit == "Y") {
    	        $isAdminZone = $this->xlite->get('adminZone');
        	    $this->xlite->set("adminZone", true);
            }
    	    */

            $product_categories = $this->getParam(self::PARAM_PRODUCT)->getCategories();

    	    /* TODO - rework
            if ($this->config->getComplex('General.enable_extra_fields_inherit') == "Y") {
    	        $this->xlite->set("adminZone", $isAdminZone);
        	}
            */

        	$extraFields_root = array();
            $ids = array();
            foreach ($product_categories as $cat) {
                $ids[] = $cat->get('category_id');
            }

            foreach ($this->extraFields as $idx => $extraField) {
            	$extraFields_categories = $extraField->getCategories();
                if (count($extraFields_categories) > 0) {
                    if (count(array_intersect($ids, $extraFields_categories)) == 0) {
                    	unset($this->extraFields[$idx]);

                    } elseif ($extraField->get('product_id') == 0) {
                    	$extraFields_root[$extraField->get('field_id')] = $idx;
                    }
                }
            }

            foreach ($this->extraFields as $idx => $extraField) {
                if (
                    isset($extraFields_root[$extraField->get('parent_field_id')])
                    && isset($this->extraFields[$extraFields_root[$extraField->get('parent_field_id')]])
                ) {
                    unset($this->extraFields[$extraFields_root[$extraField->get('parent_field_id')]]);
                }
            }

            foreach ($this->extraFields as $idx => $extraField) {
                if ($extraField->get('parent_field_id') == 0) {
                    $ef_child = new XLite_Model_ExtraField();
                    $ef_child->set('ignoreFilter', true);

                    if ($ef_child->find('parent_field_id = \'' . $extraField->get('field_id') . '\' AND enabled = 0 AND product_id = \'' . $this->getParam(self::PARAM_PRODUCT)->get('product_id') . '\'')) {
                        unset($this->extraFields[$idx]);
                    }
                }
            }
        }

        return $this->extraFields;
    }
}
