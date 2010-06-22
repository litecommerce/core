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
 * Payment methods
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_PaymentMethods extends XLite_Controller_Admin_Abstract
{
    protected $configurableMethods = null;

    function hasConfigurableMethods()
    {
        if (is_null($this->configurableMethods)) {

            $this->configurableMethods = false;
            $pm = new XLite_Model_PaymentMethod();
            
            foreach ($pm->readAll() as $pm) {
                if (!is_null($pm->configurationTemplate)) {
                    $this->configurableMethods = true;
                    break;
                }
            }
        }

        return $this->configurableMethods;
    }

    function action_update()
    {
    	$default_offline_payment = $this->config->Payments->default_offline_payment;

        foreach ($this->data as $id => $data) {
            if (array_key_exists('enabled', $data)) {
                $data['enabled'] = 1;
            } else {
                $data['enabled'] = 0;
            }

            if ($data['payment_method'] == $default_offline_payment && !$data['enabled']) {

                XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
                    array(
                        'category' =>'Payments',
                        'name'     => 'default_offline_payment',
                        'value'    =>''
                    )
                );
            }

            $payment_method = new XLite_Model_PaymentMethod();
            $payment_method->setProperties($data);
            $payment_method->read();
            $payment_method->update();
        }
    }

    function action_default_payment()
    {
        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
            array(
                'category' => 'Payments',
                'name'     => 'default_offline_payment',
                'value'    => $this->default_payment
            )
        );

        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption(
            array(
                'category' => 'Payments',
                'name'     => 'default_select_payment',
                'value'    => $this->default_select_payment
            )
        );
    }
}
