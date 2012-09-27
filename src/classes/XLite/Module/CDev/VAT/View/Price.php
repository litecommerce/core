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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\VAT\View;

/**
 * Price widget 
 *
 */
class Price extends \XLite\View\Price implements \XLite\Base\IDecorator
{
    /**
     * Determine if we need to display 'incl.VAT' note
     *
     * @return boolean
     */
    protected function isVATApplicable()
    {
        $result = false;
        $optionValue = \XLite\Core\Config::getInstance()->CDev->VAT->display_inc_vat_label;

        if (
            ('P' == $optionValue && in_array(\XLite\Core\Request::getInstance()->target, $this->getProductTargets()))
            || 'Y' == $optionValue
        ) {
            $product = $this->getProduct();
            $taxes = $product->getIncludedTaxList();
            $result = !empty($taxes);
        }

        return $result;
    }

    /**
     * Get targets of product pages
     * 
     * @return array
     */
    protected function getProductTargets()
    {
        return array('product', 'quick_look');
    }

    /**
     * Determine if we need to display 'incl.VAT' note
     *
     * @return boolean
     */
    protected function isDisplayedPriceIncludingVAT()
    {
        return \XLite\Core\Config::getInstance()->CDev->VAT->display_prices_including_vat;
    }

    /**
     * Determine if we need to display 'incl.VAT' note
     *
     * @return boolean
     */
    protected function getVATName()
    {
        $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\VAT\Model\Tax')->getTax();

        return $tax->name;
    }
}
