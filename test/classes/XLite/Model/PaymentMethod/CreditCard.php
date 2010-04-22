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
 * CreditCard-based payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_PaymentMethod_CreditCard extends XLite_Model_PaymentMethod
{
    const CALL_CHECKOUT = 'checkout';
    const CALL_BACK     = 'callback';

    /**
     * Form template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $formTemplate = 'checkout/credit_card.tpl';

    /**
     * Use secure site part
     * 
     * @var    boolean
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $secure = true;

    /**
     * Process cart
     * 
     * @param XLite_Model_Cart $cart Cart
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function process(XLite_Model_Cart $cart)
    {
        // save CC details to order
        $cart->set('details', $this->cc_info);

        $detailLabels = array(
            'cc_number' => 'Credit card number',
            'cc_type'   => 'Credit card type',
            'cc_name'   => 'Cardholder\'s name',
            'cc_date'   => 'Expiration date',
            'cc_cvv2'   => 'Credit Card Code',
        );

        if (
            'SW' == $this->cc_info['cc_type']
            || 'SO' == $this->cc_info['cc_type']
        ) {
            $detailLabels['cc_start_date'] = 'Start date';
            $detailLabels['cc_issue'] = 'Issue no.';
        }

        $cart->set('detailLabels', $detailLabels);
        $cart->set('status', 'Q');

        $cart->update();
    }

    /**
     * Get payment info 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPaymentInfo()
    {
        return isset(XLite_Core_Request::getInstance()->cc_info)
            ? XLite_Core_Request::getInstance()->cc_info
            : array();
    }

    /**
     * Handle request 
     * 
     * @param XLite_Model_Cart $cart Cart
     * @param string           $type Call type
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(XLite_Model_Cart $cart, $type = self::CALL_CHECKOUT)
    {
        $this->cc_info = $this->getPaymentInfo();
        $this->process($cart);
        $status = $cart->get('status');

        return ('Q' == $status || 'P' == $status)
            ? self::PAYMENT_SUCCESS
            : self::PAYMENT_FAILURE;
    }

    /**
     * Get card types 
     * 
     * @return array of XLite_Model_Card
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCardTypes()
    {
        $card = new XLite_Model_Card();

        return $card->findAll();
    }

    /**
     * Set cart details dield 
     * 
     * @param XLite_Model_Cart $cart  Cart
     * @param string           $code  Field code
     * @param string           $name  Field name
     * @param mixed            $value Field value
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setDetailsField(XLite_Model_Cart $cart, $code, $name, $value)
    {
        $cart->setComplex('details.' . $code, $value);
        $cart->setComplex('detailLabels.' . $code, $name);
    }

    /**
     * Check - payment method is configured or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isConfigured()
    {
        return true;
    }
}
