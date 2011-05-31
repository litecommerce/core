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

namespace XLite\Module\CDev\Demo\Controller\Admin;

/**
 * Profile
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Profile extends \XLite\Controller\Admin\Profile implements \XLite\Base\IDecorator
{
    /**
     * Check if we need to forbid current action
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkForDemoController()
    {
        return parent::checkForDemoController() && \XLite::isAdminZone();
    }

    /**
     * URL to redirect if action is forbidden
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getForbidInDemoModeRedirectURL()
    {
        return 'delete' == \XLite\Core\Request::getInstance()->action
            ? \XLite\Core\Converter::buildURL('users', '', array('mode' => 'search'))
            : (
                $this->getProfile()->getProfileId()
                ? \XLite\Core\Converter::buildURL(
                    'profile', 
                    '', 
                    array('profile_id' => $this->getProfile()->getProfileId())
                )
                : \XLite\Core\Converter::buildURL('profile', '', array('mode' => 'register'))
            );
    }
}
