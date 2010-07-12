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

namespace XLite\Module\EcommerceReports\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SalesDynamics extends EcommerceReports
{
    function handleRequest()
    {
        if ($this->get('search')) {
            $this->setComplex('session.salesDynamics', $_POST);
        } else {
            $this->setComplex('session.salesDynamics', null);
        }
        parent::handleRequest();
    }

    function getLabel($date) 
    {
        switch ($this->get('stat_step')) {
            case "day":
            case "week":
                $label = @date('M',$date) . " " . @date('j', $date);
                break;
            case "month":
            case "quarter":
                $label = @date('M',$date);
                break;
            case "year":
                $label = @date('Y',$date);
                break;
        }
        return $label;
    }

    function sumSale($items, $range) 
    {
        $sum = 0;
        foreach ($items as $item) {
            if ($item['date'] >= $range[0] && $item['date'] < $range[1]) {
                $sum = $sum + $item['price'] * $item['amount'];
            }
        }
        return $sum;
    }

    function sumSaleQuantity($items, $range) 
    {
        $qty = 0;
        foreach ($items as $item) {
            if ($item['date'] >= $range[0] && $item['date'] <= $range[1]) {
                $qty = $qty + $item['amount'];
            }
        }
        return $qty;
    }

    function sumSaleNumber($items, $range) 
    {
        $number = array();
        foreach ($items as $item) {
            if ($item['date'] >= $range[0] && $item['date'] <= $range[1]) {
                $number[$item['order_id']] = 1;
            }
        }
        return count($number);
    }

    function getSales() 
    {
        if (is_null($this->sales)) {
            $this->sales = array(
                    "x" => array(),
                    "y" => array(),
                    "labels" => array(),
                    );
            $func = "sumSale" . $this->get('show');
            $startDate = $this->getComplex('period.fromDate');
            $items = $this->get('rawItems');
            $x = array();
            $y = array();
            $labels = array();
            while (($nextDate = $this->get('nextDate')) !== false) {
                $x[] = $startDate;
                $range = array($startDate, $nextDate);
                $y[] = $this->$func($items, $range);
                $labels[] = $this->getLabel($startDate);
                $startDate = $nextDate;
            }
            $this->sales['x'] = $x;
            $this->sales['y'] = $y;
            $this->sales['labels'] = $labels;
        }
        return $this->sales;
    }

    function exportSales() 
    {
        $this->salesData = array();
        $sales = $this->get('sales');
        foreach ($sales['x'] as $xid => $x) {
            $this->salesData[$x] = $sales['y'][$xid];
        }
        $w = new \XLite\View\AView();
        $w->component = $this;
        $w->set('template', "modules/EcommerceReports/export_xls.tpl");
        $this->startDownload('sales.xls');
        $this->ColumnCount = 2;
        $this->RowCount = count($this->salesData) + 2;
        $this->endRow = count($this->salesData) + 1;
        $profile = $this->auth->get('profile');
        $time = time();
        $this->create_date = strftime("%Y-%m-%d", $time);
        $this->create_time = strftime("%H:%M:%S", $time);
        $this->author = $profile->get('billing_firstname') . " " . $profile->get('billing_lastname');
        $w->init();
        $w->display();

        // do not output anything
        $this->set('silent', true);
    }

    function action_get_data() 
    {
        if ($this->get('export')) {
            $this->exportSales();
        }
        parent::action_get_data();
    }

    function getStartXML() 
    {
        return '<?xml version="1.0"?>'."\n";;
    }
}
