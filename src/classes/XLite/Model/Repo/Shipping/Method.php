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

namespace XLite\Model\Repo\Shipping;

/**
 * Shipping method model
 *
 */
class Method extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SECONDARY;

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('processor', 'code'),
    );


    /**
     * Find all methods as options list
     *
     * @return array
     */
    public function findAsOptions()
    {
        return $this->defineFindAsOptionsQuery()->getResult();
    }

    /**
     * Returns shipping methods by specified processor Id
     *
     * @param string $processorId Processor Id
     *
     * @return array
     */
    public function findMethodsByProcessor($processorId)
    {
        return $this->defineFindMethodsByProcessor($processorId)->getResult();
    }

    /**
     * Returns shipping methods by ids
     *
     * @param array $ids Array of method_id values
     *
     * @return array
     */
    public function findMethodsByIds($ids)
    {
        return $this->defineFindMethodsByIds($ids)->getResult();
    }


    /**
     * Adds additional condition to the query for checking if method is enabled
     *
     * @param \Doctrine\ORM\QueryBuilder $qb    Query builder object
     * @param string                     $alias Entity alias OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function addEnabledCondition(\Doctrine\ORM\QueryBuilder $qb, $alias = 'm')
    {
        if (!\XLite::getInstance()->isAdminZone()) {
            $qb->andWhere($alias . '.enabled = 1');
        }

        return $qb;
    }

    /**
     * Define query builder object for findMethodsByProcessor()
     *
     * @param string $processorId Processor Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindMethodsByProcessor($processorId)
    {
        $qb = $this->createQueryBuilder('m')
            ->andWhere('m.processor =:processorId')
            ->setParameter('processorId', $processorId);

        return $this->addEnabledCondition($qb);
    }

    /**
     * Define query builder for findAsOptions() method
     * 
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindAsOptionsQuery()
    {
        return $this->createQueryBuilder('m')
            ->addOrderBy('m.carrier', 'asc')
            ->addOrderBy('m.position', 'asc');
    }

    /**
     * Define query builder object for findMethodsByIds()
     *
     * @param array $ids Array of method_id values
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindMethodsByIds($ids)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere($qb->expr()->in('m.method_id', $ids));
    }
}
