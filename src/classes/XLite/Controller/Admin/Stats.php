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
class XLite_Controller_Admin_Stats extends XLite_Controller_Admin_AAdmin
{
    public $params = array('target');
    public $page = "orders_stats";
    public $pages = array('orders_stats' => 'Order statistics',
                       'top_sellers' => 'Top sellers',
                       'searchStat' => 'Search statistics',
                       );

    function getTodayDate()
    {
        if (is_null($this->todayDate)) {
            $this->todayDate = mktime(0, 0 ,0 , date('m'), date('d'), date('Y'));
        }
        return $this->todayDate;
    }

    function getWeekDate()
    {
        if (is_null($this->weekDate)) {
            $this->weekDate  = mktime(0, 0 ,0 , date('m'), date('d') + (((date('w') == 0) ? -7 : ( -1 * date('w'))) + 1), date('Y'));
        }
        return $this->weekDate;
    }

    function getMonthDate()
    {
        if (is_null($this->monthDate)) {
            $this->monthDate = mktime(0, 0 ,0 , date('m'), 1, date('Y'));
        }
        return $this->monthDate;
    }
}
