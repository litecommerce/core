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
 * @since     1.0.15
 */

namespace XLite\View\FormField\Inline\Input\Checkbox\Switcher;

/**
 * Enabled state switcher
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
class Enabled extends \XLite\View\FormField\Inline\Input\Checkbox\Switcher
{
    /**
     * Short name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $shortName = 'enabled';

    /**
     * Check - field is editable or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasSeparateView()
    {
        return false;
    }

}

