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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

namespace XLite\Model\OrderModifier;

/**
 * Tax order modifier
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
abstract class Tax extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    const MODIFIER_TAX = 'tax';

    /**
     * Define order modifiers 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineModifiers()
    {
        $list = parent::defineModifiers();

        $list[20] = self::MODIFIER_TAX;

        return $list;
    }

    /**
     * Calculate shipping 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateTax()
    {
    }

    /**
     * Check - tax is visible or not
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isTaxVisible($subcode)
    {
        return true;
    }

    /**
     * Get tax name
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTaxName($subcode)
    {
        return $subcode ?: 'Tax';
    }

    /**
     * Check - tax is available or not
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxAvailable()
    {
        return 0 < count($this->getDisplayTaxes());
    }

    /**
     * Check - tax is summable or not
     * 
     * @param string $subcode Subcode (tax name)
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxSummable($subcode)
    {
        return 'Tax' == $subcode;
    }

    /**
     * Get display taxes list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDisplayTaxes() 
    {
        return array();
    }

    /**
     * Get tax label 
     * 
     * @param string $name Tax name
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTaxLabel($name) 
    {
        return '';
    }

    /**
     * Get registration 
     * 
     * @param string $name Tax name
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRegistration($name) 
    {
        return '';
    }

    /**
     * Check - any tax is registered  or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxRegistered()
    {
        return false;
    }

    /**
     * Calculate and return all order taxes
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function calculateAllTaxes() 
    {
    }

    /**
     * Add new taxes into existsing taxes list
     * 
     * @param array $acc   Existing taxes list
     * @param array $taxes New taxes
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addTaxes(array $acc, array $taxes) 
    {
    }

    /**
     * Check - is tax defined or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isTaxDefined() 
    {
        return true;
    }

}
