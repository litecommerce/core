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
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\SalesTax\Model;

/**
 * Tax
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity
 * @Table  (name="sales_taxes")
 */
class Tax extends \XLite\Model\Base\I18n
{
    /**
     * Product unique ID
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="uinteger")
     */
    protected $id;

    /**
     * Eenabled
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Tax rates (relation)
     *
     * @var   \Doctrine\Common\Collections\ArrayCollection
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\SalesTax\Model\Tax\Rate", mappedBy="tax", cascade={"all"})
     * @OrderBy ({"position" = "ASC"})
     */
    protected $rates;

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $data = array())
    {
        $this->rates = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }

    /**
     * Get filtered rates by zones and membership
     *
     * @param array                                        $zones          Zone id list
     * @param \XLite\Model\Membership                      $membership     Membership
     * @param \Doctrine\Common\Collections\ArrayCollection $productClasses Product classes OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFilteredRates(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\ArrayCollection $productClasses = null
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
     * Get filtered rate by zones and membership
     *
     * @param array                                        $zones          Zone id list
     * @param \XLite\Model\Membership                      $membership     Membership OPTIONAL
     * @param \Doctrine\Common\Collections\ArrayCollection $productClasses Product classes OPTIONAL
     *
     * @return \XLite\Module\CDev\SalesTax\Model\Tax\Rate
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFilteredRate(
        array $zones,
        \XLite\Model\Membership $membership = null,
        \Doctrine\Common\Collections\ArrayCollection $productClasses = null
    ) {
        $rates = $this->getFilteredRates($zones, $membership, $productClasses);

        return array_shift($rates);
    }
}
