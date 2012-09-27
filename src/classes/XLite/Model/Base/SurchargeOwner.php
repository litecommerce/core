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

namespace XLite\Model\Base;

/**
 * Surcharge owner
 *
 *
 * @MappedSuperclass
 */
abstract class SurchargeOwner extends \XLite\Model\AEntity
{
    /**
     * Total
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $total = 0.0000;

    /**
     * Subtotal
     *
     * @var float
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $subtotal = 0.0000;

    // {{{ Saved surcharges

    /**
     * Get exclude surcharges (non-included)
     * 
     * @return array
     */
    public function getExcludeSurcharges()
    {
        $list = array();

        foreach ($this->getSurcharges() as $surcharge) {
            if (!$surcharge->getInclude()) {
                $list[] = $surcharge;
            }
        }

        return $list;
    }

    /**
     * Get included surcharges
     *
     * @return array
     */
    public function getIncludeSurcharges()
    {
        $list = array();

        foreach ($this->getSurcharges() as $surcharge) {
            if ($surcharge->getInclude()) {
                $list[] = $surcharge;
            }
        }

        return $list;
    }

    /**
     * Get exclude surcharges (non-included) by type
     * 
     * @param string $type Type
     *  
     * @return array
     */
    public function getExcludeSurchargesByType($type)
    {
        $list = array();

        foreach ($this->getSurcharges() as $surcharge) {
            if (!$surcharge->getInclude() && $surcharge->getType() == $type) {
                $list[] = $surcharge;
            }
        }

        return $list;
    }

    /**
     * Get surcharge totals 
     * 
     * @return array
     */
    public function getSurchargeTotals()
    {
        $surcharges = array();

        foreach ($this->getExcludeSurcharges() as $surcharge) {

            $code = $surcharge->getCode();

            if (!isset($surcharges[$code])) {
                $surcharges[$code] = array(
                    'name'      => $surcharge->getTypeName(),
                    'cost'      => 0,
                    'available' => $surcharge->getAvailable(),
                    'count'     => 0,
                    'lastName'  => null,
                    'code'      => $code,
                );
            }

            $surcharges[$code]['cost'] += $surcharge->getValue();
            $surcharges[$code]['count']++;
            $surcharges[$code]['lastName'] = $surcharge->getName();
        }

        return $surcharges;
    }

    /**
     * Get surcharge sum
     *
     * @return float
     */
    public function getSurchargeSum()
    {
        $total = 0;

        foreach ($this->getExcludeSurcharges() as $s) {
            $total += $s->getValue();
        }

        return $total;
    }

    /**
     * Get surcharge sum by type
     *
     * @param string $type Surcharge type
     *
     * @return float
     */
    public function getSurchargeSumByType($type)
    {
        $total = 0;

        foreach ($this->getExcludeSurchargesByType($type) as $s) {
            $total += $s->getValue();
        }

        return $total;
    }

    /**
     * Get surcharge total by type
     *
     * @param string $type Surcharge type
     *
     * @return float
     */
    public function getSurchargeTotalByType($type)
    {
        return $this->getSubtotal() + $this->getSurchargeSumByType($type);
    }

    // }}}
}
