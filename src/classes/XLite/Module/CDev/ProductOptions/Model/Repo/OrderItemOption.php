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

namespace XLite\Module\CDev\ProductOptions\Model\Repo;

/**
 * Orer item option repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByItemIdAndOrderId($itemId, $orderId)
    {
        return $this->defineByItemIdAndOrderIdQuery($itemId, $orderId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Define query for findByItemIdAndOrderId() method
     * 
     * @param string  $itemId  Order item id
     * @param integer $orderId Order id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByItemIdAndOrderIdQuery($itemId, $orderId)
    {
        return $this->createQueryBuilder()
            ->andWhere('o.item_id = :itemId AND o.order_id = :orderId')
            ->setParameter('itemId', $itemId)
            ->setParameter('orderId', $orderId);
    }

    /**
     * Process DB schema 
     * 
     * @param array  $schema Schema
     * @param string $type   Schema type
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processSchema(array $schema, $type)
    {
        $schema = parent::processSchema($schema, $type);

        if (\XLite\Core\Database::SCHEMA_UPDATE == $type || \XLite\Core\Database::SCHEMA_CREATE == $type) {
            $schema = preg_replace(
                '/(\w+order_item_options` ADD FOREIGN KEY \(`group_id`\) REFERENCES `\w+option_groups` \(`group_id`\)$)/Ss',
                '$1 ON DELETE SET NULL',
                $schema
            );
            $schema = preg_replace(
                '/(\w+order_item_options` ADD FOREIGN KEY \(`option_id`\) REFERENCES `\w+options` \(`option_id`\)$)/Ss',
                '$1 ON DELETE SET NULL',
                $schema
            );
        }

        return $schema;
    }
}

