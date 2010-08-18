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

namespace XLite\Model\Repo;

/**
 * Cart repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Cart extends \XLite\Model\Repo\ARepo
{
    /**
     * Mark cart model as order 
     * 
     * @param integer $orderId Order id
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function markAsOrder($orderId)
    {
        return 0 < $this->defineMarkAsOrderQuery($orderId)->execute();
    }

    /**
     * Define query for markAsOrder() method
     * 
     * @param integer $orderId Order id
     *  
     * @return \Doctrine\ORM\NativeQuery
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineMarkAsOrderQuery($orderId)
    {
        $query = new \Doctrine\ORM\NativeQuery($this->_em);
        $query->setSql(
            'UPDATE ' . $this->_class->getTableName() . ' '
            . 'SET is_order = :flag '
            . 'WHERE order_id = :id'
        );
        $query->setParameter('flag', 1);
        $query->setParameter('id', $orderId);

        return $query;
    }
}
