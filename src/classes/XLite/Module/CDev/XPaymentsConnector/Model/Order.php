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

namespace XLite\Module\CDev\XPaymentsConnector\Model;

/**
 * Order 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\Order
implements \XLite\Base\IDecorator
{
    /**
     * Constructs a new database object. The options argument list is a primary key value.
     * If it is specified, the object is created as isPersistent, otherwise - !isPersistent
     * 
     * @param integer $id Order id OPTIONAL
     *
     * @return void
     * @access public
     * @since  3.0
     */
    public function __construct($id = null)
    {
        $this->fields['xpc_txnid'] = '';

        parent::__construct($id);
    }

    /**
     * Get orders list by X-Payments transaction id 
     * 
     * @param string $txnId Transaction id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOrdersByXPCTxnId($txnId)
    {
        return $this->findAll('xpc_txnid = \'' . $txnId . '\'');
    }
}

