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
class Markup extends \XLite\Model\Repo\ARepo
{
    /**
     * Repository type
     *
     * @var string
     */
    protected $type = self::TYPE_SECONDARY;


    /**
     * Returns shipping markups for order modifier by specified processor
     *
     * @param string                               $processor Processor class name
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier  Shipping order modifier
     *
     * @return array
     */
    public function findMarkupsByProcessor($processor, \XLite\Logic\Order\Modifier\Shipping $modifier)
    {
        $result = array();

        $address = \XLite\Model\Shipping::getInstance()->getDestinationAddress($modifier);

        $customerZones = array();

        if (isset($address)) {
            // Get customer zone sorted out by weight
            $customerZones = \XLite\Core\Database::getRepo('XLite\Model\Zone')
                ->findApplicableZones($address);
        }

        // Iterate through zones and generate markups list
        foreach ($customerZones as $zone) {

            $markups = $this->defineFindMarkupsByProcessorQuery($processor, $modifier, $zone->getZoneId())->getResult();

            foreach ($markups as $markupData) {

                $markup = $markupData[0];

                if ($markup->getShippingMethod() && !isset($result[$markup->getShippingMethod()->getMethodId()])) {
                    $markup->setMarkupValue($markupData['markup_value']);
                    $result[$markup->getShippingMethod()->getMethodId()] = $markup;
                }
            }
        }

        return $result;
    }

    /**
     * findMarkupsByZoneAndMethod
     *
     * @param integer $zoneId   Zone Id OPTIONAL
     * @param integer $methodId Method Id OPTIONAL
     *
     * @return array
     */
    public function findMarkupsByZoneAndMethod($zoneId = null, $methodId = null)
    {
        return $this->defineFindMarkupsByZoneAndMethodQuery($zoneId, $methodId)->getResult();
    }

    /**
     * Get markups by specified set of its id
     *
     * @param array $ids Array of markup Id
     *
     * @return array
     */
    public function findMarkupsByIds($ids)
    {
        return $this->defineFindMarkupsByIdsQuery($ids)->getResult();
    }


    /**
     * Adds markup condition to the query builder object
     *
     * @param \Doctrine\ORM\QueryBuilder           $qb       Query builder object
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier Shipping order modifier
     * @param integer                              $zoneId   Zone Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function addMarkupCondition(\Doctrine\ORM\QueryBuilder $qb, \XLite\Logic\Order\Modifier\Shipping $modifier, $zoneId)
    {
        $prepareSum = array(
            'm.markup_flat',
            '(m.markup_percent * :value / 100)',
            '(m.markup_per_item * :items)',
            '(m.markup_per_weight * :weight)'
        );

        return $qb->addSelect(implode(' + ', $prepareSum) . ' as markup_value')
            ->innerJoin('m.zone', 'zone')
            ->andWhere('m.min_weight <= :weight')
            ->andWhere('zone.zone_id = :zoneId')
            ->andWhere('m.max_weight >= :weight')
            ->andWhere('m.min_total <= :total')
            ->andWhere('m.max_total >= :total')
            ->andWhere('m.min_items <= :items')
            ->andWhere('m.max_items >= :items')
            ->setParameters(
                array_merge(
                    $qb->getParameters(),
                    array(
                        'zoneId' => $zoneId,
                        'weight' => $modifier->getWeight(),
                        'total'  => $modifier->getSubtotal(),
                        'items'  => $modifier->countItems(),
                        'value'  => $modifier->getSubtotal(),
                    )
                )
            );
    }

    /**
     * Define query builder object for findMarkupsByProcessor()
     *
     * @param string                               $processor Processor class name
     * @param \XLite\Logic\Order\Modifier\Shipping $modifier  Shipping order modifier
     * @param integer                              $zoneId    Zone Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindMarkupsByProcessorQuery($processor, \XLite\Logic\Order\Modifier\Shipping $modifier, $zoneId)
    {
        $qb = $this->createQueryBuilder('m')
            ->addSelect('sm')
            ->innerJoin('m.shipping_method', 'sm')
            ->andWhere('sm.processor = :processor')
            ->andWhere('sm.enabled = 1')
            ->setParameters(
                array(
                    'processor' => $processor
                )
            );

        return $this->addMarkupCondition($qb, $modifier, $zoneId);
    }

    /**
     * defineFindMarkupsByZoneAndMethodQuery
     *
     * @param integer $zoneId   Zone Id
     * @param integer $methodId Method Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindMarkupsByZoneAndMethodQuery($zoneId, $methodId)
    {
        $qb = $this->createQueryBuilder('m')
            ->addSelect('sm')
            ->innerJoin('m.shipping_method', 'sm');

        if (isset($zoneId)) {
            $qb->innerJoin('m.zone', 'zone')
                ->andWhere('zone.zone_id = :zoneId')
                ->setParameter('zoneId', $zoneId);
        }

        if (isset($methodId)) {
            $qb->innerJoin('m.shipping_method', 'shipping_method')
                ->andWhere('shipping_method.method_id = :methodId')
                ->setParameter('methodId', $methodId);
        }

        return $qb;
    }

    /**
     * defineFindMarkupsByIdsQuery
     *
     * @param array $ids Array of markup id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineFindMarkupsByIdsQuery($ids)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere($qb->expr()->in('m.markup_id', $ids));
    }
}
