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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\Affiliate\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PartnerSales extends \XLite\Module\Affiliate\Controller\Partner
{
    public $qty              = 0;
    public $saleTotal        = 0;
    public $commissionsTotal = 0;
    public $affiliatePending = 0;
    public $affiliatePaid    = 0;

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0 
     */
    protected function getLocation()
    {
        return 'Referred sales';
    }

    function getSalesStats()
    {
        if (!$this->auth->isAuthorized($this)) {
        	return null;
        }

        if (is_null($this->salesStats)) {
            $this->salesStats = array();
            $pp = new \XLite\Module\Affiliate\Model\PartnerPayment();
            $salesStats = $pp->searchSales(
                    $this->get('startDate'),
                    $this->get('endDate') + 24 * 3600,
                    $this->get('product_id'),
                    $this->getComplex('auth.profile.profile_id'),
                    $this->get('payment_status'),
                    null,
                    null,
                    null,
                    true
                    );
            // summarize search result into $this->salesStats
            array_map(array($this, 'sumSale'), $salesStats);
        }
        return $this->salesStats;
    }

    function getTopProducts()
    {
        if (is_null($this->topProducts)) {
            $this->topProducts = array();
            // getSalesStats must be called first to collect order items
            foreach ((array)$this->get('items') as $item) {
                $id = $item->get('product_id');
                if (!isset($this->topProducts[$id])) {
                    $this->topProducts[$id] = array(
                            "name" => $item->get('name'),
                            "amount" => $item->get('amount'),
                            "total" => $item->get('total'),
                            "commissions" => $item->get('commissions')
                            );
                } else {
                    $this->topProducts[$id]['amount'] += $item->get('amount');
                    $this->topProducts[$id]['amount']["total"] = sprintf("%.02f", doubleval($this->topProducts[$id]['amount']["total"] + $item->get('total')));
                    $this->topProducts[$id]['amount']["commissions"] = sprintf("%.02f", doubleval($this->topProducts[$id]['amount']["commissions"] + $item->get('commissions')));
                }
            }
            usort($this->topProducts, array($this, "cmpProducts"));
            $topProducts = array_chunk(array_reverse($this->topProducts), 10);
            $this->topProducts = $topProducts[0];
        }
        return $this->topProducts;
    }
    
    function cmpProducts($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }
    
    function sumSale($pp)
    {
        if ($pp->get('affiliate') == 0) {  // it's a partner buyer
            $this->salesStats[] = $pp;
            foreach ($pp->getComplex('order.items') as $item) {
                $this->qty += $item->get('amount');
            }
            if ($pp->isComplex('order.processed')) {
                $this->items = array_merge($this->items, $pp->getComplex('order.items'));
            }
            $this->salesTotal += $pp->getComplex('order.subtotal');
        } else { // it's a partner affiliate
            if ($pp->get('paid')) { // 
                $this->affiliatePaid += $pp->get('commissions');
            } else {
                $this->affiliatePending += $pp->get('commissions');
            }
        }
        $this->commissionsTotal += $pp->get('commissions');
    }
}
