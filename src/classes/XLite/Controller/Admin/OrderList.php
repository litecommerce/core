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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Orders list controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OrderList extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Common method to determine current location
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Search orders';
    }

    /**
     * doActionUpdate 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Order')->updateInBatchById($this->getPostedData());
    }

    /**
     * doActionDelete 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDelete()
    {
        \XLite\Core\Database::getRepo('\XLite\Model\Order')->deleteInBatchById($this->getToDelete());
    }
    

    /**
     * getDateValue 
     * FIXME - to remove
     * 
     * @param string $fieldName Field name (prefix)
     *  
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDateValue($fieldName)
    {
        $dateValue = \XLite\Core\Request::getInstance()->$fieldName;

        if (!isset($dateValue)) {
            $nameDay   = $fieldName . 'Day';
            $nameMonth = $fieldName . 'Month';
            $nameYear  = $fieldName . 'Year';

            if (isset(\XLite\Core\Request::getInstance()->$nameMonth)
                && isset(\XLite\Core\Request::getInstance()->$nameDay)
                && isset(\XLite\Core\Request::getInstance()->$nameYear))
            {
                $dateValue = mktime(
                    0, 0, 0,
                    \XLite\Core\Request::getInstance()->$nameMonth,
                    \XLite\Core\Request::getInstance()->$nameDay,
                    \XLite\Core\Request::getInstance()->$nameYear
                );
            }
        }

        return $dateValue;

    }

    /**
     * doActionSearch 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {
        $ordersSearch = array();
        $searchParams   = \XLite\View\ItemsList\Order\Admin\Search::getSearchParams();

        // Prepare dates

        $this->startDate = $this->getDateValue('startDate');
        $this->endDate   = $this->getDateValue('endDate');

        if (
            0 === $this->startDate
            || 0 === $this->endDate
            || $this->startDate > $this->endDate
        ) {
            $date = getdate(time());
            $this->startDate = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
            $this->endDate   = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
        }
        
        foreach ($searchParams as $modelParam => $requestParam) {
            if (\XLite\Model\Repo\Order::P_DATE === $requestParam) {
                $ordersSearch[$requestParam] = array($this->startDate, $this->endDate);
            } elseif (isset(\XLite\Core\Request::getInstance()->$requestParam)) {
                $ordersSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }
        }
        
        $this->session->set(\XLite\View\ItemsList\Order\Admin\Search::getSessionCellName(), $ordersSearch);
        $this->set('returnUrl', $this->buildUrl('order_list', '', array('mode' => 'search')));
    }

    /**
     * Get search conditions
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParams = $this->session->get(\XLite\View\ItemsList\Order\Admin\Search::getSessionCellName());

        if (!is_array($searchParams)) {
            $searchParams = array();
        }

        return $searchParams;
    }

    /**
     * Get search condition parameter by name
     * 
     * @param string $paramName 
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {
            $return = $searchParams[$paramName];
        }

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Get date condition parameter (start or end)
     * 
     * @param boolean $start Start date flag, otherwise - end date  OPTIONAL
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDateCondition($start = true)
    {
        $dates = $this->getCondition(\XLite\Model\Repo\Order::P_DATE);
        $n = (true === $start) ? 0 : 1;

        return isset($dates) && isset($dates[$n])
            ? $dates[$n]
            : null;
    }


    /**
     * doActionExportXls 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function doActionExportXls()
    {
        $w = new \XLite\View\ExportXLS();
        $w->component = $this;
        $this->startDownload('orders.xls');
        $this->ColumnCount = 9;
        $this->RowCount = $this->get('count') + 2;
        $this->endRow = $this->get('count') + 1;
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

    /**
     * getExportFormats 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getExportFormats()
    {
        return array("export_xls" => "MS Excel XP/XML");
    }

    /**
     * getStartXML 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getStartXML()
    {
        return '<?xml version="1.0"?>'."\n";;
    }*/
}
