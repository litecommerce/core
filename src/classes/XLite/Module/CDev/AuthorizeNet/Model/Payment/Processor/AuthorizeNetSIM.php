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

namespace XLite\Module\CDev\AuthorizeNet\Model\Payment\Processor;

/**
 * Authorize.Net SIM processor
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AuthorizeNetSIM extends \XLite\Model\Payment\Base\WebBased
{
    /**
     * AVS messages
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $avserr = array(
        'A' => 'Address (Street) matches, ZIP does not',
        'B' => 'Address information not provided for AVS check',
        'E' => 'AVS error',
        'G' => 'Non-U.S. Card Issuing Bank',
        'N' => 'No Match on Address (Street) or ZIP',
        'P' => 'AVS not applicable for this transaction',
        'R' => 'Retry - System unavailable or timed out',
        'S' => 'Service not supported by issuer',
        'U' => 'Address information is unavailable',
        'W' => '9 digit ZIP matches, Address (Street) does not',
        'X' => 'Address (Street) and 9 digit ZIP match',
        'Y' => 'Address (Street) and 5 digit ZIP match',
        'Z' => '5 digit ZIP matches, Address (Street) does not',
    );

    /**
     * CVV messages
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cvverr = array(
        'M' => 'Match',
        'N' => 'No Match',
        'P' => 'Not Processed',
        'S' => 'Should have been present',
        'U' => 'Issuer unable to process',
    );

    /**
     * Error messages 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $err = array(
        '1' => 'This transaction has been approved.',
        '2' => 'This transaction has been declined.',
        '3' => 'This transaction has been declined.',
        '4' => 'This transaction has been declined. The code returned from the processor indicating that the card used needs to be picked up.',
        '5' => 'A valid amount is required. The value submitted in the amount field did not pass validation for a number.',
        '6' => 'The credit card number is invalid.',
        '7' => 'The credit card expiration date is invalid. The format of the date submitted was incorrect.',
        '8' => 'The credit card has expired.',
        '9' => 'The ABA code is invalid. The value submitted in the x_Bank_ABA_Code field did not pass validation or was not for a valid financial institution.',
        '10' => 'The account number is invalid. The value submitted in the x_Bank_Acct_Num field did not pass validation.',
        '11' => 'A duplicate transaction has been submitted. A transaction with identical amount and credit card information was submitted two minutes prior.',
        '12' => 'An authorization code is required but not present. A transaction that required x_Auth_Code to be present was submitted without a value.',
        '13' => 'The merchant Login ID is invalid or the account is inactive.',
        '14' => 'The Referrer or Relay Response URL is invalid. The Relay Response or Referrer URL does not match the merchant\'s configured value(s) or is absent. Applicable only to SIM and WebLink APIs.',
        '15' => 'The transaction ID is invalid. The transaction ID value is non-numeric or was not present for a transaction that requires it (i.e., VOID, PRIOR_AUTH_CAPTURE, and CREDIT).',
        '16' => 'The transaction was not found. The transaction ID sent in was properly formatted but the gateway had no record of the transaction.',
        '17' => 'The merchant does not accept this type of credit card. The merchant was not configured to accept the credit card submitted in the transaction.',
        '18' => 'ACH transactions are not accepted by this merchant. The merchant does not accept electronic checks.',
        '19' => 'An error occurred during processing. Please try again in 5 minutes.',
        '20' => 'An error occurred during processing. Please try again in 5 minutes.',
        '21' => 'An error occurred during processing. Please try again in 5 minutes.',
        '22' => 'An error occurred during processing. Please try again in 5 minutes.',
        '23' => 'An error occurred during processing. Please try again in 5 minutes.',
        '24' => 'The Nova Bank Number or Terminal ID is incorrect. Call Merchant Service Provider.',
        '25' => 'An error occurred during processing. Please try again in 5 minutes.',
        '26' => 'An error occurred during processing. Please try again in 5 minutes.',
        '27' => 'The transaction resulted in an AVS mismatch. The address provided does not match billing address of cardholder.',
        '28' => 'The merchant does not accept this type of credit card. The Merchant ID at the processor was not configured to accept this card type.',
        '29' => 'The PaymentTech identification numbers are incorrect. Call Merchant Service Provider.',
        '30' => 'The configuration with the processor is invalid. Call Merchant Service Provider.',
        '31' => 'The FDC Merchant ID or Terminal ID is incorrect. Call Merchant Service Provider. The merchant was incorrectly set up at the processor.',
        '32' => 'This reason code is reserved or not applicable to this API.',
        '33' => 'FIELD cannot be left blank. The word FIELD will be replaced by an actual field name. This error indicates that a field the merchant specified as required was not filled in.',
        '34' => 'The VITAL identification numbers are incorrect. Call Merchant Service Provider. The merchant was incorrectly set up at the processor.',
        '35' => 'An error occurred during processing. Call Merchant Service Provider. The merchant was incorrectly set up at the processor.',
        '36' => 'The authorization was approved, but settlement failed.',
        '37' => 'The credit card number is invalid.',
        '38' => 'The Global Payment System identification numbers are incorrect. Call Merchant Service Provider. The merchant was incorrectly set up at the processor.',
        '39' => 'The supplied currency code is either invalid, not supported, not allowed for this merchant or doesn\'t have an exchange rate.',
        '40' => 'This transaction must be encrypted.',
        '41' => 'This transaction has been declined. Only merchants set up for the FraudScreen.Net service would receive this decline. This code will be returned if a given transaction\'s fraud score is higher than the threshold set by the merchant.',
        '42' => 'There is missing or invalid information in a required field. This is applicable only to merchants processing through the Wells Fargo SecureSource product who have requirements for transaction submission that are different from merchants not processing through Wells Fargo.',
        '43' => 'The merchant was incorrectly set up at the processor. Call your merchant service provider. The merchant was incorrectly set up at the processor.',
        '44' => 'This transaction has been declined. The merchant would receive this error if the Card Code filter has been set in the Merchant Interface and the transaction received an error code from the processor that matched the rejection criteria set by the merchant.',
        '45' => 'This transaction has been declined. This error would be returned if the transaction received a code from the processor that matched the rejection criteria set by the merchant for both the AVS and Card Code filters.',
        '46' => 'Your session has expired or does not exist. You must log in to continue working.',
        '47' => 'The amount requested for settlement may not be greater than the original amount authorized. This occurs if the merchant tries to capture funds greater than the amount of the original authorization-only transaction.',
        '48' => 'This processor does not accept partial reversals. The merchant attempted to settle for less than the originally authorized amount.',
        '49' => 'A transaction amount greater than $99,999 will not be accepted.',
        '50' => 'This transaction is awaiting settlement and cannot be Credits or refunds may only be performed against settled transactions. The transaction refunded. against which the credit/refund was submitted has not been settled, so a credit cannot be issued.',
        '51' => 'The sum of all credits against this transaction is greater than the original transaction amount.',
        '52' => 'The transaction was authorized, but the client could not be notified; the transaction will not be settled.',
        '53' => 'The transaction type was invalid for ACH transactions. If x_Method = ECHECK, x_Type cannot be set to CAPTURE_ONLY.',
        '54' => 'The referenced transaction does not meet the criteria for issuing a credit.',
        '55' => 'The sum of credits against the referenced transaction would exceed the original debit amount. The transaction is rejected if the sum of this credit and prior credits exceeds the original debit amount',
        '56' => 'This merchant accepts ACH transactions only; no credit card transactions are accepted. The merchant processes eCheck transactions only and does not accept credit cards.',
        '57' => 'An error occurred in processing. Please try again in 5 minutes.',
        '58' => 'An error occurred in processing. Please try again in 5 minutes.',
        '59' => 'An error occurred in processing. Please try again in 5 minutes.',
        '60' => 'An error occurred in processing. Please try again in 5 minutes.',
        '61' => 'An error occurred in processing. Please try again in 5 minutes.',
        '62' => 'An error occurred in processing. Please try again in 5 minutes.',
        '63' => 'An error occurred in processing. Please try again in 5 minutes.',
        '64' => 'The referenced transaction was not approved. This error is applicable to Wells Fargo SecureSource merchants only. Credits or refunds cannot be issued against transactions that were not authorized.',
        '65' => 'This transaction has been declined. The transaction was declined because the merchant configured their account through the Merchant Interface to reject transactions with certain values for a Card Code mismatch.',
        '66' => 'This transaction cannot be accepted for processing. The transaction did not meet gateway security guidelines.',
        '67' => 'The given transaction type is not supported for this merchant. This error code is applicable to merchants using the Wells Fargo SecureSource product only. This product does not allow transactions of type CAPTURE_ONLY.',
        '68' => 'The version parameter is invalid. The value submitted in x_Version was invalid.',
        '69' => 'The transaction type is invalid. The value submitted in x_Type was invalid.',
        '70' => 'The transaction method is invalid.The value submitted in x_Method was invalid.',
        '71' => 'The bank account type is invalid. The value submitted in x_Bank_Acct_Type was invalid.',
        '72' => 'The authorization code is invalid.The value submitted in x_Auth_Code was more than six characters in length.',
        '73' => 'The driver\'s license date of birth is invalid. The format of the value submitted in x_Drivers_License_Num was invalid.',
        '74' => 'The duty amount is invalid. The value submitted in x_Duty failed format validation.',
        '75' => 'The freight amount is invalid. The value submitted in x_Freight failed format validation.',
        '76' => 'The tax amount is invalid. The value submitted in x_Tax failed format validation.',
        '77' => 'The SSN or tax ID is invalid. The value submitted in x_Customer_Tax_ID failed validation.',
        '78' => 'The Card Code (CVV2/CVC2/CID) is invalid. The value submitted in x_Card_Code failed format validation.',
        '79' => 'The driver\'s license number is invalid. The value submitted in x_Drivers_License_Num failed format validation.',
        '80' => 'The driver\'s license state is invalid. The value submitted in x_Drivers_License_State failed format validation.',
        '81' => 'The requested form type is invalid. The merchant requested an integration method not compatible with the ADC Direct Response API.',
        '82' => 'Scripts are only supported in version 2.5. The system no longer supports version 2.5; requests cannot be posted to scripts.',
        '83' => 'The requested script is either invalid or no longer supported. The system no longer supports version 2.5; requests cannot be posted to scripts.',
        '84' => 'This reason code is reserved or not applicable to this API.',
        '85' => 'This reason code is reserved or not applicable to this API.',
        '86' => 'This reason code is reserved or not applicable to this API.',
        '87' => 'This reason code is reserved or not applicable to this API.',
        '88' => 'This reason code is reserved or not applicable to this API.',
        '89' => 'This reason code is reserved or not applicable to this API.',
        '90' => 'This reason code is reserved or not applicable to this API.',
        '91' => 'Version 2.5 is no longer supported.',
        '92' => 'The gateway no longer supports the requested method of integration.',
        '93' => 'A valid country is required. This code is applicable to Wells Fargo SecureSource merchants only. Country is required field and must contain the value of a supported country.',
        '94' => 'The shipping state or country is invalid. This code is applicable to Wells Fargo SecureSource merchants only.',
        '95' => 'A valid state is required. This code is applicable to Wells Fargo SecureSource merchants only.',
        '96' => 'This country is not authorized for buyers. This code is applicable to Wells Fargo SecureSource merchants only. Country is a required field and must contain the value of a supported country.',
        '97' => 'This transaction cannot be accepted. Applicable only to SIM API. Fingerprints are only valid for a short period of time. This code indicates that the transaction fingerprint has expired.',
        '98' => 'This transaction cannot be accepted. Applicable only to SIM API. The transaction fingerprint has already been used.',
        '99' => 'This transaction cannot be accepted. Applicable only to SIM API. The server-generated fingerprint does not match the merchant-specified fingerprint in the x_FP_Hash field.',
        '100' => 'The eCheck type is invalid. Applicable only to eCheck. The value specified in the x_Echeck_type field is invalid.',
        '101' => 'The given name on the account and/or the account type does not match the actual account. Applicable only to eCheck. The specified name on the account and/or the account type do not match the NOC record for this account.',
        '102' => 'This request cannot be accepted. A password or transaction key was submitted with this WebLink request. This is a high security risk.',
        '103' => 'This transaction cannot be accepted. A valid fingerprint, transaction key, or password is required for this transaction.',
        '104' => 'This transaction is currently under review. Applicable only to eCheck. The value submitted for country failed validation.',
        '105' => 'This transaction is currently under review. Applicable only to eCheck. The values submitted for city and country failed validation.',
        '106' => 'This transaction is currently under review. Applicable only to eCheck. The value submitted for company failed validation.',
        '107' => 'This transaction is currently under review. Applicable only to eCheck. The value submitted for bank account name failed validation.',
        '108' => 'This transaction is currently under review. Applicable only to eCheck. The values submitted for first name and last name failed validation.',
        '109' => 'This transaction is currently under review. Applicable only to eCheck. The values submitted for first name and last name failed validation.',
        '110' => 'This transaction is currently under review. Applicable only to eCheck. The value submitted for bank account name does not contain valid characters.',
        '111' => 'A valid billing country is required. This code is applicable to Wells Fargo SecureSource merchants only.',
        '112' => 'A valid billing state/province is This code is applicable to Wells Fargo',
        '127' => 'The transaction resulted in an AVS mismatch. The address provided does not match billing address of cardholder. The system-generated void for the original AVS-rejected transaction failed.',
        '141' => 'This transaction has been declined. The system-generated void for the original FraudScreen-rejected transaction failed.',
        '145' => 'This transaction has been declined. The system-generated void for the original card code-rejected and AVS-rejected transaction failed.',
        '152' => 'The transaction was authorized, but the client could not be notified; the transaction will not be settled. The system-generated void for the original transaction failed. The response for the original transaction could not be communicated to the client.',
        '165' => 'This transaction has been declined. The system-generated void for the original card code-rejected transaction failed.',
    );

    /**
     * Get operation types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOperationTypes()
    {
        return array(
            self::OPERATION_SALE,
            self::OPERATION_AUTH,
        );
    }

    /**
     * Get settings widget or template 
     * 
     * @return string Widget class name or template path
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSettingsWidget()
    {
        return 'modules/CDev/AuthorizeNet/config.tpl';
    }

    /**
     * Get redirect form URL 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormURL()
    {
        return $this->getSetting('test')
            ? 'https://test.authorize.net/gateway/transact.dll'
            : 'https://secure.authorize.net/gateway/transact.dll';
    }

    /**
     * Get redirect form fields list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormFields()
    {
        mt_srand();
        $sequence = mt_rand(1, 1000);

        $tstamp = gmdate('U');

        $strin = $this->getSetting('login') . '^'
            . $sequence . '^'
            . $tstamp . '^'
            . round($this->transaction->getValue(), 2) . '^';
        $hash = $this->getHMAC(
            $this->getSetting('key'),
            $string
        );

        $bState = $this->getOrder()->getProfile()->getBillingAddress()->getState()->getCode()
            ? $this->getOrder()->getProfile()->getBillingAddress()->getState()->getCode()
            : 'n/a';

        $sState = $this->getOrder()->getProfile()->getShippingAddress()->getState()->getCode()
            ? $this->getOrder()->getProfile()->getShippingAddress()->getState()->getCode()
            : 'n/a';

        switch ($this->transaction->getType()) {
            case self::TRANSACTION_AUTH:
                $type = 'AUTH_ONLY';
                break;

            default:
                $type = 'AUTH_CAPTURE';
        }

        return array(
            'x_test_request'  => $this->getSetting('test') ? 'TRUE' : 'FALSE',
            'x_login'         => $this->getSetting('login'),
            'x_type'          => $type,
            'x_fp_sequence'   => $sequence,
            'x_fp_timestamp'  => $tstamp,
            'x_fp_hash'       => $hash,
            'x_show_form'     => 'PAYMENT_FORM',
            'x_amount'        => round($this->transaction->getValue(), 2),
            'x_currency_code' => $this->getSetting('currency'),
            'x_method'        => 'CC',
            'x_first_name'    => $this->getOrder()->getProfile()->getBillingAddress()->getFirstname(),
            'x_last_name'     => $this->getOrder()->getProfile()->getBillingAddress()->getLastname(),
            'x_phone'         => $this->getOrder()->getProfile()->getBillingAddress()->getPhone(),
            'x_email'         => $this->getOrder()->getProfile()->getLogin(),
            'x_cust_id'       => $this->getOrder()->getProfile()->getLogin(),
            'x_address'       => $this->getOrder()->getProfile()->getBillingAddress()->getStreet(),
            'x_city'          => $this->getOrder()->getProfile()->getBillingAddress()->getCity(),
            'x_state'         => $bState,
            'x_zip'           => $this->getOrder()->getProfile()->getBillingAddress()->getZipcode(),
            'x_country'       => $this->getOrder()->getProfile()->getBillingAddress()->getCountry()->getCountry(),
            'x_ship_to_first_name' => $this->getOrder()->getProfile()->getShippingAddres()->getFirstname(),
            'x_ship_to_last_name'  => $this->getOrder()->getProfile()->getShippingAddress()->getLastname(),
            'x_ship_to_address'    => $this->getOrder()->getProfile()->getShippingAddress()->getStreet(),
            'x_ship_to_city'       => $this->getOrder()->getProfile()->getShippingAddress()->getCity(),
            'x_ship_to_state'      => $sState,
            'x_ship_to_zip'        => $this->getOrder()->getProfile()->getShippingAddress()->getZipcode(),
            'x_ship_to_country'    => $this->getOrder()->getProfile()->getShippingAddress()->getCountry()->getCountry(),
            'x_invoice_num'        => $this->transaction->getTransactionId(),
            'x_relay_response'     => 'TRUE',
            'x_relay_url'          => $this->getReturnURL('x_invoice_num'),
            'x_customer_ip'        => $this->getClientIP(),
        );
    }

    /**
     * Process return
     * 
     * @param \XLite\Model\Payment\Transaction $transaction Return-owner transaction
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processReturn(\XLite\Model\Payment\Transaction $transaction)
    {
        parent::processReturn($transaction);

        $request = \XLite\Core\Request::getInstance();

        $status = 1 == $request->x_response_code ? $transaction::STATUS_SUCCESS : $transaction::STATUS_FAILED;

        if (isset($request->x_response_reason_text)) {
            $this->getOrder()->setDetail('response', $request->x_response_reason_text, 'Response');
            $this->transaction->setNote($request->x_response_reason_text);

        } elseif (isset($this->err[$request->x_response_reason_code])) {
            $this->getOrder()->setDetail('response', $this->err[$request->x_response_reason_code], 'Response');
            $this->transaction->setNote($this->err[$request->x_response_reason_code]);
        }

        if ($request->x_auth_code) {
            $this->getOrder()->setDetail('authCode', $request->x_auth_code, 'Auth code');
        }

        if ($request->x_trans_id) {
            $this->getOrder()->setDetail('transId', $request->x_trans_id, 'Transaction ID');
        }

        if ($request->x_response_subcode) {
            $this->getOrder()->setDetail('responseSubcode', $request->x_response_subcode, 'Response subcode');
        }

        if (isset($request->x_avs_code) && isset($this->avserr[$request->x_avs_code])) {
            $this->getOrder()->setDetail('avs', $this->avserr[$request->x_avs_code], 'AVS status');
        }

        if (isset($request->x_CVV2_Resp_Code) && isset($this->cvverr[$request->x_CVV2_Resp_Code])) {
            $this->getOrder()->setDetail('cvv', $this->cvverr[$request->x_CVV2_Resp_Code], 'CVV status');
        }

        if (!$this->checkTotal($request->x_amount)) {
            $status = $transaction::STATUS_FAILED;
        }

        $this->transaction->setStatus($status);
    }

    /**
     * Check - payment method is configured or not
     * 
     * @param \XLite\Model\Payment\Method $method Payment method
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isConfigured(\XLite\Model\Payment\Method $method)
    {
        return parent::isConfigured()
            && $method->getSetting('login')
            && $method->getSetting('type');
    }

    /**
     * Get return type 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReturnType()
    {
        return self::RETURN_TYPE_HTML_REDIRECT;
    }

    /**
     * Get RFC 2104 HMAC (MD5)
     * 
     * @param string $key  Key
     * @param string $data Data
     *  
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHMAC($key, $data)
    {
        if (function_exists('hash_hmac')) {
            $result = hash_hmac('md5', $data, $key);

        } else {

            /**
             * RFC 2104 HMAC implementation for php. Creates an md5 HMAC.
             * Eliminates the need to install mhash to compute a HMAC. Hacked by Lance Rushing
             */

            $b = 64; // byte length for md5
            if (strlen($key) > $b) {
                $key = pack('H*', md5($key));
            }

            $key  = str_pad($key, $b, chr(0x00));
            $ipad = str_pad('', $b, chr(0x36));
            $opad = str_pad('', $b, chr(0x5c));
            $kIpad = $key ^ $ipad ;
            $kOpad = $key ^ $opad;

            $result = md5($kOpad . pack('H*', md5($kIpad . $data)));
        }

        return $result;
    }
}
