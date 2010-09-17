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

namespace XLite\Model\Payment\Base;

/**
 * Abstract credit card, web-based processor 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class WebBased extends \XLite\Model\Payment\Base\CreditCard
{
    /**
     * Form method (only for web-based processor) 
     */
    const FORM_METHOD_POST = 'post';
    const FORM_METHOD_GET  = 'get';


    /**
     * Return response type 
     */
    const RETURN_TYPE_HTTP_REDIRECT = 'http';
    const RETURN_TYPE_HTML_REDIRECT = 'html';
    const RETURN_TYPE_CUSTOM        = 'custom';


    /**
     * Do initial payment 
     * 
     * @return string Status code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doInitialPayment()
    {
        $method = $this->getFormMethod();
        $url = $this->getFormURL();
        $body = '';

        $inputs = array();
        foreach ($this->getFormFields() as $name => $value) {
            $inputs[] = '<input type="hidden" name="' . htmlspecialchars($name)
                . '" value="' . htmlspecialchars($value) . '" />';
        }

        if ($inputs) {
            $body = '      ' . implode("\n" . '      ', $inputs);
        }

        $page = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body onload="javascript: document.getElementById('form').submit();">
  <form method="$method" id="form" name="payment_form" action="$url">
    <fieldset style="display: none;">
$body
    </fieldset>
    <noscript>
      If you are not redirected within 3 seconds, please <input type="submit" value="press here" />.
    </noscript>
  </form>
</body>
</html>
HTML;

        print ($page);

        return self::PROLONGATION;
    }

    /**
     * Get form method 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFormMethod()
    {
        return self::FORM_METHOD_POST;
    }

    /**
     * Get redirect form URL 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getFormURL();

    /**
     * Get redirect form fields list
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getFormFields();

    /**
     * Get input template
     *
     * @return string or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getInputTemplate()
    {
        return null;
    }

    /**
     * Get return request owner transaction or null
     * 
     * @return \XLite\Model\Payment\Transaction or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getReturnOwnerTransaction()
    {
        return null;
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
        $this->transaction = $transaction;
    }

    /**
     * Get transactionId-based return URL 
     *
     * @param string $fieldName TransactionId field name
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getReturnURL($fieldName)
    {
        return \XLite::getInstance()->getShopUrl(
            \XLite\Core\Converter::buildUrl('payment_return', '', array('txn_id_name' => $fieldName)),
            \XLite\Core\Request::getInstance()->isHTTPS()
        );
    }

    /**
     * Check total (transaction total and total from gateway response)
     *
     * @param float $total Total from gateway response
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkTotal($total)
    {
        $result = true;

        if ($total && round($this->transaction->getValue(), 2) != $total) {
            $msg = 'Total amount doesn\'t match. Transaction total: ' . $this->transaction->getValue()
                . '; payment gateway amount: ' . $total;
            $this->transaction->getOrder()->setDetail('error', 'Hacking attempt!', 'Error');
            $this->transaction->getOrder()->setDetail(
                'errorDescription',
                $msg,
                'Hacking attempt details'
            );
            $this->transaction->setNote($msg);
            $result = false;
        }

        return $result;
    }

    /**
     * Check currency (order currency and transaction response currency)
     * 
     * @param string $currency Transaction response currency code
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkCurrency($currency)
    {
        $result = true;

        if ($currency && $this->transaction->getOrder()->getCurrency()->getCode() != $currency) {
            $msg = 'Currency code doesn\'t match. Order currency: ' . $this->transaction->getOrder()->getCurrency()->getCode()
                . '; payment gateway currency: ' . $currency;
            $this->transaction->getOrder()->setDetail('error', 'Hacking attempt!', 'Error');
            $this->transaction->getOrder()->setDetail(
                'errorDescription',
                $msg,
                'Hacking attempt details'
            );
            $this->transaction->setNote($msg);

            $result = false;
        }

        return $result;
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
        return self::RETURN_TYPE_HTTP_REDIRECT;
    }

    /**
     * Do custom redirect after customer's return
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doCustomReturnRedirect()
    {
    }
}
