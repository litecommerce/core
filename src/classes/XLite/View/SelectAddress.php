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

namespace XLite\View;

/**
 * Pick address from address book
 *
 *
 * @ListChild (list="center")
 */
class SelectAddress extends \XLite\View\Dialog
{
    /**
     * Columns number
     *
     * @var integer
     */
    protected $columnsNumber = 2;

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'select_address';

        return $result;
    }

    /**
     * Get a list of JS files required to display the widget properly
     * FIXME - decompose these files
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'select_address/controller.js';

        return $list;
    }

    /**
     * Check - specified address is selected or not
     *
     * @param \XLite\Model\Address $address Address
     *
     * @return boolean
     */
    public function isSelectedAddress(\XLite\Model\Address $address)
    {
        $atype = \XLite\Core\Request::getInstance()->atype;

        return ($address->getIsShipping() && \XLite\Model\Address::SHIPPING == $atype)
            || ($address->getIsBilling() && \XLite\Model\Address::BILLING == $atype);
    }

    /**
     * Get addresses list
     *
     * @return array
     */
    public function getAddresses()
    {
        return $this->getCart()->getOrigProfile()->getAddresses();
    }

    /**
     * Check - profile has addresses list or not
     *
     * @return boolean
     */
    public function hasAddresses()
    {
        return 0 < count($this->getAddresses());
    }

    /**
     * Get list item class name
     *
     * @param \XLite\Model\Address $address Address
     * @param integer              $i       Address position in addresses list
     *
     * @return string
     */
    public function getItemClassName(\XLite\Model\Address $address, $i)
    {
        $class = 'address-' . $address->getAddressId();

        if ($this->isSelectedAddress($address)) {
            $class .= ' selected';
        }

        if (0 == $i % $this->columnsNumber) {
            $class .= ' last';
        }

        return $class;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'select_address';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getCart()->getOrigProfile()
            && !$this->getCart()->getOrigProfile()->getOrder();
    }
}
