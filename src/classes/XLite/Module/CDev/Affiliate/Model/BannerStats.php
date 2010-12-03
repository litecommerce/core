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

namespace XLite\Module\CDev\Affiliate\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class BannerStats extends \XLite\Model\AModel
{
    public $fields = array(
            "stat_id"    => 0,
            "stat_type"  => "",    // stat_type := C | V, click and view
            "partner_id" => 0,
            "product_id" => 0,
            "banner_id"  => 0,
            "date"       => 0,
            "referrer"   => '',
            );

    public $autoIncrement = "stat_id";
    public $alias = "banner_stats";
    public $defaultOrder = "date";

    public $partner = null;

    /**
    * Logs view banner request
    */
    function logView() 
    {
        $this->set('stat_type', "V");
        $this->set('partner_id', $_GET['partner']);
        $this->set('product_id', $_GET['product_id']);
        $this->set('banner_id',  $_GET['banner_id']);
        $this->set('referrer',   $_SERVER['HTTP_REFERER']);
        $this->set('date',       time());
        $this->create();
    }

    function logClick() 
    {
        $this->set('stat_type', "C");
        $this->set('partner_id', $_GET['partner']);
        $this->set('product_id', $_GET['product_id']);
        $this->set('banner_id',  $_GET['banner_id']);
        $referrer = isset($_GET['referrer']) ? $_GET['referrer'] : $_SERVER['HTTP_REFERER'];
        $this->set('referrer', $referrer);
        $this->set('date', time());
        $this->create();
    }

    function searchTopPerformers($startDate, $endDate, $reportBy) 
    {
        $startDate = intval($startDate);
        $endDate = intval($endDate);

        // select partners orders
        $o = new \XLite\Model\Order();
        $ordersTable = $o->db->getTableByAlias($o->alias);
        $sql = "SELECT partnerClick AS stat_id, subtotal FROM $ordersTable WHERE partnerClick<>0 AND (status='P' OR status='C')";
        $orders = $this->db->getAll($sql);


        $statsTable = $this->db->getTableByAlias($this->alias);

        if ($reportBy == "referrer") {
            // select referrer -> click summary 
            $sql = "SELECT referrer, count(*) AS clicks FROM $statsTable WHERE stat_type='C' AND date>='$startDate' AND date<='$endDate' AND partner_id<>0 GROUP BY referrer";
            $referrers = $this->db->getAll($sql);
            // fill empty referrers
            foreach ($referrers as $rid => $referrer) {
                if (empty($referrers[$rid]['referrer'])) {
                    $referrers[$rid]['referrer'] = "Unknown";
                }
            }

            // select referrer click id's
            $sql = "SELECT stat_id, referrer FROM $statsTable WHERE stat_type='C' AND date>='$startDate' AND date<='$endDate' AND partner_id<>0";
            $stats = $this->db->getAll($sql);
            // fill empty referrers
            foreach ($stats as $sid => $stat) {
                if (empty($stats[$sid]['referrer'])) {
                    $stats[$sid]['referrer'] = "Unknown";
                }
            }

            foreach ($referrers as $rid => $referrer) {
                // add referrer
                $referrers[$rid]['sales'] = 0;    // initial value
                $referrers[$rid]['total'] = 0.00; // initial value
                $referrerID = $referrer['referrer'];
                foreach ($stats as $sid => $stat) {
                    if ($stat['referrer'] == $referrerID) {
                        $statID = $stat['stat_id'];
                        // search subtotals for every referrer click
                        foreach ($orders as $oid => $order) {
                            if ($order['stat_id'] == $statID) {
                                $referrers[$rid]['sales']++;
                                $referrers[$rid]['total'] += $order['subtotal'];
                            }
                        }
                    }
                }
            }
            return $referrers;

        } else {

            // select partner -> click summary
            $sql = "SELECT partner_id, count(*) as clicks FROM $statsTable WHERE stat_type='C' AND date>='$startDate' AND date<='$endDate' AND partner_id<>0 GROUP BY partner_id";
            $clicks = $this->db->getAll($sql);

            // select partner click id's 
            $sql = "SELECT stat_id, partner_id FROM $statsTable WHERE stat_type='C' AND date>='$startDate' AND date<='$endDate' AND partner_id<>0";
            $stats = $this->db->getAll($sql);

            // calculate sales statistics for partners
            foreach ($clicks as $cid => $click) {
                // add partner
                $clicks[$cid]['sales'] = 0;    // initial value
                $clicks[$cid]['total'] = 0.00; // initial value
                $partnerID = $click['partner_id'];
                // use click ids to find processed orders
                foreach ($stats as $sid => $stat) {
                    if ($stat['partner_id'] == $partnerID) {
                        $statID = $stat['stat_id'];
                        // search subtotals for every partner click
                        foreach ($orders as $oid => $order) {
                            if ($order['stat_id'] == $statID) {
                                $clicks[$cid]['sales']++;
                                $clicks[$cid]['total'] += $order['subtotal'];
                            }
                        }
                    }
                }
            }
            return $clicks;
        }
    }

