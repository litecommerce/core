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

namespace XLite\Model\Repo\Shipping;

/**
 * Shipping method model
 * 
 * @package    XLite
 * @subpackage Model
 * @see        ____class_see____
 * @since      3.0.0
 */
class Markup extends \XLite\Model\Repo\ARepo
{
    /**
     * Returns shipping markups for order by specified processor
     * 
     * @param string             $processor Processor class name
     * @param \XLite\Model\Order $order     Order object
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarkupsByProcessor($processor, $order)
    {
        $result = array();

        if (is_object($order->getProfile())) {
            $address = $order->getProfile()->getShippingAddress();

        } else {
            $config = \XLite\Base::getInstance()->config->Shipping;

            if ($config->def_calc_shippings_taxes) {
                $address = array(
                    'address' => $config->anonymous_address,
                    'city'    => $config->anonymous_city,
                    'state'   => $config->anonymous_state,
                    'zipcode' => $config->anonymous_zipcode,
                    'country' => $config->anonymous_country
                );
            }
        }

        $customerZones = array();

        if (isset($address)) {
            // Get customer zone sorted out by weight
            $customerZones = \XLite\Core\Database::getRepo('\XLite\Model\Zone')
                ->findApplicableZones($address);
        }

        // Iterate through zones and generate markups list
        foreach ($customerZones as $zone) {

            $markups = $this->defineGetMarkupsByProcessorQuery($processor, $order, $zone->getZoneId())
                ->getQuery()
                ->getResult();

            foreach ($markups as $markupData) {

                $markup = $markupData[0];

                $methodId = $markup->getMethodId();

                if (!isset($result[$methodId])) {
                    $markup->setMarkupValue($markupData['markup_value']);
                    $result[$methodId] = $markup;
                }
            }
        }

        return $result;
    }

    /**
     * Define query builder object for getMarkupsByProcessorQuery()
     * 
     * @param string             $processor Processor class name
     * @param \XLite\Model\Order $order     Order or cart object
     * @param int                $zoneId    Zone Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetMarkupsByProcessorQuery($processor, $order, $zoneId)
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

        $qb = $this->addMarkupCondition($qb, $order, $zoneId);

        return $qb;
    }

    /**
     * Adds markup condition to the query builder object
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb     Query builder object
     * @param \XLite\Model\Order         $order  Order object
     * @param int                        $zoneId Zone Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addMarkupCondition($qb, $order, $zoneId)
    {
        return $qb->addSelect('(m.markup_flat + (m.markup_percent * :value / 100) + (m.markup_per_item * :items) + (m.markup_per_weight * :weight)) as markup_value')
            ->andWhere('m.min_weight <= :weight')
            ->andWhere('m.zone_id = :zoneId')
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
                        'weight' => $order->getWeight(),
                        'total'  => $order->getSubtotal(),
                        'items'  => $order->countShippedItems(),
                        'value'  => $order->getSubtotal()
                    )
                )
            );
    }

    /**
     * getMarkupsByZoneAndMethod 
     * 
     * @param int $zoneId   Zone Id
     * @param int $methodId Method Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarkupsByZoneAndMethod($zoneId = null, $methodId = null)
    {
        $data = $this->defineGetMarkupsByZoneAndMethod($zoneId, $methodId)
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * defineGetMarkupsByZoneAndMethod 
     * 
     * @param int $zoneId   Zone Id
     * @param int $methodId Method Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetMarkupsByZoneAndMethod($zoneId, $methodId)
    {
        $qb = $this->createQueryBuilder('m');

        $qb = $qb->addSelect('sm')
            ->innerJoin('m.shipping_method', 'sm')
            ->andWhere('sm.enabled = 1');

        if (isset($zoneId)) {
            $qb = $qb->andWhere('m.zone_id = :zoneId')
                ->setParameter('zoneId', $zoneId);
        }

        if (isset($methodId)) {
            $qb = $qb->andWhere('m.method_id = :methodId')
                ->setParameter('methodId', $methodId);
        }

        return $qb;
    }

    /**
     * Get markups by specified set of its id 
     * 
     * @param array $ids Array of markup Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMarkupsByIds($ids)
    {
        $data = $this->defineGetMarkupsByIds($ids)
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * defineGetMarkupsByIds 
     * 
     * @param array $ids Array of markup id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetMarkupsByIds($ids)
    {
        $qb = $this->createQueryBuilder('m');

        return $qb->andWhere($qb->expr()->in('m.markup_id', $ids));
    }

}
