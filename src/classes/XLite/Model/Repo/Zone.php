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
 * Zone repository
 *
 */
class Zone extends \XLite\Model\Repo\ARepo
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
        array('zone_name'),
    );

    // {{{ defineCacheCells

    /**
     * Define cache cells
     *
     * @return array
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list['all'] = array(
            self::RELATION_CACHE_CELL => array('\XLite\Model\Zone'),
        );

        $list['default'] = array(
            self::RELATION_CACHE_CELL => array('\XLite\Model\Zone'),
        );

        $list['zone'] = array(
            self::ATTRS_CACHE_CELL    => array('zone_id'),
            self::RELATION_CACHE_CELL => array('\XLite\Model\Zone'),
        );

        return $list;
    }

    // }}}

    // {{{ findAllZones

    /**
     * findAllZones
     *
     * @return array
     */
    public function findAllZones()
    {
        $data = $this->getFromCache('all');

        if (!isset($data)) {
            $data = $this->defineFindAllZones()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * defineGetZones
     *
     * @return void
     */
    protected function defineFindAllZones()
    {
        return $this->createQueryBuilder()
            ->addSelect('ze')
            ->leftJoin('z.zone_elements', 'ze')
            ->addOrderBy('z.is_default', 'DESC')
            ->addOrderBy('z.zone_name');
    }

    // }}}

    // {{{ findZone

    /**
     * findZone
     *
     * @param integer $zoneId Zone Id
     *
     * @return \XLite\Model\Zone
     */
    public function findZone($zoneId)
    {
        $data = $this->getFromCache('zone', array('zone_id' => $zoneId));

        if (!isset($data)) {
            $data = $this->defineFindZone($zoneId)->getSingleResult();

            if ($data) {
                $this->saveToCache($data, 'zone', array('zone_id' => $zoneId));
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
     */
    protected function defineFindZone($zoneId)
    {
        return $this->createQueryBuilder()
            ->addSelect('ze')
            ->leftJoin('z.zone_elements', 'ze')
            ->andWhere('z.zone_id = :zoneId')
            ->setParameter('zoneId', $zoneId);
    }

    // }}}

    // {{{ findApplicableZones

    /**
     * Get the zones list applicable to the specified address
     *
     * @param array $address Address data
     *
     * @return void
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
        $defaultZone = $this->getDefaultZone();

        if ($defaultZone) {
            $applicableZones[0] = $defaultZone;
        }

        // Sort zones list by weight in reverse order
        krsort($applicableZones);

        return $applicableZones;
    }

    /**
     * Return default zone
     *
     * @return \XLite\Model\Zone
     */
    protected function getDefaultZone()
    {
        $result = $this->getFromCache('default');

        if (!isset($result)) {
            $result = $this->findOneBy(array('is_default' => 1));
            $this->saveToCache($result, 'default');
        }

        return $result;
    }

    // }}}
}
