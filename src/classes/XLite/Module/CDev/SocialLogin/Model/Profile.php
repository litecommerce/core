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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SocialLogin\Model;

/**
 * \XLite\Module\CDev\SocialLogin\Model\Profile
 *
 */
class Profile extends \XLite\Model\Profile implements \XLite\Base\IDecorator
{
    /**
     * Auth provider (facebook, google, etc.)
     *
     * @var   string
     *
     * @Column (type="string", length=128, nullable=true)
     */
    protected $socialLoginProvider;

    /**
     * Auth provider-unique user id (for ex. facebook user id)
     *
     * @var   string
     *
     * @Column (type="string", length=128, nullable=true)
     */
    protected $socialLoginId;

    /**
     * Checks if current profile is a SocialLogin's profile
     *
     * @return boolean
     */
    public function isSocialProfile()
    {
        return (bool) $this->getSocialLoginProvider();
    }
}
