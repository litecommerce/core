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

namespace XLite\Controller\Customer;

/**
 * Web-based payment method return
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PaymentReturn extends \XLite\Controller\Customer\ACustomer
{
    /**
     * This controller is always accessible
     * TODO - check if it's really needed; remove if not
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function checkStorefrontAccessability()
    {
        return true;
    }

    /**
     * Handles the request
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        \XLite\Core\Request::getInstance()->action = 'return';

        parent::handleRequest();
    }

    /**
     * Process return
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionReturn()
    {
        $txn = null;
        $txnIdName = 'txnId';

        if (isset(\XLite\Core\Request::getInstance()->txn_id_name)) {
            /**
             * some of gateways can't accept return url on run-time and
             * use the one set in merchant account, so we can't pass
             * 'order_id' in run-time, instead pass the order id parameter name
             */
            $txnIdName = \XLite\Core\Request::getInstance()->txn_id_name;
        }

        if (isset(\XLite\Core\Request::getInstance()->$txnIdName)) {
            $txn = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')->find(\XLite\Core\Request::getInstance()->$txnIdName);
        }

        if (!$txn) {

            $methods = \XLite\Core\Database::getRepo('XLite\Model\Payment\Method')->findAllActive();
            foreach ($methods as $method) {
                if (method_exists($method->getProcessor(), 'getCallbackOwnerTransaction')) {
                    $txn = $method->getProcessor()->getReturnOwnerTransaction();
                    if ($txn) {
                        break;
                    }
                }
            }

        }

        if ($txn) {
            $txn->getPaymentMethod()->getProcessor()->processReturn($txn);

            \XLite\Core\Database::getEM()->persist($txn);
            \XLite\Core\Database::getEM()->flush();

            $url = \XLite::getShopUrl(
                $this->buildUrl('checkout', 'return', array('order_id' => $txn->getorder()->getOrderId())),
                $this->config->Security->customer_security
            );

            switch ($txn->getPaymentMethod()->getProcessor()->getReturnType()) {
                case \XLite\Model\Payment\Base\WebBase:RETURN_TYPE_HTML_REDIRECT:
                    $this->doHTMLRedirect($url);
                    break;

                case \XLite\Model\Payment\Base\WebBase:RETURN_TYPE_CUSTOM:
                    $txn->getPaymentMethod()->getProcessor()->doCustomReturnRedirect();

                default:
                    $this->setReturnUrl($url);
            }

        } else {
            // TODO - add error logging

            $this->setReturnUrl(
                $this->buildUrl('checkout')
            );
        }

    }

    /**
     * Do HTML-based redirect 
     * 
     * @param string $url  URL
     * @param int    $time Redirect delay
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doHTMLRedirect($url, $time = 1)
    {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Refresh" content="<?php echo $time; ?>;URL=<?php echo $url; ?>" />
</head>
<body>
If the page is not updated in <?php echo $time; ?> seconds, please follow this link: <a href="<?php echo $url; ?>">continue &gt;&gt;</a>
</body>
</html>
<?php

        exit(0);
    }
}
