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

namespace XLite\Module\CDev\Bestsellers\Model\Repo;

/**
 * The "OrderItem" model repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Defines bestsellers products collection 
     * 
     * @param integer $count Number of products to get OPTIONAL
     * @param integer $cat   Category identificator OPTIONAL
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findBestsellers($count = 0, $cat = 0)
    {
        return $this->getObjectOnlyResult($this->defineBestsellersQuery($count, $cat));
    }

    /**
     * Prepares query builder object to get bestsell products
     * 
     * @param integer $count Number of products to get
     * @param integer $cat   Category identificator
     * 
     * @return \Doctrine\ORM\QueryBuilder Query builder object
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineBestsellersQuery($count, $cat)
    {
        $qb = $this->createQueryBuilder()
            ->leftJoin('p.order_item', 'o')
            ->innerJoin('o.order', 'ord')
            ->leftJoin('p.categoryProducts', 'cp')
            ->leftJoin('cp.category', 'c')
            ->addSelect('sum(o.amount) as product_amount')
            ->andWhere('p.enabled = :enabled')
            ->andWhere('ord.status IN (:complete_status, :processed_status)')
            ->groupBy('o.product')
            ->orderBy('product_amount', 'DESC')
            ->setParameter('enabled', true)
            ->setParameter('complete_status', \XLite\Model\Order::STATUS_COMPLETED)
            ->setParameter('processed_status', \XLite\Model\Order::STATUS_PROCESSED);

        if (0 < $count) {
            $qb->setMaxResults($count);
        }

        if (0 < $cat) {
            \XLite\Core\Database::getRepo('XLite\Model\Category')->addSubTreeCondition($qb, $cat);
        }

        return $qb;
    }

    /**
     * Returns query result with the object collection only
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder object
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getObjectOnlyResult($qb)
    {
        $data = $qb->getQuery()->getResult();

        $result = array();

        foreach ($data as $row) {

            if (is_array($row)) {

                $object = $row[0];

                unset($row[0]);

            }

            $result[] = $object;

        }

        unset($data);

        return $result;
    }

}
