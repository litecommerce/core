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

namespace XLite\Module\CDev\SimpleTaxes\Controller\Admin;

/**
 * Taxes controller
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Taxes extends \XLite\Controller\Admin\AAdmin
{
    /**
     * FIXME- backward compatibility
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $params = array('target', 'page', 'backURL');

    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        $taxes = \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleTaxes\Model\Tax')->findAll();

        $pages = array();
        foreach ($taxes as $tax) {
            $pages[$tax->getId()] = $tax->getName();
        }

        return $pages;
    }

    /**
     * Get pages templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPageTemplates()
    {
        $list = array(
            'default' => 'modules/CDev/SimpleTaxes/edit.tpl',
        );

        foreach (array_keys($this->getPages()) as $key) {
            $list[$key] = 'modules/CDev/SimpleTaxes/edit.tpl';
        }

        return $list;
    }

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Taxes';
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Taxes';
    }

    // {{{ Widget-specific getters

    /**
     * Get tax 
     * 
     * @return \XLite\Module\CDev\SimpleTaxes\Model\Tax
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTax()
    {
        $page = \XLite\Core\Request::getInstance()->page;

        if (!$page) {
            $pages = $this->getPages();
            $page = key($pages);
        }

        return \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleTaxes\Model\Tax')->find($page);
    }

    /**
     * Check - current tax is vat or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isVAT()
    {
        return $this->getTax()->getIncluded();
    }

    // }}}

    // {{{ Actions

    /**
     * Update tax rate
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUpdate()
    {
        $tax = $this->getTax();

        $name = trim(\XLite\Core\Request::getInstance()->name);
        if (0 < strlen($name)) {
            $tax->setName($name);

        } else {
            \XLite\Core\TopMessage::addError('The name of the tax has not been preserved, because that is not filled');
        }

        // Set VAT base properties
        if ($this->isVAT()) {

            $vatMembership = \XLite\Core\Request::getInstance()->vatMembership;
            $vatMembership = $vatMembership
                ? \XLite\Core\Database::getRepo('XLite\Model\Membership')->find($vatMembership)
                : null;
            $tax->setVATMembership($vatMembership);

            $vatZone = \XLite\Core\Request::getInstance()->vatZone;
            $vatZone = $vatZone
                ? \XLite\Core\Database::getRepo('XLite\Model\Zone')->find($vatZone)
                : null;
            $tax->setVATZone($vatZone);
        }

        $rates = \XLite\Core\Request::getInstance()->rates;
        if (is_array($rates)) {
            foreach ($rates as $rateId => $data) {

                if ('%' == $rateId) {

                    // Temporary (fake) rate
                    $rate = null;

                } elseif (0 < $rateId) {

                    // Find rate by rateId
                    $rate = null;
                    foreach ($tax->getRates() as $r) {
                        if ($r->getId() == $rateId) {
                            $rate = $r;
                            break;
                        }
                    }

                } elseif (0 < strlen(trim($data['value']))) {

                    // Create new rate if value not empty
                    $rate = new \XLite\Module\CDev\SimpleTaxes\Model\Tax\Rate;
                    $tax->addRates($rate);
                    $rate->setTax($tax);
                    \XLite\Core\Database::getEM()->persist($rate);
                }

                if ($rate) {

                    $productClass = $data['productClass']
                        ? \XLite\Core\Database::getRepo('XLite\Model\ProductClass')->find($data['productClass'])
                        : null;
                    $rate->setProductClass($productClass);
                    unset($data['productClass']);

                    $membership = $data['membership']
                        ? \XLite\Core\Database::getRepo('XLite\Model\Membership')->find($data['membership'])
                        : null;
                    $rate->setMembership($membership);
                    unset($data['membership']);

                    $zone = $data['zone']
                        ? \XLite\Core\Database::getRepo('XLite\Model\Zone')->find($data['zone'])
                        : null;
                    $rate->setZone($zone);
                    unset($data['zone']);

                    $data['position'] = intval(trim($data['position']));
                    $data['value'] = doubleval(trim($data['value']));

                    $rate->map($data);
                }
            }
        }

        \XLite\Core\TopMessage::addInfo('Tax rates has been updated successfully');
        \Xlite\Core\Database::getEM()->flush();
    }

    /**
     * Remove tax rate 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionRemoveRate()
    {
        $rate = null;
        $rateId = \XLite\Core\Request::getInstance()->id;

        foreach ($this->getTax()->getRates() as $r) {
            if ($r->getId() == $rateId) {
                $rate = $r;
                break;
            }
        }

        if ($rate) {
            $this->getTax()->getRates()->removeElement($rate);
            \XLite\Core\Database::getEM()->remove($rate);
            \XLite\Core\TopMessage::addInfo('Tax rate has been deleted successfully');
            $this->setPureAction(true);

        } else {
            $this->valid = false;
            \XLite\Core\TopMessage::addError('Tax rate has not been deleted successfully');
        }

        \Xlite\Core\Database::getEM()->flush();
    }

    /**
     * Switch tax state
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSwitch()
    {
        $tax = $this->getTax();
        $tax->setEnabled(!$tax->getEnabled());
        \XLite\Core\Database::getEM()->flush();

        if ($tax->getEnabled()) {
            \XLite\Core\TopMessage::addInfo('Tax has been enabled successfully');

        } else {
            \XLite\Core\TopMessage::addInfo('Tax has been disabled successfully');
        }
    }

    // }}}
}

