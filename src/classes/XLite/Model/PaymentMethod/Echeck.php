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

namespace XLite\Model\PaymentMethod;

/**
 * e-check payment method
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Echeck extends \XLite\Model\PaymentMethod
{
    /**
     * Form template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $formTemplate = 'checkout/echeck.tpl';

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
     * Form fields list
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $formFields = array(
        'ch_routing_number' => 'ABA routing number',
        'ch_acct_number'    => 'Bank Account Number',
        'ch_type'           => 'Type of Account',
        'ch_bank_name'      => 'Bank name',
        'ch_acct_name'      => 'Account name',
        'ch_number'         => 'Check number',
    );

    /**
     * Process cart
     * 
     * @param \XLite\Model\Cart $cart Cart
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function process(\XLite\Model\Cart $cart)
    {
        $cart->setDetailLabels($this->formFields);

        $data = \XLite\Core\Request::getInstance()->ch_info;
        foreach ($this->formFields as $key => $name) {
            if (isset($data[$key])) {
                $cart->setDetail($key, $data[$key]);
            }
        }

        $cart->setStatus($cart::STATUS_QUEUED);
    }

    /**
     * Handle request 
     * 
     * @param \XLite\Model\Cart $cart Cart
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest(\XLite\Model\Cart $cart)
    {
        if ($this->checkRequest()) {
            $this->process($cart);

        } else {
            $cart->setStatus($cart::STATUS_FAILED);
        }

        return in_array($cart->getStatus(), array($cart::STATUS_QUEUED, $cart::STATUS_PROCESSED))
            ? self::PAYMENT_SUCCESS
            : self::PAYMENT_FAILURE;
    }

    /**
     * Check request 
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkRequest()
    {
        $data = \XLite\Core\Request::getInstance()->ch_info;

        $result = true;

        if (!is_array($data)) {

            \XLite\Core\TopMessage::getInstance()->add(
                'Check data is required',
                \XLite\Core\TopMessage::ERROR
            );
            $result = false;

        } else {

            $fields = $this->formFields;
            unset($fields['ch_number']);

            foreach ($fields as $key => $name) {
                if (!isset($data[$key]) || empty($data[$key])) {
                    \XLite\Core\TopMessage::getInstance()->add(
                        $name . ' is required',
                        \XLite\Core\TopMessage::ERROR
                    );
                    $result = false;
                }
            }
        }

        return $result;
    }
}
