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

namespace XLite\Module\CDev\GiftCertificates\Model;

/**
 * Order
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Order extends \XLite\Model\Order implements \XLite\Base\IDecorator
{
    /**
     * Gift certificate (cache)
     * 
     * @var    \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $gc = null;

    /**
     * Skip shipping cost getter flag
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $skipShippingCostRecursion = false;

    /**
     * Shipped certificates count
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shippedCertificates = null;

    /**
     * Shipped items 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shippedItems = null;

    /**
     * Shipping cost 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $shippingCost = null;

    /**
     * Constructor
     * 
     * @param mixed $param Parameter OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($param = null)
    {
        // new fields
        $this->fields['gcid'] = ''; // gift certificate unique ID or 0
        $this->fields['payedByGC'] = ''; // how much of the order is payed by GC

        parent::__construct($param);
    }

    /**
     * Calc order total 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calcTotal()
    {
        $this->shippingCost = null;

        parent::calcTotal();

        $gcid = $this->get('gcid');
        if ($gcid) {
            $gcAmount = ($this->xlite->is('adminZone') && 0 < $this->get('payedByGC'))
                ? $this->get('payedByGC')
                : $this->getGC()->get('debit');

            $this->_payedByGC = min($this->get('total'), $gcAmount);

            $this->set('total', $this->get('total') - $this->_payedByGC);

        } else {
            $this->_payedByGC = 0;
        }

        $this->set('payedByGC', $this->_payedByGC);
    }

    /**
     * Calculate order totals 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function calcTotals()
    {
        // for PHP 5.2.4 in order not to affect the items during tax calculation, the cache must be cleaned up
        $this->refresh('items');

        parent::calcTotals();

        $this->refresh('items');
    }

    /**
     * Get gift certificate
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getGC()
    {
        if (is_null($this->gc)) {
            $this->gc = $this->get('gcid')
                ? new \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate($this->get('gcid'))
                : null;
        }

        return $this->gc;
    }

    /**
     * Apply gift certificate
     * 
     * @param \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate $gc Gift certificate
     *  
     * @return integer Operation status
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setGC($gc)
    {
        $result = \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate::GC_DISABLED;

        if (is_null($gc)) {

            $this->gc = null;
            $this->set('gcid', '');
            $this->calcTotals();
            $result = \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate::GC_OK;

        } elseif (
            $gc instanceof \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate
            && 'A' == $gc->get('status')
            && 0 < $gc->get('debit')
        ) {

            $this->gc = $gc;
            $this->set('gcid', $gc->get('gcid'));
            $this->calcTotals();
            $result = \XLite\Module\CDev\GiftCertificates\Model\GiftCertificate::GC_OK;

        }

        return $result;
    }
    
    /**
     * Called when an order becomes processed, before saving it to the database
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function processed()
    {
        parent::processed();

        $this->setGCStatus('A');
    }

    /**
     * Order after-checkout postprocessing
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkedOut()
    {
        if (\XLite\Core\Request::getInstance()->target != 'callback') {
            $this->calcTotals();
        }

        parent::checkedOut();

        $this->changeGCDebit(-1);
    }

    /**
     * Decline order
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function declined()
    {
        parent::declined();

        $this->setGCStatus('P');
    }

    /**
     * Queued order
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function queued()
    {
        parent::queued();

        $this->setGCStatus('P');
    }

    /**
     * Order after-checkout postprocessing
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function uncheckedOut()
    {
        parent::uncheckedOut();
        $this->changeGCDebit(1);
        $this->setGCStatus('D');
    }

    /**
     * Change gift certificate debit 
     * 
     * @param integer $sign Sign (1 or -1)
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function changeGCDebit($sign)
    {
        $gc = $this->getGC();
        if (!is_null($gc)) {
            $gc->set('debit', $gc->get('debit') + $sign * $this->get('payedByGC'));
            $gc->set('status', 0 >= $gc->get('debit') ? 'U' : 'A');
            $gc->update();
        }
    }

    /**
     * Set gift certificate status
     * 
     * @param string $status Status code
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setGCStatus($status)
    {
        foreach ($this->getItems() as $item) {
            if (!is_null($item->get('gc'))) {
                $gc = $item->get('gc');
                $gc->set('status', $status);
                $gc->update();
            }
        }
    }

    /**
     * Checkout order
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkout()
    {
        if (!is_null($this->getGC())) {
            // re-calculate total during checkout to prevemt double-payment
            $this->calcTotals();
            $this->update();
        }

        parent::checkout();
    }

    /**
     * Getter
     * 
     * @param string $name Property name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function get($name)
    {
        // arounding problem in the "skins/default/en/shopping_cart/totals.tpl"
        if (
            'shipping_cost' == $name
            && !$this->skipShippingCostRecursion
        ) {
            $this->skipShippingCostRecursion = true;
            $result = $this->getShippingCost();
            $this->skipShippingCostRecursion = false;
        }

        return isset($result) ? $result : parent::get($name);
    }

    /**
     * Get shipping cost 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShippingCost()
    {
        if (is_null($this->shippingCost)) {

            /* TODO - check this
            // LiteCommerce 1.2.2 bug fix
            if (!$this->is('shipped')) {
                $this->shippingCost = 0;
                return false;
            }
            */

            // find shipped certificates
            $count = $this->countShippedCertificates();
            if ($count) {
                $this->shippingCost = $this->hasShippedItems() ? parent::getShippingCost() : 0;
                $this->shippingCost += $count * $this->config->GiftCertificates->shippingCost;

            } else {
                $this->shippingCost = parent::getShippingCost();
            }
        }

        return $this->shippingCost;
    }

    /**
     * Check - order shipped or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShipped()
    {
        return parent::isShipped()
            || 0 < $this->countShippedCertificates();
    }

    /**
     * Get shipped gift certificates count
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function countShippedCertificates()
    {
        if (is_null($this->shippedCertificates)) {

            $this->shippedCertificates = 0;
            foreach ($this->getItems() as $item) {
                $gc = $item->get('gc');
                if (!is_null($gc) && $gc->get('send_via') == 'P') {
                    $this->shippedCertificates++;
                }
            }

        }

        return $this->shippedCertificates;
    }

    /**
     * Check - has order shipped items or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasShippedItems()
    {
        if (is_null($this->shippedItems)) {

            $this->shippedItems = false;
            foreach ($this->getItems() as $item) {
                if ($item->isShipped()) {
                    $this->shippedItems = true;
                    break;
                }
            }
        }

        return $this->shippedItems;
    }

    /**
     * Check - shipping is available or not
     * TODO: isShippingAvailable is no more exists - rework it
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingAvailable()
    {
        return ($this->isShipped() && $this->getItemsCount() == $this->countShippedCertificates())
            ? true
            : parent::isShippingAvailable();
    }

    /**
     * Check - shipping is defined or not
     * TODO: isShippingDefined is no more exists - rework it
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isShippingDefined()
    {
        return (!parent::isShippingDefined() && $this->isShipped()) ? true : parent::isShippingDefined();
    }

    /**
     * Setter
     * 
     * @param string $property Property name
     * @param mixed  $value    Value
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function set($property, $value)
    {
        if (
            'shippingTaxes' == $property
            && $this->getItemsCount() == $this->countShippedCertificates()
            && !$this->config->Taxes->prices_include_tax
        ) {
            $value = array();
        }

        parent::set($property, $value);
    }

    /**
     * Check - has order specified gift certificate or not
     * 
     * @param string $gcid Gift certificate id
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasGC($gcid)
    {
        $has = false;

        if ($gcid) {
            foreach ($this->getItems() as $item) {
                if ($item->get('gcid') == $gcid) {
                    $has = true;
                    break;
                }
            }
        }

        return $has;
    }

    /**
     * Check - has order gift certificates or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasGiftCertificates()
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            if (!is_null($item->get('gc'))) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Check - has order regular products or not
     * 
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasRegularProducts()
    {
        $result = false;

        foreach ($this->getItems() as $item) {
            $product = $item->getProduct();
            if (!is_null($product) && $product->isExists()) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Check - can apply gift certificate to order or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canApplyGiftCertificate()
    {
        $option = $this->config->GiftCertificates->prohibit_pay_gc;

        return 'N' == $option
            || ('O' == $option && ($this->hasRegularProducts() || !$this->hasGiftCertificates()))
            || ('P' == $option && !$this->hasGiftCertificates());
    }
}
