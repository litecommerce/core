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
 * Zone repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Zone extends \XLite\Model\Repo\ARepo
{
    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['all'] = array(
            self::RELATION_CACHE_CELL => array('\XLite\Model\Zone'),
        );

        $list['zone'] = array(
            self::ATTRS_CACHE_CELL    => array('zone_id'),
            self::RELATION_CACHE_CELL => array('\XLite\Model\Zone'),
        );

        return $list;
    }

    /**
     * getZones 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZones()
    {
        return $this->defineGetZones()->getQuery()->getResult();
    }

    /**
     * defineGetZones 
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetZones()
    {
        return $this->createQueryBuilder()
            ->orderBy('z.zone_name');
    }

    /**
     * getZone 
     * 
     * @param int $zoneId Zone Id
     *  
     * @return \XLite\Model\Zone
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZone($zoneId)
    {
        try {
            $zone = $this->defineGetZone($zoneId)->getQuery()->getSingleResult();
        
        } catch (\Doctrine\ORM\NoResultException $exception) {
            $zone = null;
        }

        return $zone;
    }

    /**
     * defineGetZone
     * 
     * @param mixed $zoneId ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineGetZone($zoneId)
    {
        return $this->createQueryBuilder()
            ->andWhere('z.zone_id = :zoneId')
            ->setMaxResults(1)
            ->setParameter('zoneId', $zoneId);
    }

    /**
     * Get the zones list applicable to the specified address
     * 
     * @param array $address Address data
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getApplicableZones($address)
    {
        // Get all zones list
        $allZones = $this->getZones();

        $applicableZones = array();

        // Get the list of zones that are applicable for address
        foreach ($allZones as $zone) {

            $zoneWeight = $zone->getZoneWeight($address);

            if (0 < $zoneWeight) {
                $applicableZones[$zoneWeight] = $zone;
            }
        }

        // Add default zone with zero weight
        $applicableZones[0] = new \XLite\Model\Zone();

        // Sort zones list by weight in reverse order
        arsort($applicableZones, SORT_NUMERIC);

        return $applicableZones;
    }

}
