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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_RecoverPassword extends XLite_Controller_Customer_ACustomer
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

        $this->locationPath->addNode(new XLite_Model_Location('Help zone'));
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

    function action_recover_password()
    {
        // show recover message if email is valid
        if ($this->auth->requestRecoverPassword($this->get('email'))) {
            $this->set('mode', "recoverMessage"); // redirect to passwordMessage mode
            $this->set('link_mailed', true); // redirect to passwordMessage mode
        } else {
            $this->set('valid', false);
            $this->set('noSuchUser', true);
        }
    }

    function action_confirm()
    {
        if (!is_null($this->get('email')) && isset($_GET['request_id'])) {
            if ($this->auth->recoverPassword($this->get('email'), $_GET['request_id'])) {
                $this->set('mode', "recoverMessage");
            }
        }
    }

}
