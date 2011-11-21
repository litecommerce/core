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

namespace XLite\Module\CDev\DrupalConnector\Core;

/**
 * Authorization routine
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Auth extends \XLite\Core\Auth implements \XLite\Base\IDecorator
{
    /**
     * Get stored profiel id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getStoredProfileId()
    {
        $profileId = parent::getStoredProfileId();

        if (
            !$profileId
            && \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && !empty($GLOBALS['user'])
        ) {
            $profileId = \XLite\Module\CDev\DrupalConnector\Handler::getInstance()
                ->getProfileIdByCMSId($GLOBALS['user']->uid);
            
            if ($profileId) {
                // Save profile Id in session
                \XLite\Core\Session::getInstance()->profile_id = $profileId;
            }
        }

        return $profileId;
    }
}
