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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_GiftCertificates_Model_PaymentMethod_GiftCertificate extends XLite_Model_PaymentMethod
{
    /**
     * Payment form template
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $formTemplate = 'modules/GiftCertificates/checkout.tpl';

    /**
     * Processor name 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $processorName = 'Gift certificate';

    /**
     * Handle request 
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart)
    {
        $gcid = trim(XLite_Core_Request::getInstance()->gcid);
        $gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($gcid);
        $setResult = $cart->setGC($gc);

        $result = self::PAYMENT_SILENT;

        if ($setResult != XLite_Module_GiftCertificates_Model_GiftCertificate::GC_OK) {

            // Failed
            $result = self::PAYMENT_FAILURE;

        } elseif ($cart->get('total') > 0) {

            // choose payment method once again
            $cart->set('payment_method', '');
            $cart->update();

            header('Location: ' . XLite_Core_Converter::buildUrl('checkout', '', array('mode' => 'paymentMethod')));

        } else {

            $cart->set('status', 'P');
            $cart->update();
            $result = self::PAYMENT_SUCCESS;
        }

        return $result;
    }

    /**
     * Return list of active payment methods
     *
     * @return XLite_Model_PaymentMethod
     * @access public
     * @since  3.0
     */
    public function getActiveMethods()
    {
        $result = array();

        $controller = XLite::getController();

        if (method_exists($controller, 'getCart') && !$controller->getCart()->canApplyGiftCertificate()) {
            $result = $this->findAll('enabled = \'1\' AND payment_method != \'gift_certificate\'');

        } else {
            $result = parent::getActiveMethods();
        }

        return $result;
    }


}
