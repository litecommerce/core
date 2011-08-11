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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\SimpleTaxes\View;

/**
 * Product class selector 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProductClassSelector extends \XLite\View\AView
{
    /**
     * Widget parameters names
     */
    const PARAM_FIELD_NAME = 'field';
    const PARAM_VALUE      = 'value';

    /**
     * Get active product classes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductClasses()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->findAll();
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/SimpleTaxes/product_class_selector.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_FIELD_NAME => new \XLite\Model\WidgetParam\String('Field', 'product_class', false),
            self::PARAM_VALUE      => new \XLite\Model\WidgetParam\Object('Value', null, false, '\XLite\Model\ProductClass'),
        );
    }

    /**
     * Check - specified product class is selected or not
     * 
     * @param \XLite\Model\ProductClass $current ProductClass
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSelectedProductClass(\XLite\Model\ProductClass $current)
    {
        return $this->getParam(self::PARAM_VALUE)
            && $current->getId() == $this->getParam(self::PARAM_VALUE)->getId();
    }
}

