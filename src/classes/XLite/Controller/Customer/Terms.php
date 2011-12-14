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

namespace XLite\Controller\Customer;

/**
 * Terms and conditions page
 *
 * @see   ____class_see____
 * @since 1.0.13
 */
class Terms extends \XLite\Controller\Customer\Category
{
    /**
     * Controller parameters list
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target');


    /**
     * Check whether the title is to be displayed in the content area
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isTitleVisible()
    {
        return true;
    }

    /**
     * Return title of page
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return static::t('Terms and conditions');
    }
}
