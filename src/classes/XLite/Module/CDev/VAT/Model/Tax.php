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

namespace XLite\Module\CDev\VAT\Model;

/**
 * Tax
 *
 *
 * @Entity
 * @Table  (name="vat_taxes")
 */
class Tax extends \XLite\Model\Base\I18n
{
    /**
     * Product unique ID
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Eenabled
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Tax rates (relation)
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\VAT\Model\Tax\Rate", mappedBy="tax", cascade={"all"})
     * @OrderBy ({"position" = "ASC"})
     */
    protected $rates;

    /**
     * VAT base membership
     *
     * @var \XLite\Model\Membership
     *
     * @ManyToOne  (targetEntity="XLite\Model\Membership", cascade={"detach", "merge", "persist"})
     * @JoinColumn (name="vat_membership_id", referencedColumnName="membership_id")
     */
    protected $vatMembership;

    /**
     * VAT base Zone
     *
     * @var \XLite\Model\Zone
     *
     * @ManyToOne  (targetEntity="XLite\Model\Zone", cascade={"detach", "merge", "persist"})
     * @JoinColumn (name="vat_zone_id", referencedColumnName="zone_id")
     */
    protected $vatZone;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get filtered rates by zones, membership and product classes
     *
     * @param array                                   $zones          Zone id list
     * @param \XLite\Model\Membership                 $membership     Membership OPTIONAL
     * @param \Doctrine\Common\Collections\Collection $productClasses Product classes OPTIONAL
     *
     * @return array
     */
    public function getFilteredRates(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\Collection $productClasses = null
    ) {
        $rates = array();

        foreach ($this->getRates() as $rate) {
            if ($rate->isApplied($zones, $membership, $productClasses) && !isset($rates[$rate->getPosition()])) {
                $rates[$rate->getPosition()] = $rate;
            }
        }
        ksort($rates);

        return $rates;
    }

    /**
     * Get filtered rate by zones, membership and product classes
     *
     * @param array                                   $zones          Zone id list
     * @param \XLite\Model\Membership                 $membership     Membership OPTIONAL
     * @param \Doctrine\Common\Collections\Collection $productClasses Product classes OPTIONAL
     *
     * @return \XLite\Module\CDev\VAT\Model\Tax\Rate
     */
    public function getFilteredRate(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\Collection $productClasses = null
    ) {
        $rates = $this->getFilteredRates($zones, $membership, $productClasses);

        return array_shift($rates);
    }

    /**
     * Set VAT base membership 
     * 
     * @param \XLite\Model\Membership $membership Membership OPTIONAL
     *  
     * @return void
     */
    public function setVATMembership(\XLite\Model\Membership $membership = null)
    {
        $this->vatMembership = $membership;
    }

    /**
     * Set VAT base zone 
     * 
     * @param \XLite\Model\Zone $zone Zone OPTIONAL
     *  
     * @return void
     */
    public function setVATZone(\XLite\Model\Zone $zone = null)
    {
        $this->vatZone = $zone;
    }
}
