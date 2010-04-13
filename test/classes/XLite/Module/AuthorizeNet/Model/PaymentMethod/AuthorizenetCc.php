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
 * Authorize.NET
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_AuthorizeNet_Model_PaymentMethod_AuthorizenetCc extends XLite_Model_PaymentMethod_CreditCard
{    
    /**
     * Processor (cache)
     * 
     * @var    XLite_Module_AuthorizeNet_Processor
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $processor = null;    
 
    /**
     * Configuration template 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $configurationTemplate = 'modules/AuthorizeNet/config.tpl';    

    /**
     * Processor name 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $processorName = 'Authorize.Net';

    /**
     * Get processor 
     * 
     * @return XLite_Module_AuthorizeNet_Processor
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getProcessor()
    {
        if (is_null($this->processor)) {
            $this->processor = new XLite_Module_AuthorizeNet_Processor();
        }

        return $this->processor;
    }

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
        return $this->getProcessor()->process($cart, $this);
    }

    /**
     * Handle configuration request
     *
     * @return mixed Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleConfigRequest()
    {
        return $this->getProcessor()->handleConfigRequest();
    }

    public function initRequest($cart, &$request)
    {
        $request->data['x_Method'] = 'CC';
        $request->data['x_Card_Num'] = $this->cc_info['cc_number'];
        $request->data['x_Exp_Date'] = $this->cc_info['cc_date'];
        $cc_name = trim($this->cc_info['cc_name']);
        if (strlen($cc_name)) {
            @list($fname, $lname) = explode(' ', $cc_name, 2);
            if (is_string($fname) && is_string($lname)) {
                $request->data['x_First_Name'] = $fname;
                $request->data['x_Last_Name']  = $lname;
            }    
        }
        if ($this->params['cvv2'] != '0') {
            $request->data['x_Card_Code'] = $this->cc_info['cc_cvv2'];
        }
    }
}
