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

namespace XLite\Module\CDev\SalesTax\Controller\Admin;

/**
 * Taxes controller
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Taxes extends \XLite\Controller\Admin\Taxes implements \XLite\Base\IDecorator
{
    /**
     * Page key 
     */
    const PAGE_SALES_TAX = 'salesTax';


    /**
     * Get pages sections
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPages()
    {
        $list = parent::getPages();

        $list[self::PAGE_SALES_TAX] = 'Sales tax';

        return $list;
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
        $list = parent::getPageTemplates();

        $list[self::PAGE_SALES_TAX] = 'modules/CDev/SalesTax/edit.tpl';

        return $list;
    }

    // {{{ Widget-specific getters

    /**
     * Get tax 
     * 
     * @return object
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTax()
    {
        $tax = parent::getTax();

        if (!$tax && $this->getPage() == self::PAGE_SALES_TAX) {
             $tax = \XLite\Core\Database::getRepo('XLite\Module\CDev\SalesTax\Model\Tax')->find(1);
        }

        return $tax;
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
    protected function doActionSalesTaxUpdate()
    {
        $tax = $this->getTax();

        $name = trim(\XLite\Core\Request::getInstance()->name);
        if (0 < strlen($name)) {
            $tax->setName($name);

        } else {
            \XLite\Core\TopMessage::addError('The name of the tax has not been preserved, because that is not filled');
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
                    $rate = new \XLite\Module\CDev\SalesTax\Model\Tax\Rate;
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
    protected function doActionSalesTaxRemoveRate()
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
    protected function doActionSalesTaxSwitch()
    {
        $tax = $this->getTax();
        $tax->setEnabled(!$tax->getEnabled());
        \XLite\Core\Database::getEM()->flush();
        $this->setPureAction(true);

        if ($tax->getEnabled()) {
            \XLite\Core\TopMessage::addInfo('Tax has been enabled successfully');

        } else {
            \XLite\Core\TopMessage::addInfo('Tax has been disabled successfully');
        }
    }

    // }}}
}

