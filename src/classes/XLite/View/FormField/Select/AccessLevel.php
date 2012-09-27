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

namespace XLite\View\FormField\Select;

/**
 * \XLite\View\FormField\Select\AccessLevel
 *
 */
class AccessLevel extends \XLite\View\FormField\Select\Regular
{
    /**
     * Determines if this field is visible for customers or not
     *
     * @var boolean
     */
    protected $isAllowedForCustomer = false;


    /**
     * getDefaultOptions
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        $list = \XLite\Core\Auth::getInstance()->getUserTypesRaw();

        foreach ($list as $k => $v) {
            $list[$k] = static::t($v);
        }

        return $list;
    }

    /**
     * Check field value validity
     *
     * @return boolean
     */
    protected function checkFieldValue()
    {
        return in_array($this->getValue(), \XLite\Core\Auth::getInstance()->getAccessLevelsList());
    }
}
