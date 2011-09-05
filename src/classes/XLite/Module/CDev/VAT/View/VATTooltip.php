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

namespace XLite\Module\CDev\VAT\View;

/**
 * VAT tooltip widget
 *
 * @see   ____class_see____
 * @since 1.0.1
 */
class VATTooltip extends \XLite\View\AView
{
    /**
     * Common widget parameter names
     */
    const PARAM_PRODUCT = 'product';


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
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object('Product', null, false, '\\Xlite\\Model\\Product'),
        );
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/VAT/vat_tooltip.tpl';
    }

    /**
     * Get VAT taxes 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getVATTaxes()
    {
        return $this->getParam(self::PARAM_PRODUCT)->getIncludedTaxList();
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_PRODUCT)
            && $this->getVATTaxes();
    }
}
