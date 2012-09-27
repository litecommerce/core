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

namespace XLite\Model;

/**
 * ZoneElement model
 *
 *
 * @Entity
 * @Table (name="zone_elements",
 *      indexes={
 *          @Index (name="type_value", columns={"element_type","element_value"}),
 *          @Index (name="id_type", columns={"zone_id","element_type"})
 *      }
 * )
 */
class ZoneElement extends \XLite\Model\AEntity
{
    /*
     * Zone element types
     */
    const ZONE_ELEMENT_COUNTRY = 'C';
    const ZONE_ELEMENT_STATE   = 'S';
    const ZONE_ELEMENT_TOWN    = 'T';
    const ZONE_ELEMENT_ZIPCODE = 'Z';
    const ZONE_ELEMENT_ADDRESS = 'A';

    /**
     * Unique zone element Id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer", length=11, nullable=false)
     */
    protected $element_id;

    /**
     * Zone element value, e.g. 'US', 'US_NY', 'New Y%' etc
     *
     * @var string
     *
     * @Column (type="string", length=255)
     */
    protected $element_value;

    /**
     * Element type
     *
     * @var string
     *
     * @Column (type="fixedstring", length=1)
     */
    protected $element_type;

    /**
     * Zone (relation)
     *
     * @var \XLite\Model\Zone
     *
     * @ManyToOne (targetEntity="XLite\Model\Zone", inversedBy="zone_elements")
     * @JoinColumn (name="zone_id", referencedColumnName="zone_id")
     */
    protected $zone;

    /**
     * getElementTypesData
     *
     * @return void
     */
    static public function getElementTypesData()
    {
        return array(
            self::ZONE_ELEMENT_COUNTRY => array(
                'field'      => 'country',   // Address field name
                'weight'     => 0x01,        // Element weight
                'funcSuffix' => 'Countries', // Suffix for functions name: getZone<Suffix>, checkZone<Suffix>
                'required'   => true,        // Required property: if true then entire zone declined if this element does bot match
            ),
            self::ZONE_ELEMENT_STATE   => array(
                'field'      => 'state',
                'weight'     => 0x02,
                'funcSuffix' => 'States',
                'required'   => true,
            ),
            self::ZONE_ELEMENT_ZIPCODE => array(
                'field'      => 'zipcode',
                'weight'     => 0x08,
                'funcSuffix' => 'ZipCodes',
                'required'   => false,
            ),
            self::ZONE_ELEMENT_TOWN    => array(
                'field'      => 'city',
                'weight'     => 0x10,
                'funcSuffix' => 'Cities',
                'required'   => false,
            ),
            self::ZONE_ELEMENT_ADDRESS => array(
                'field'      => 'address',
                'weight'     => 0x20,
                'funcSuffix' =>'Addresses',
                'required'   => false,
            )
        );
    }
}
