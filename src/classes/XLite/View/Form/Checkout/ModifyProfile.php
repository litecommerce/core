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

namespace XLite\View\Form\Checkout;

/**
 * Register form (Checkout page)
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class ModifyProfile extends \XLite\View\Form\Checkout\ACheckout
{
    /**
     * Current form name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return 'modify_profile_form';
    }

    /**
     * getDefaultAction 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'modify_profile';
    }

    /**
     * getDefaultClassName
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultClassName()
    {
        return 'profile-form';
    }
}
