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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Stats extends \XLite\Controller\Admin\AAdmin
{
    /**
     * params 
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     */
    public $params = array('target');

    /**
     * getTodayDate 
     * 
     * @return integer
     * @see    ____func_see____
     */
    function getTodayDate()
    {
        if (is_null($this->todayDate)) {
            $this->todayDate = mktime(0, 0 ,0 , date('m'), date('d'), date('Y'));
        }
        return $this->todayDate;
    }

    /**
     * getWeekDate 
     * 
     * @return int
     * @see    ____func_see____
     */
    function getWeekDate()
    {
        if (is_null($this->weekDate)) {
            $this->weekDate  = mktime(0, 0 ,0 , date('m'), date('d') + (((date('w') == 0) ? -7 : ( -1 * date('w'))) + 1), date('Y'));
        }
        return $this->weekDate;
    }

    /**
     * getMonthDate 
     * 
     * @return int
     * @see    ____func_see____
     */
    function getMonthDate()
    {
        if (is_null($this->monthDate)) {
            $this->monthDate = mktime(0, 0 ,0 , date('m'), 1, date('Y'));
        }
        return $this->monthDate;
    }
}
