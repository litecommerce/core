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

namespace XLite\Module\CDev\XPaymentsConnector\Controller\Admin;

/**
 * Module settings
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends \XLite\Controller\Admin\Module
implements \XLite\Base\IDecorator
{
    /**
     * Test request to X-Payments
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionXpcTest()
    {
        $pm = new \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment();
        if ($pm->sendTestRequest()) {
            \XLite\Core\TopMessage::getInstance()->add('Test transaction successfully complete.');

        } else {
            \XLite\Core\TopMessage::getInstance()->add(
                'Test transaction failed. Please check the X-Payment Connector settings and try again.'
                . ' If all options is ok review your X-Payments settings and'
                . ' make sure you have properly defined shopping cart properties.',
                \XLite\Core\TopMessage::ERROR
            );
        }
    }

    /**
     * Request payment configurations
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionXpcRequest()
    {
        $this->session->set('xpc_payment_methods', array());

        $pm = new \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment();
        $list = $pm->requestPaymentMethods();

        if (!$list) {
            \XLite\Core\TopMessage::getInstance()->add(
                'Error had occured during the requesting of payment methods from X-Payments',
                \XLite\Core\TopMessage::ERROR
            );

        } else {
            $this->session->set('xpc_payment_methods', $list);
        }
    }

    /**
     * Import requested payment configurations
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionXpcImport()
    {
        $types = array(
            'is_auth'        => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_AUTH,
            'is_capture'     => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_CAPTURE,
            'is_void'        => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_VOID,
            'is_refund'      => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_REFUND,
            'is_part_refund' => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_PART_REFUND,
            'is_get_info'    => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_GET_INFO,
            'is_accept'      => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_ACCEPT,
            'is_decline'     => \XLite\Module\CDev\XPaymentsConnector\Model\PaymentMethod\XPayment::TRAN_TYPE_DECLINE,
        );

        $conf = new \XLite\Module\CDev\XPaymentsConnector\Model\Configuration();
        $conf->deleteAll();

        foreach ($this->session->get('xpc_payment_methods') as $pm) {
            $conf = new \XLite\Module\CDev\XPaymentsConnector\Model\Configuration();
            $conf->set('confid', $pm['id']);
            $conf->set('name', $pm['name']);
            $conf->set('module', $pm['moduleName']);
            $conf->set('hash', $pm['settingsHash']);
            $conf->set('auth_exp', $pm['authCaptureInfo']['authExp']);
            $conf->set('capture_min', $pm['authCaptureInfo']['captMinLimit']);
            $conf->set('capture_max', $pm['authCaptureInfo']['captMaxLimit']);

            foreach ($types as $fn => $code) {
                $conf->set(
                    $fn,
                    (isset($pm['transactionTypes'][$code]) && $pm['transactionTypes'][$code]) ? 'Y' : 'N'
                );
            }

            $conf->create();
        }

        $this->session->set('xpc_payment_methods', array());

        \XLite\Core\TopMessage::getInstance()->add('Payment methods have been successfully imported');
    }

    /**
     * Clear requested payment configurations
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionXpcClear()
    {
        $this->session->set('xpc_payment_methods', null);
    }

    /**
     * Chec- X-Payments connector is configured or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isXPCConfigured()
    {
        return \XLite\Module\CDev\XPaymentsConnector\Main::isConfigured();
    }

    /**
     * Check - has requested payment methods list or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasPaymentMethodsList()
    {
        return 0 < count($this->getPaymentMethodsList());
    }

    /**
     * Get payment methods list 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPaymentMethodsList()
    {
        return is_array($this->session->get('xpc_payment_methods'))
            ? $this->session->get('xpc_payment_methods')
            : array();
    }

    /**
     * Check - can payment configuration specified transaction type 
     * 
     * @param array  $pm   Payment configuration
     * @param string $type Transaction type
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canTransactionType(array $pm, $type)
    {
        return isset($pm['transactionTypes'][$type])
            && $pm['transactionTypes'][$type];
    }

    /**
     * Check - is payment configurations imported early or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPaymentMethodsImported()
    {
        $conf = new \XLite\Module\CDev\XPaymentsConnector\Model\Configuration();

        return 0 < count($conf->findAll());
    }
}
