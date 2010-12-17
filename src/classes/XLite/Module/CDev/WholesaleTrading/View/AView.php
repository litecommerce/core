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

namespace XLite\Module\CDev\WholesaleTrading\View;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class AView extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    function price_format($base, $field = "", $thousand_delim = null, $decimal_delim = null)
    {
        if (is_Object($base) && $base->get($field) === $this->config->CDev->WholesaleTrading->price_denied_message) {
            return $this->config->CDev->WholesaleTrading->price_denied_message;
        } else if ($base === $this->config->CDev->WholesaleTrading->price_denied_message) {
            return $this->config->CDev->WholesaleTrading->price_denied_message;
        }

        return parent::price_format($base, $field, $thousand_delim, $decimal_delim);
    }

    function disabled($disabled)
    {
        if ($disabled) {
            print('disabled');
        }
    }

    function checked($checked)
    {
        if ($checked) {
            return "checked";
        }
    }

    function isEven($val)
    {
        return ($val % 2 == 0);
    }

    function selectString($first, $second, $print_first)
    {
        if ($print_first) {
            print $first;
        } else {
            print $second;
        }
    }

    function invertSign($value)
    {
        return -$value;
    }
}
