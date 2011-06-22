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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Payment\Base;

/**
 * Abstract credit card, web-based (iframe) processor
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Iframe extends \XLite\Model\Payment\Base\CreditCard
{
    /**
     * Get iframe data
     *
     * @return string|array URL or POST data
     * @see    ____func_see____
     * @since  1.0.0
     */
    abstract protected function getIframeData();

    /**
     * Get input template
     *
     * @return string|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getInputTemplate()
    {
        return null;
    }

    /**
     * Get return request owner transaction or null
     *
     * @return \XLite\Model\Payment\Transaction|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getReturnOwnerTransaction()
    {
        return null;
    }

    /**
     * Get iframe form URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIframeFormURL()
    {
    }

    /**
     * Get iframe size 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIframeSize()
    {
        return array(600, 400);
    }

    /**
     * Do initial payment
     *
     * @return string Status code
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doInitialPayment()
    {
        $data = $this->getIframeData();

        $html = is_array($data) ? $this->assembleFormIframe($data) : $this->assembleURLIframe($data);

        $page = <<<HTML
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
    $html
</body>
</html>
HTML;

        print ($page);

        return self::PROLONGATION;
    }

    /**
     * Assemble form-based iframe 
     * 
     * @param array $data Form elements
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleFormIframe(array $data)
    {
        list($width, $height) = $this->getIframeSize();

        $content = new \XLite\Model\IframeContent;
        $content->setData($data);
        $content->setUrl($this->getIframeFormURL());

        \XLite\Core\Database::getEM()->persist($content);
        \XLite\Core\Database::getEM()->flush();

        $url = \XLite\Core\Converter::buildURL('iframe_content', '', array('id' => $content->getId()));

        return '<iframe id="pay_iframe" width="' . $width . '" height="' . $height . '" src="' . $url . '">'
            . '</iframe>';
    }

    /**
     * Assemble URL-based iframe 
     * 
     * @param string $data Iframe URL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleURLIframe($data)
    {
        list($width, $height) = $this->getIframeSize();

        return '<iframe id="pay_iframe" width="' . $width . '" height="' . $height . '" src="' . $data . '">'
            . '</iframe>';
    }


    /**
     * Get form method
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormMethod()
    {
        return self::FORM_METHOD_POST;
    }

    /**
     * Get transactionId-based return URL
     *
     * @param string $fieldName TransactionId field name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getReturnURL($fieldName)
    {
        return \XLite::getInstance()->getShopURL(
            \XLite\Core\Converter::buildURL('payment_return', '', array('txn_id_name' => $fieldName)),
            true
        );
    }

    /**
     * Check total (transaction total and total from gateway response)
     *
     * @param float $total Total from gateway response
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkTotal($total)
    {
        $result = true;

        if ($total && $this->transaction->getValue() != $total) {
            $msg = 'Total amount doesn\'t match. Transaction total: ' . $this->transaction->getValue()
                . '; payment gateway amount: ' . $total;
            $this->setDetail(
                'total_checking_error',
                $msg,
                'Hacking attempt'
            );

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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkCurrency($currency)
    {
        $result = true;

        if ($currency && $this->transaction->getOrder()->getCurrency()->getCode() != $currency) {
            $msg = 'Currency code doesn\'t match. Order currency: '
                . $this->transaction->getOrder()->getCurrency()->getCode()
                . '; payment gateway currency: ' . $currency;
            $this->setDetail(
                'currency_checking_error',
                $msg,
                'Hacking attempt details'
            );

            $result = false;
        }

        return $result;
    }

    /**
     * Assemble form body (field set)
     *
     * @return string HTML
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleFormBody()
    {
        $inputs = array();
        foreach ($this->getFormFields() as $name => $value) {
            $inputs[] = '<input type="hidden" name="' . htmlspecialchars($name)
                . '" value="' . htmlspecialchars($value) . '" />';
        }

        if ($inputs) {
            $body = '      ' . implode("\n" . '      ', $inputs);
        }

        return $body;
    }

}
