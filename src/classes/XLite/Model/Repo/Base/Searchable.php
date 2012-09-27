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

namespace XLite\Model\Repo\Base;

// TODO - must be a parent of the Repo classes
// TODO - must be completely revised after the multiple inheritance will be added

/**
 * Searchable
 *
 */
abstract class Searchable extends \XLite\Base\SuperClass
{
    /**
     * Prepare the "LIMIT" SQL condition
     *
     * @param integer                $start First item index
     * @param integer                $count Items per frame
     * @param \XLite\Core\CommonCell $cnd   Condition object to use OPTIONAL
     *
     * @return \XLite\Core\CommonCell
     */
    public static function addLimitCondition($start, $count, \XLite\Core\CommonCell $cnd = null)
    {
        if (!isset($cnd)) {
            $cnd = new \XLite\Core\CommonCell();
        }
        // TODO - must be "self::P_LIMIT"
        $cnd->{\XLite\Model\Repo\Product::P_LIMIT} = array($start, $count);

        return $cnd;
    }
}
