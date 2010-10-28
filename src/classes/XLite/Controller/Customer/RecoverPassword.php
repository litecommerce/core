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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Customer;

/**
 * Password recovery controller
 * TODO: full refactoring is needed
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class RecoverPassword extends \XLite\Controller\Customer\ACustomer
{
    public $params = array('target', "mode", "email", "link_mailed");


    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->locationPath->addNode(new \XLite\Model\Location('Help zone'));
    }

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Recover password';
    }

    protected function action_recover_password()
    {
        // show recover message if email is valid
        if ($this->requestRecoverPassword($this->get('email'))) {
            $this->set('mode', 'recoverMessage'); // redirect to passwordMessage mode
            $this->set('link_mailed', true); // redirect to passwordMessage mode
        } else {
            $this->set('valid', false);
            $this->set('noSuchUser', true);
        }
    }

    protected function action_confirm()
    {
        if (!is_null($this->get('email')) && isset($_GET['request_id'])) {
            if ($this->recoverPassword($this->get('email'), $_GET['request_id'])) {
                $this->set('mode', 'recoverMessage');
            }
        }
    }

    /**
     * requestRecoverPassword 
     * 
     * @param mixed $email ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function recoverPassword($email, $requestID) 
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
