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
class XLite_Module_EcommerceReports_Controller_Admin_EcommerceReports extends XLite_Controller_Admin_Stats
{
    public $page = "product_sales";
    public $pages = array(
            'general_stats' => 'General statistics',
            'product_sales' => 'Product sales',
            'sales_dynamics' => 'Sales dynamics',
            'geographic_sales' => 'Sales by geography',
            'sp_stats' => 'Shipping/Payment stats',
            'focused_audience' => 'Focused audience'
            );
    public $rawItems = null;

    function init() 
    {
        $this->params[] = "search_period";
        parent::init();
    }

    function action_get_data()
    {
        // form too big to GET data result, use POST
        // do not redirect after request
        $this->set("valid", false);
    }
    
    function fillForm() 
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }

    function getCategories() 
    {
        if (is_null($this->categories)) {
            $category = new XLite_Model_Category();
            $this->categories = $category->findAll();
            $names = array();
            $names_hash = array();
            for ($i=0; $i<count($this->categories); $i++) {
            	$name = $this->categories[$i]->getStringPath();
            	while (isset($names_hash[$name])) {
            		$name .= " ";
            	}
            	$names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->categories);
        }
        return $this->categories;
    }

    function isSelectedItem($arrayName, $itemID) 
    {
        $items = $this->get($arrayName);
        if (is_array($items)) {
            if (in_array($itemID, $items)) {
                return true;
            }
        }
        return false;
    }
    
    function getPeriod() 
    {
        if (is_null($this->period)) {
            if ($this->search_period == 6) {
                $this->period = array(
                    "fromDate" => mktime(0, 0, 0,
                        $this->get('startDateMonth'),
                        $this->get('startDateDay'),
                        $this->get('startDateYear')),
                    "toDate"   => mktime(23, 59, 59,
                        $this->get('endDateMonth'),
                        $this->get('endDateDay'),
                        $this->get('endDateYear'))
                );
            } else {
                list($startDateRaw, $endDateRaw) = $this->getDatesRaw($this->search_period);
                $this->period = array("fromDate" => $startDateRaw, "toDate" => ($endDateRaw + 24*60*60));
            }
        }
        return $this->period;
    }

    function getDatesRaw($period) 
    {
        $currentTime = getdate(time());
        switch ($period) {
            case -1:     // Whole period
                $startDateRaw = 0;
                $endDateRaw = time();
            break;
            case 0:     // Today
                $startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday'],$currentTime['year']);
                $endDateRaw = $startDateRaw;
            break;
            case 1:     // Yesterday
                $startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-1,$currentTime['year']);
                $endDateRaw = $startDateRaw;
            break;
            case 2:     // Current week
                $wday = ($currentTime['wday'] == 0) ? 7 : $currentTime['wday'];
                $startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
                $endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
            break;
            case 3:     // Previous week
                $wday = (($currentTime['wday'] == 0) ? 7 : $currentTime['wday']) + 7;
                $startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
                $endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
            break;
            case 4:     // Current month
                $startDateRaw = mktime(0,0,0,$currentTime['mon'],1,$currentTime['year']);
                $endDateRaw = mktime(0,0,0,$currentTime['mon']+1,0,$currentTime['year']);
            break;
            case 5:     // Previous month
                $startDateRaw = mktime(0,0,0,$currentTime['mon']-1,1,$currentTime['year']);
                $endDateRaw = mktime(0,0,0,$currentTime['mon'],0,$currentTime['year']);
            break;
        }

        return array($startDateRaw, $endDateRaw);
    }

    function getRawProducts() 
    {
        require_once LC_MODULES_DIR . 'EcommerceReports' . LC_DS . 'encoded.php';
        return func_EcommerceReports_getRawProducts($this);
    }

    function getRawItems($unique=false) 
    {
    	if (is_null($this->rawItems)) {
        	require_once LC_MODULES_DIR . 'EcommerceReports' . LC_DS . 'encoded.php';
        	$this->rawItems = func_EcommerceReports_getRawItems($this, $unique);
        }
        return $this->rawItems;
    }

    function getRawItemsNumber()
    {
        $this->getRawItems();
    	if (is_array($this->rawItems)) {
        	return count($this->rawItems);
        } else {
        	return 0;
        }
    }

    function getInCities($table) 
    {
        return "";
    }
    
    function getInMembership($table) 
    {
        return "";
    }
    
    function getInCountries($table) 
    {
        return "";
    }
    
    function getInStates($table) 
    {
        return "";
    }
    
    // SELECT extra fields {{{
    function getSelect($ot, $it, $pt)
    {
        return "";
    }
    function getFrom()
    {
        return "";
    }
    function getWhere($ot, $it, $pt)
    {
        return "";
    }

    function getNextDate() 
    {
        static $currentDate;

        $step = "_next" . ucfirst($this->get('stat_step'));
        if (isset($currentDate) && $currentDate == $this->getComplex('period.toDate')) {
            return false;
        }
        $currentDate = $this->$step($this->tsToDate( isset($currentDate) ? $currentDate : $this->getComplex('period.fromDate') ));
        if ($currentDate > $this->getComplex('period.toDate')) {
            $currentDate = $this->getComplex('period.toDate');
        }
        return $currentDate;
    }

    function _nextDay($ts)
    {
        if ($ts['day'] + 1 > $ts['days']) {
            $date = @mktime(0, 0, 0, $ts['month'] + 1, 1, $ts['year']);
        } else {
            $date = @mktime(0, 0, 0, $ts['month'], $ts['day'] + 1, $ts['year']);
        }
        return $date;
    }

    function _nextWeek($ts)
    {
        $adj = 7;
        if ($ts['weekday'] != 1) { // is NOT a Monday
            $adj = 8 - $ts['weekday'];
        }
        $date = @mktime(0, 0, 0, $ts['month'], $ts['day'] + $adj, $ts['year']);
        return $date;
    }

    function _nextQuarter($ts)
    {
        $quarters = array(1 => 1, 2 => 4, 3 => 7, 4 => 10);
        if ($ts['quarter'] == 4) {
            $date = @mktime(0, 0, 0, 1, 1, $ts['year'] + 1);
        } else {
            $date = @mktime(0, 0, 0, $quarters[$ts['quarter'] + 1], 1, $ts['year']);
        }
        return $date;
    }

    function _nextMonth($ts)
    {
        if ($ts['month'] + 1 > 12) {
            $date = @mktime(0, 0, 0, 1, 1, $ts['year'] + 1);
        } else {
            $date = @mktime(0, 0, 0, $ts['month'] + 1, 1, $ts['year']);
        }
        return $date;
    }

    function _nextYear($ts)
    {
        $date = @mktime(0, 0, 0, 1, 1, $ts['year'] + 1);
        return $date;
    }

    function tsToDate($stamp)
    {
        $ts = array();
        $ts['day'] = @date("j", $stamp);
        $ts['weekday'] = @date("w", $stamp);
        $ts['days'] = ($dt = @date("t", $stamp)) == 0 ? 7 : $dt;
        $ts['month'] = @date("n", $stamp);
        $ts['quarter'] = ceil($ts['month'] / 3);
        $ts['year'] = @date("Y", $stamp);
        return $ts;
    }

    function count($array) 
    {
        return count($array);
    }

    function getProductIDs() 
    {
        $ids = array();
        $pid = $this->get('product_id');
        if (!empty($pid)) {
            $ids = array($pid);
        }
        return $ids;
    }

    function getRowClass($row, $class1, $class2 = null) 
    {
        static $idx;
        if (!isset($idx)) $idx = 0;
        return $idx++ % 2 ? $class1 : $class2;
    }
}
