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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Model\Repo;

/**
 * Cart repository
 *
 */
class Cart extends \XLite\Model\Repo\ARepo
{
    /**
     * Mark cart model as order
     *
     * @param integer $orderId Order id
     *
     * @return boolean
     */
    public function markAsOrder($orderId)
    {
        $stmt = $this->defineMarkAsOrderQuery($orderId);

        return $stmt && $stmt->execute() && 0 < $stmt->rowCount();
    }

    /**
     * Define query for markAsOrder() method
     *
     * @param integer $orderId Order id
     *
     * @return \Doctrine\DBAL\Statement|void
     */
    protected function defineMarkAsOrderQuery($orderId)
    {
        $stmt = $this->_em->getConnection()->prepare(
            'UPDATE ' . $this->_class->getTableName() . ' '
            . 'SET is_order = :flag '
            . 'WHERE order_id = :id'
        );

        if ($stmt) {
            $stmt->bindValue(':flag', 1);
            $stmt->bindValue(':id', $orderId);

        } else {
            $stmt = null;
        }

        return $stmt;
    }
}
