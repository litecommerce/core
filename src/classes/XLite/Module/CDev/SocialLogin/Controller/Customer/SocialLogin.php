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
 * @since     1.0.24
 */

namespace XLite\Module\CDev\SocialLogin\Controller\Customer;

/**
 * Authorization grants are routed to this controller
 *
 * @see   ____class_see____
 * @since 1.0.24
 */
class SocialLogin extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Perform login action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function doActionLogin()
    {
        $authProviders = \XLite\Module\CDev\SocialLogin\Core\AuthManager::getAuthProviders();

        $requestProcessed = false;

        foreach ($authProviders as $provider) {
            if ($provider->detectAuth()) {

                $profileInfo = $provider->processAuth();

                if ($profileInfo && !empty($profileInfo['id']) && !empty($profileInfo['email'])) {

                    $profile = $this->getSocialLoginProfile(
                        $profileInfo['email'],
                        $provider->getName(),
                        $profileInfo['id']
                    );

                    if ($profile) {
                        if ($profile->isEnabled()) {
                            \XLite\Core\Auth::getInstance()->loginProfile($profile);
                            $this->setReturnURL($this->buildURL());

                        } else {
                            \XLite\Core\TopMessage::addError('Profile is disabled');
                            $this->setReturnURL($this->buildURL('login'));
                        }

                    } else {
                        $provider = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                            ->findOneBy(array('login' => $profileInfo['email'], 'order' => null))
                            ->getSocialLoginProvider();

                        if ($provider) {
                            $signInVia = 'Please sign in with ' . $provider . '.';
                        } else {
                            $signInVia = 'Profile with the same e-mail address already registered. '
                                . 'Please sign in the classic way.';
                        }

                        \XLite\Core\TopMessage::addError($signInVia);
                        $this->setReturnURL($this->buildURL('login'));
                    }

                    $requestProcessed = true;
                }
            }
        }

        if (!$requestProcessed) {
            \XLite\Core\TopMessage::addError('We we\'re unable to process this request');
            $this->setReturnURL($this->buildURL('login'));
        }
    }

    /**
     * Fetches an existing social login profile or creates new
     * 
     * @param string $login          E-mail address
     * @param string $socialProvider SocialLogin auth provider
     * @param string $socialId       SocialLogin provider-unique id
     *  
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getSocialLoginProfile($login, $socialProvider, $socialId)
    {
        $profile = \XLite\Core\Database::getRepo('\XLite\Model\Profile')->findOneBy(
            array(
                'socialLoginProvider'   => $socialProvider,
                'socialLoginId'         => $socialId,
                'order'              => null,
            )
        );

        if (!$profile) {
            $profile = new \XLite\Model\Profile();
            $profile->setLogin($login);
            $profile->setSocialLoginProvider($socialProvider);
            $profile->setSocialLoginId($socialId);

            $existingProfile = \XLite\Core\Database::getRepo('XLite\Model\Profile')
                ->findOneBy(array('login' => $login, 'order' => null));

            if ($existingProfile) {
                $profile = null;
            } else {
                $profile->create();
            }
        }

        return $profile;
    }
}
