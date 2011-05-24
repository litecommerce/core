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
 * Login page controller
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Login extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Index in request array; the secret token used for authorization
     */
    const SECURE_TOKEN = 'secureToken';


    /**
     * Controlelr parameters
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target', 'mode');

    /**
     * Profile
     *
     * @var   \XLite\Model\Profile|integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $profile;


    /**
     * handleRequest 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function handleRequest()
    {
        if (
            \XLite\Core\Auth::getInstance()->isLogged()
            && 'logoff' !== \XLite\Core\Request::getInstance()->{static::PARAM_ACTION}
        ) {
            $this->setReturnURL($this->buildURL());
        }

        return parent::handleRequest();
    }

    /**
     * Perform some actions after the "login" action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function redirectFromLogin()
    {
        $url = $this->getRedirectFromLoginURL();

        if (isset($url)) {
            \XLite\Core\CMSConnector::isCMSStarted()
                ? \XLite\Core\Operator::redirect($url, true)
                : $this->setReturnURL($url);
        }
    }

    /**
     * Get the full URL of the page
     *
     * @param string  $url    Relative URL OPTIONAL
     * @param boolean $secure Flag to use HTTPS OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getShopURL($url = '', $secure = false)
    {
        $add = (strpos($url, '?') ? '&' : '?') . 'feed=' . \XLite\Core\Request::getInstance()->action;

        return parent::getShopURL($url . $add, $secure);
    }


    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Authentication';
    }

    /**
     * Return URL to redirect from login
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRedirectFromLoginURL()
    {
        return null;
    }

    /**
     * Log in using the login and password from request
     *
     * @return \XLite\Model\Profile
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performLogin()
    {
        $data = \XLite\Core\Request::getInstance()->getData();
        $token = empty($data[self::SECURE_TOKEN]) ? null : $data[self::SECURE_TOKEN];

        return \XLite\Core\Auth::getInstance()->login($data['login'], $data['password'], $token);
    }

    /**
     * Login
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionLogin()
    {
        $this->profile = $this->performLogin();

        if ($this->profile === \XLite\Core\Auth::RESULT_ACCESS_DENIED) {
            $this->set('valid', false);
            $this->addLoginFailedMessage(\XLite\Core\Auth::RESULT_ACCESS_DENIED);
            \XLite\Logger::getInstance()
                ->log(sprintf('Log in action is failed (%s)', \XLite\Core\Request::getInstance()->login), LOG_WARNING);

        } else {

            $this->setReturnURL(\XLite\Core\Request::getInstance()->returnURL);

            if (!$this->getReturnURL()) {
                $this->setReturnURL(
                    $this->getCart()->isEmpty()
                    ? \XLite\Core\Converter::buildURL()
                    : \XLite\Core\Converter::buildURL('cart')
                );
            }

            $this->getCart()->setProfile($this->profile);

            $this->updateCart();
        }
    }

    /**
     * Log out
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionLogoff()
    {
        \XLite\Core\Auth::getInstance()->logoff();

        $this->setReturnURL(\XLite\Core\Converter::buildURL());

        if (!$this->getCart()->isEmpty()) {

            if ('Y' == \XLite\Core\Config::getInstance()->Security->logoff_clear_cart) {
                \XLite\Core\Database::getEM()->remove($this->getCart());
                \XLite\Core\Database::getEM()->flush();

            } else {
                $this->updateCart();
            }
        }
    }

    /**
     * Perform some actions before redirect
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function actionPostprocessLogin()
    {
        $this->redirectFromLogin();
    }

    /**
     * Add top message if log in is failed
     * 
     * @param mixed $result Result of log in procedure
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addLoginFailedMessage($result)
    {
        if (\XLite\Core\Auth::RESULT_ACCESS_DENIED === $result) {
            \XLite\Core\TopMessage::addError('Invalid login or password');
        }
    }
}
