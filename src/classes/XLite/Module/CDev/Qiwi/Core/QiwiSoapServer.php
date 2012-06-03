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
 * @since     1.0.23
 */

namespace XLite\Module\CDev\Qiwi\Core;

/**
 * Helper class for dealing with SOAP requests to Qiwi server
 * 
 * @see   ____class_see____
 * @since 1.0.23
 */
class QiwiSoapServer extends \XLite\Base
{

    /**
     * Path to Qiwi client's WSDL file
     */
    const CLIENT_WSDL_PATH = '/XLite/Module/CDev/Qiwi/schemas/IShopClientWS.wsdl';

    /**
     * SoapServer instance for catching callbacks from Qiwi
     * 
     * @var   \SoapServer
     * @see   ____var_see____
     * @since 1.0.23
     */
    protected $server;

    /**
     * Bill object received in updateBill call
     * 
     * @var   \stdClass
     * @see   ____var_see____
     * @since 1.0.23
     */
    protected $bill;

    /**
     * Initializes SOAP server instance
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function __construct()
    {
         $this->server = new \SoapServer(LC_DIR_CLASSES . static::CLIENT_WSDL_PATH);
         $this->server->setObject($this);
    }

    /**
     * updateBill 
     * 
     * @param \stdClass $bill Paid or cancelled bill
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function updateBill(\stdClass $bill)
    {
        if ($this->checkRequest($bill)) {
            $this->bill = $bill;
        }
    }

    /**
     * Handles current SOAP server call
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function handle()
    {
        $this->server->handle();
    }

    /**
     * Returns public transaction id (Qiwi txn) passed in last updateBill call
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function getPublicTransactionId()
    {
        return $this->bill ? $this->bill->txn : null;
    }

    /**
     * Returns Bill object passed in last updateBill call
     * 
     * @return \stdClass
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function getBill()
    {
        return $this->bill;
    }

    /**
     * Checks request authenticity
     * 
     * @param \stdClass $bill Bill object containing password to check
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.23
     */
    protected function checkRequest(\stdClass $bill)
    {
        $valid = false;

        $transaction = \XLite\Core\Database::getRepo('XLite\Model\Payment\Transaction')
            ->findOneBy(array('public_id' => $bill->txn));

        if ($transaction) {
            $password = $transaction->getPaymentMethod()->getSetting('password');

            $valid = $bill->password === strtoupper(md5($bill->txn . strtoupper(md5($password))));
        }

        return $valid;
    }
}
