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

namespace XLite\Module\ProductOptions\View;

/**
 * Product options lsit
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0
 * @ListChild (list="productDetails.main", weight="70")
 */
class ProductOptions extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT = 'product';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/ProductOptions/product_options.tpl';
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
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object('Product', $this->getProduct(), false, '\XLite\Model\Product'),
        );
    }


    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_PRODUCT)->hasOptions();
    }

    /**
     * Get product options 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions()
    {
        return $this->getParam(self::PARAM_PRODUCT)->getActiveOptions();
    }

    /**
     * Get template name by option group
     * 
     * @param \XLite\Module\ProductOptions\Model\OptionGroup $option Option group
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTemplateNameByOption(\XLite\Module\ProductOptions\Model\OptionGroup $option)
    {
        switch ($option->getType() . $option->getViewType()) {
            case $option::GROUP_TYPE . $option::SELECT_VISIBLE:
                $tpl = 'modules/ProductOptions/display/select.tpl';
                break;

            case $option::GROUP_TYPE . $option::RADIO_VISIBLE:
                $tpl = 'modules/ProductOptions/display/radio.tpl';
                break;

            case $option::TEXT_TYPE . $option::TEXTAREA_VISIBLE:
                $tpl = 'modules/ProductOptions/display/textarea.tpl';
                break;

            case $option::TEXT_TYPE . $option::INPUT_VISIBLE:
                $tpl = 'modules/ProductOptions/display/input.tpl';
                break;

            default:
                // TODO - add throw exception
        }

        return $tpl;
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'modules/ProductOptions/options_validation.js';

        return $list;
    }

    /**
     * Check - option is selected or not
     * 
     * @param \XLite\Module\ProductOptions\Model\Option $option Option class
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isOptionSelected(\XLite\Module\ProductOptions\Model\Option $option)
    {
        $options = $option->getGroup()->getOptions();

        return $options[0]->getOptionId() == $option->getOptionId();
    }

    /**
     * Get option text 
     * 
     * @param \XLite\Module\ProductOptions\Model\OptionGroup $option Option class
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptionText(\XLite\Module\ProductOptions\Model\OptionGroup $option)
    {
        return '';
    }
}

