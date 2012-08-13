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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Customer;

/**
 * Password recovery controller
 * TODO: full refactoring is needed
 *
 */
class RecoverPassword extends \XLite\Controller\Customer\ACustomer
{
    /**
     * params
     *
     * @var string
     */
    protected $params = array('target', 'mode', 'email', 'link_mailed');

    /**
     * Add the base part of the location path
     *
     * @return void
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->addLocationNode('Help zone');
    }

    /**
     * Common method to determine current location
     *
     * @return array
     */
    protected function getLocation()
    {
        return 'Recover password';
    }

    /**
     * doActionRecoverPassword
     *
     * @return void
     */
    protected function doActionRecoverPassword()
    {
        // show recover message if email is valid
        if ($this->requestRecoverPassword($this->get('email'))) {

            $this->setReturnURL(
                $this->buildURL(
                    'recover_password',
                    '',
                    array(
                        'mode'        => 'recoverMessage',
                        'link_mailed' => 1,
                        'email'       => $this->get('email'),
                    )
                )
            );

        } else {

            $this->setReturnURL($this->buildURL('recover_password', '', array('valid' => 0)));

            \XLite\Core\TopMessage::addError('There is no user with specified email address');
        }
    }

    /**
     * doActionConfirm
     *
     * @return void
     */
    protected function doActionConfirm()
    {
        if (!is_null($this->get('email')) && \XLite\Core\Request::getInstance()->request_id) {

            if ($this->doPasswordRecovery($this->get('email'), \XLite\Core\Request::getInstance()->request_id)) {
                $this->setReturnURL(
                    $this->buildURL(
                        'recover_password',
                        '',
                        array(
                            'mode'  => 'recoverMessage',
                            'email' => $this->get('email'),
                        )
                    )
                );
            }
        }
    }

    /**
     * requestRecoverPassword
     *
     * @param mixed $email ____param_comment____
     *
     * @return void
     */
    protected function requestRecoverPassword($email)
    {
        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin($email);

        if (isset($profile)) {
            \XLite\Core\Mailer::sendRecoverPasswordRequest($profile->getLogin(), $profile->getPassword());
        }

        return isset($profile);
    }

    /**
     * recoverPassword
     *
     * @param mixed $email     ____param_comment____
     * @param mixed $requestID ____param_comment____
     *
     * @return void
     */
    protected function doPasswordRecovery($email, $requestID)
    {
        $result = true;

        $profile = \XLite\Core\Database::getRepo('XLite\Model\Profile')->findByLogin($email);

        if (!isset($profile) || $profile->getPassword() != $requestID) {
            $result = false;

        } else {

            $pass = generate_code();
            $profile->setPassword(md5($pass));

            $result = $profile->update();

            if ($result) {
                // Send notification to the user
                \XLite\Core\Mailer::sendRecoverPasswordConfirmation($email, $pass);
            }
        }

        return $result;
    }
}
