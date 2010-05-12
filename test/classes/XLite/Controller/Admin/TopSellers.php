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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_TopSellers extends XLite_Controller_Admin_Stats
{
    public $todayItems = array();
    public $weekItems = array();
    public $monthItems = array();
    public $sort_by = "amount";
    public $counter = array(0,1,2,3,4,5,6,7,8,9);

    protected $topProducts = array();

    function getPageTemplate()
    {
        return "top_sellers.tpl";
    }

    function handleRequest()
    {
        // typedef
        $statRec = array("today" => 0, "week" => 0, "month" => 0);
        $this->stat = array(
                "processed" => $statRec,
                "queued" => $statRec,
                "failed" => $statRec,
                "not_finished" => $statRec,
                "total" => $statRec,
                "paid" => $statRec);

        $order = new XLite_Model_Order();
        $date = $this->get("monthDate");
        array_map(array($this, "collect"), $order->findAll("(status='P' OR status='C') AND date>=$date"));
        $this->sort("todayItems");
        $this->sort("weekItems");
        $this->sort("monthItems");

        parent::handleRequest();
    }

    function getTopProduct($period, $pos, $property)
    {
        return is_null($val = $this->getComplex("topProducts." . $period . "Items." . $pos . "." . $property)) ? "" : $val;
    }

    function collect($order)
    {
        $items = $order->get("items");
        if ($order->get("date") >= $this->get("todayDate")) {
            $this->todayItems = array_merge($this->todayItems, $items);
        }
        if ($order->get("date") >= $this->get("weekDate")) {
            $this->weekItems = array_merge($this->weekItems, $items);
        }
        if ($order->get("date") >= $this->get("monthDate")) {
            $this->monthItems = array_merge($this->monthItems, $items);
        }
    }

    function sort($name)
    {
        $this->topProducts[$name] = array();
        foreach ((array) $this->get($name) as $item) {
            $id = $item->get("product_id");
            if (!$id) continue;
            if (!isset($this->topProducts[$name][$id])) {
                $this->topProducts[$name][$id] = array(
                        "id" => $id,
                        "name" => $item->get("name"),
                        "amount" => $item->get("amount")
                        );
            } else {
                $this->topProducts[$name][$id]["amount"] += $item->get("amount");
            }
        }
        usort($this->topProducts[$name], array($this, "cmpProducts"));
        $topProducts = array_chunk(array_reverse($this->topProducts[$name]), 10);
        $this->topProducts[$name] = isset($topProducts[0]) ? $topProducts[0] : null;
    }

    function cmpProducts($p1, $p2)
    {
        $key = $this->sort_by;
        if ($p1[$key] == $p2[$key]) {
            return 0;
        }
        return ($p1[$key] < $p2[$key]) ? -1 : 1;
    }

}
