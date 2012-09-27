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

namespace XLite\Module\CDev\ProductOptions\Model\Repo;

/**
 * Orer item option repository
 *
 */
class OrderItemOption extends \XLite\Model\Repo\ARepo
{
    /**
     * Find selected options by order item id and order id
     *
     * @param string  $itemId  Order item id
     * @param integer $orderId Order id
     *
     * @return array
     */
    public function findByItemIdAndOrderId($itemId, $orderId)
    {
        return $this->defineByItemIdAndOrderIdQuery($itemId, $orderId)->getResult();
    }


    /**
     * Define query for findByItemIdAndOrderId() method
     *
     * @param string  $itemId  Order item id
     * @param integer $orderId Order id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineByItemIdAndOrderIdQuery($itemId, $orderId)
    {
        return $this->createQueryBuilder()
            ->andWhere('o.item_id = :itemId AND o.order_id = :orderId')
            ->setParameter('itemId', $itemId)
            ->setParameter('orderId', $orderId);
    }

    /**
     * Get detailed foreign keys
     *
     * @return array
     */
    protected function getDetailedForeignKeys()
    {
        $list = parent::getDetailedForeignKeys();

        $list[] = array(
            'fields'          => array('group_id'),
            'referenceRepo'   => 'XLite\Module\CDev\ProductOptions\Model\OptionGroup',
            'delete'          => 'SET NULL',
        );
        $list[] = array(
            'fields'          => array('option_id'),
            'referenceRepo'   => 'XLite\Module\CDev\ProductOptions\Model\Option',
            'delete'          => 'SET NULL',
        );

        return $list;
    }
}
