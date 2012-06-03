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
class QiwiSoapClient extends \XLite\Base
{

    /**
     * Path to Qiwi server's WSDL file
     */
    const SERVER_WSDL_PATH = '/XLite/Module/CDev/Qiwi/schemas/IShopServerWS.wsdl';

    /**
     * SoapClient instance for making requests to Qiwi web service
     * 
     * @var   \SoapClient
     * @see   ____var_see____
     * @since 1.0.23
     */
    protected $client;

    /**
     * Initializes SOAP client
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function __construct()
    {
         $this->client = new \SoapClient(LC_DIR_CLASSES . static::SERVER_WSDL_PATH);
    }

    /**
     * Returns bill's info
     * 
     * @param string  $login    Qiwi login
     * @param string  $password Qiwi password
     * @param integer $txn      Transaction identifier
     *  
     * @return \stdClass
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function checkBill($login, $password, $txn)
    {
        $checkBill = new \stdClass();
        $checkBill->login = $login;
        $checkBill->password = $password;
        $checkBill->txn = $txn;

        return $this->client->checkBill($checkBill);
    }

    /**
     * Create a bill
     * 
     * @param \stdClass $args Object containing input parameters for createBill call
     *  
     * @return \stdClass
     * @see    ____func_see____
     * @since  1.0.23
     */
    public function createBill(\stdClass $args)
    {
        return $this->client->createBill($args);
    }
}
