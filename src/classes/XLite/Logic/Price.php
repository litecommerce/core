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

namespace XLite\Logic;

/**
 * Price mdification logic
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Price extends \XLite\Logic\ALogic
{
    protected $productNetPriceModifiers = array();
    protected $productDisplayPriceModifiers = array();
    protected $orderItemPriceModifiers = array();

    protected function registerPriceModifier($modifierType, $methodName, $weight = 0)
    {
        $reestrName = lcfirst(\XLite\Core\Converter::getInstance()->convertToCamelCase($modifierType) . 'Modifiers');

        $this->{$reestrName}[$methodName] = $weight;
    }

    public function applyPriceModifiers($modifierType, $price, $contextObj)
    {
        $reestrName = lcfirst(\XLite\Core\Converter::getInstance()->convertToCamelCase($modifierType) . 'Modifiers');

        foreach ($this->{$reestrName} as $methodName => $weight) {
            if (method_exists($this, $methodName)) {
                $price += $this->{$methodName}($price, $contextObj);
            }
        }

        return $price;
    }
}
