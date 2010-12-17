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
     * Repository type 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $type = self::TYPE_SECONDARY;

    /**
     * Alternative record identifiers
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $alternativeIdentifier = array(
        array('zone_name'),
    );

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
     * cleanCache 
     * 
     * @param integer $zoneId Zone Id OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cleanCache($zoneId = null)
    {
        $this->deleteCache('all');
        
        if (isset($zoneId)) {
            $this->deleteCache('zone.' . sprintf('%d', $zoneId));

        } else {
            $this->deleteCache('zone');
        }
    }

    /**
     * findAllZones 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllZones()
    {
        $data = $this->getFromCache('all');

        if (!isset($data)) {
            $data = $this->defineFindAllZones()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * defineGetZones 
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFindAllZones()
    {
        return $this->createQueryBuilder()
            ->addSelect('ze')
            ->leftJoin('z.zone_elements', 'ze')
            ->addOrderBy('z.is_default', 'DESC')
            ->addOrderBy('z.zone_name');
    }

    /**
     * getZone 
     * 
     * @param integer $zoneId Zone Id
     *  
     * @return \XLite\Model\Zone
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findZone($zoneId)
    {
        $data = $this->getFromCache('zone', array('zone_id' => $zoneId));

        if (!isset($data)) {

            try {
                $data = $this->defineFindZone($zoneId)->getQuery()->getSingleResult();
                $this->saveToCache($data, 'zone', array('zone_id' => $zoneId));

            } catch (\Doctrine\ORM\NoResultException $exception) {
                $data = null;
            }
        }

        return $data;
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
    protected function defineFindZone($zoneId)
    {
        return $this->createQueryBuilder()
            ->addSelect('ze')
            ->leftJoin('z.zone_elements', 'ze')
            ->andWhere('z.zone_id = :zoneId')
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
    public function findApplicableZones($address)
    {
        if (is_numeric($address['state'])) {
            $address['state'] = \XLite\Core\Database::getRepo('XLite\Model\State')->getCodeById($address['state']);
        }

        // Get all zones list
        $allZones = $this->findAllZones();

        $applicableZones = array();

        // Get the list of zones that are applicable for address
        foreach ($allZones as $zone) {

            $zoneWeight = $zone->getZoneWeight($address);

            if (0 < $zoneWeight) {
                $applicableZones[$zoneWeight] = $zone;
            }
        }

        // Add default zone with zero weight
        $applicableZones[0] = $this->findOneBy(array('is_default' => 1));

        // Sort zones list by weight in reverse order
        krsort($applicableZones);

        return $applicableZones;
    }

}
