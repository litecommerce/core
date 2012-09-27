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

namespace XLite\Module\CDev\ProductOptions\Model;

/**
 * Product option group item
 *
 *
 * @Entity (repositoryClass="\XLite\Module\CDev\ProductOptions\Model\Repo\Option")
 * @Table  (name="options",
 *      indexes={
 *          @Index (name="grp", columns={"group_id","enabled","orderby"})
 *      }
 * )
 */
class Option extends \XLite\Model\Base\I18n
{
    /**
     * Option unique id
     *
     * @var integer
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $option_id;

    /**
     * Sort position
     *
     * @var integer
     *
     * @Column (type="integer")
     */
    protected $orderby = 0;

    /**
     * Enabled
     *
     * @var boolean
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Option group (relation)
     *
     * @var \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     *
     * @ManyToOne  (targetEntity="XLite\Module\CDev\ProductOptions\Model\OptionGroup", inversedBy="options")
     * @JoinColumn (name="group_id", referencedColumnName="group_id")
     */
    protected $group;

    /**
     * Exceptions (relation)
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\ProductOptions\Model\OptionException", mappedBy="option", cascade={"all"})
     */
    protected $exceptions;

    /**
     * Surcharges (relation)
     *
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @OneToMany (targetEntity="XLite\Module\CDev\ProductOptions\Model\OptionSurcharge", mappedBy="option", cascade={"all"})
     */
    protected $surcharges;

    /**
     * Get surcharge by type
     *
     * @param string $type Type
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge|void
     */
    public function getSurcharge($type)
    {
        $result = null;

        foreach ($this->getSurcharges() as $surcharge) {
            if ($surcharge->getType() == $type) {
                $result = $surcharge;
                break;
            }
        }

        return $result;
    }

    /**
     * Check - has option action surcharge with specified type
     *
     * @param string $type Type
     *
     * @return boolean
     */
    public function hasActiveSurcharge($type)
    {
        $surcharge = $this->getSurcharge($type);

        return $surcharge && !$surcharge->isEmpty();
    }

    /**
     * Check - is option product attributes modifier or not
     *
     * @return boolean
     */
    public function isModifier()
    {
        return 0 < count($this->getNotEmptyModifiers());
    }

    /**
     * Get not empty modifiers
     *
     * @return array
     */
    public function getNotEmptyModifiers()
    {
        $types = \XLite\Core\Database::getRepo('\XLite\Module\CDev\ProductOptions\Model\OptionSurcharge')
            ->getSurchargeTypes();

        $result = array();

        foreach ($this->getSurcharges() as $surcharge) {
            if (in_array($surcharge->getType(), $types) && !$surcharge->isEmpty()) {
                $result[] = $surcharge;
            }
        }

        return $result;
    }

    /**
     * Constructor
     *
     * @param array $data Entity properties OPTIONAL
     *
     * @return void
     */
    public function __construct(array $data = array())
    {
        $this->exceptions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->surcharges = new \Doctrine\Common\Collections\ArrayCollection();

        parent::__construct($data);
    }
}
