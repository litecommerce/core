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
     * fillForm 
     * FIXME - to remove
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        $startDate = $this->getDateValue('startDate');
        $endDate = $this->getDateValue('endDate');

        if (0 == $startDate || 0 == $endDate) {
            $date = getdate(time());
            $startDate = mktime(0, 0, 0, $date['mon'], 1, $date['year']);
            $endDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);
        }

        $this->startDate = $startDate;
        $this->endDate = $endDate;

    }

    /**
     * getDateValue 
     * FIXME - to remove
     * 
     * @param string $fieldName field name (prefix)
     *  
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDateValue($fieldName)
    {
        $dateValue = \XLite\Core\Request::getInstance()->$fieldName;;

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

