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

namespace XLite\View;

/**
 * Authorization
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center", zone="customer")
 */
class Authorization extends \XLite\View\SimpleDialog
{
    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return static::t('Authentication');
    }

    /**
     * Return file name for the center part template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBody()
    {
        return 'authentication.tpl';
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
        $target = \XLite\Core\Request::getInstance()->target;

        return parent::isVisible()
            && ('login' == $target || ('profile' == $target && 'login' == \XLite\Core\Request::getInstance()->mode));
    }
}