    function search($partner_id, $startDate, $endDate, $sort_by, $homeBanner, $productBanner, $directLink) 
    {
        $where = array();
        if (!empty($partner_id)) {
        	$partner_id = intval($partner_id);
            $where[] = "partner_id='".$partner_id."'";
        }
        if ($startDate) {
        	$startDate = intval($startDate);
            $where[] = "date>='$startDate'";
        }
        if ($endDate) {
        	$endDate = intval($endDate);
            $where[] = "date<='$endDate'";
        }
        $table = $this->db->getTableByAlias($this->alias);
        // select all views
        $cw = $where;
        $cw[] = "stat_type='V'";
        $sql = "SELECT COUNT(*) as views, banner_id, product_id ".
               "FROM $table ".
               "WHERE ". implode(' AND ', $cw) . " ".
               "GROUP BY banner_id, product_id";
        $views = $this->db->getAll($sql);
        // select all clicks
        $cc = $where;
        $cc[] = "stat_type='C'";
        $sql = "SELECT COUNT(*) as clicks, banner_id, product_id ".
               "FROM $table ".
               "WHERE ". implode(' AND ', $cc) . " ".
               "GROUP BY banner_id, product_id";
        $clicks = $this->db->getAll($sql);
        // summarize
        $result = array();
        // add clicks..
        foreach ($clicks as $cid => $click) {
            $result[] = $click;
        }
        // add views..
        foreach ($views as $vid => $view) {
            foreach ($result as $rid => $row) {
                if ($row['banner_id'] == $view['banner_id'] && $row['product_id'] == $view['product_id']) {
                    $result[$rid]['views'] += $view['views'];
                    continue 2;
                }
            }
            $result[] = $view;
        }
        // fill empty sockets
        foreach ($result as $rid => $row) {
            if (!isset($row['views'])) $result[$rid]['views'] = 0;
            if (!isset($row['clicks'])) $result[$rid]['clicks'] = 0;
        }
        // pre-sorting
        if ($sort_by == "clicks") {
            $skel = array("clicks" => 0, "views" => 0, "banner_id" => null, "product_id" => null);
        } else {
            $skel = array("views" => 0, "clicks" => 0, "banner_id" => null, "product_id" => null);
        }
        foreach ($arr = $result as $rid => $row) {
            $newRow = array_merge($skel, $row);
            $result[$rid] = $newRow;
        }
        // calculate rates and create banner and product instances
        foreach ($result as $i => $row) {
            $result[$i]['rate'] = "0.00";
            if ($result[$i]['views'] != 0) {
                $result[$i]['rate'] =  sprintf("%.02f", doubleval($result[$i]['clicks'] / $result[$i]['views']));
            }
            if (isset($result[$i]['banner_id'])) {
                $banner = new \XLite\Module\CDev\Affiliate\Model\Banner();
                $banner->set('deleted', !$banner->find("banner_id=".$result[$i]['banner_id']));
                $result[$i]['banner'] = $banner;
            }
            // create product instance
            else if (isset($result[$i]['product_id'])) {
                $product = new \XLite\Model\Product();
                $product->set('deleted', !$product->find("product_id=".$result[$i]['product_id']));
                $result[$i]['product'] = $product;
            }
            // direct link
            else {
                $result[$i]['direct_link'] = true;
            }
        }
        // filter by banner category
        $result2 = array();
        foreach ($result as $k => $r) {
            if (isset($r['banner']) && isset($homeBanner) ||
                isset($r['product']) && isset($productBanner) ||
                $r['direct_link'] && isset($directLink))
            {
                $result2[$k] = $r;
            }
        }
        $result = $result2;
        sort($result);
        return array_reverse($result);
    }

    function getPartner() 
    {
        if (is_null($this->partner)) {
            $profile = new \XLite\Model\Profile($this->get('partner_id'));
            if ($profile->is('partner') && $profile->is('enabled')) {
                $this->partner = $profile;
            }
        }
        return $this->partner;
    }
}
