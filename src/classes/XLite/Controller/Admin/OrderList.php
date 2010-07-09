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


/**
 * Orders list controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Admin_OrderList extends XLite_Controller_Admin_AAdmin
{
    public $params = array('target', 'mode', 'order_id', 'login', 'status');

    /**
     * noSuchUser 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $noSuchUser = false;

    /**
     * startDate 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $startDate = null;

    /**
     * endDate 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $endDate = null;

    /**
     * orders 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $orders = null;

    /**
     * fillForm 
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
        $dateValue = null;

        if (isset(XLite_Core_Request::getInstance()->$fieldName)) {
            $dateValue = XLite_Core_Request::getInstance()->get($fieldName);

        } else {
            $nameDay   = $fieldName . 'Day';
            $nameMonth = $fieldName . 'Month';
            $nameYear  = $fieldName . 'Year';

            if (isset(XLite_Core_Request::getInstance()->$nameMonth)
                && isset(XLite_Core_Request::getInstance()->$nameDay)
                && isset(XLite_Core_Request::getInstance()->$nameYear))
            {
                $dateValue = mktime(
                    0, 0, 0,
                    XLite_Core_Request::getInstance()->get($nameMonth),
                    XLite_Core_Request::getInstance()->get($nameDay),
                    XLite_Core_Request::getInstance()->get($nameYear)
                );
            }
        }

        return $dateValue;

    }

    /**
     * isQuickSearch 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isQuickSearch()
    {
    	return ('export_xls' != XLite_Core_Request::getInstance()->action);
    }

    /**
     * getOrders 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOrders()
    {
        $origProfile = true;
        $enhacedSearch = false;
        $onlyNormalProfile = false;

        if (is_null($this->orders)) {

            $order = new XLite_Model_Order();
            $order->collectGarbage();
            $order->fetchKeysOnly = false;
            $order->fetchObjIdxOnly = $this->isQuickSearch();

            $login = XLite_Core_Request::getInstance()->login;

            if (!empty($login)) {

                $profile = new XLite_Model_Profile();
                $profile->_range = null;

                if (!$profile->find("login='" . addslashes($login) . "' AND order_id != '0'")) {
                    $this->noSuchUser = true;

                	if ($profile->find("login='" . addslashes($login) . "' AND order_id = '0'")) {
                    	$this->noSuchUser = false;
                        $origProfile = false;

                	} else {
                        $where = "login LIKE '%" . addslashes($login) . "%'";
                        $users = $profile->findAll($where);

                        if (is_array($users) && count($users) > 0) {
                            $this->noSuchUser = false;
                        	$enhacedSearch = true;
                        }
                	}
                }

            } else {
                $profile = null;
            }

            if (!$enhacedSearch) {

            	if ((!empty($login) && $profile->get('profile_id')) || empty($login)) {
                    $this->orders = $order->search(
                            $profile,
                            XLite_Core_Request::getInstance()->order_id,
                            XLite_Core_Request::getInstance()->status,
                            $this->getDateValue('startDate'),
                            $this->getDateValue('endDate') + 24 * 3600,
                            $origProfile
                        );
                }

            	if (0 == count($this->orders) && is_object($profile)) {
                    $where = "login='" . addslashes($login) . "'";
                    $users = $profile->findAll($where);
                    $onlyNormalProfile = true;
            	}
            }

            if ($enhacedSearch || (!$enhacedSearch && 0 == count($this->orders))) {
            	$orders = $order->search(
                        null,
                        XLite_Core_Request::getInstance()->order_id,
                        XLite_Core_Request::getInstance()->status,
                        $this->getDateValue('startDate'),
                        $this->getDateValue('endDate') + 24 * 3600
                    );

                $this->orders = array();

                if (is_array($orders) && count($orders) > 0) {

                    for ($i = 0; $i < count($orders); $i++) {

                		if ($order->isObjectDescriptor($orders[$i])) {
                			$orders[$i] = $order->descriptorToObject($orders[$i]);
                        }

                        $profileId = $orders[$i]->get('profile_id');
                        $origProfileId = $orders[$i]->get('orig_profile_id');

                        for ($j = 0; $j < count($users); $j++) {

                            $uid = $users[$j]->get('profile_id');

                            if (!$onlyNormalProfile) {

    							if ($uid == $profileId || $uid == $origProfileId) {
    								$this->orders[] = $orders[$i];
    								break;
                                }

                            } elseif ($uid == $profileId) {
    								$this->orders[] = $orders[$i];
    								break;
    						}
                        }
                    }
                }
            }

            if ($this->action == "export_xls") {

                foreach ($this->orders as $ord_idx => $order) {

                    $taxes = 0;

            		foreach ($order->getDisplayTaxes() as $tax_name => $tax) {
            			$taxes += $tax;
                    }

            		$this->orders[$ord_idx]->set('tax', $taxes);
            	}
            }
        }

        return $this->orders;
    }

    /**
     * getNoSuchUser 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNoSuchUser()
    {
        $this->getOrders();
        return $this->noSuchUser;
    }

    /**
     * getCount: how many orders were found
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCount()
    {
        return count($this->getOrders());
    }

    /**
     * getRecentOrders 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRecentOrders()
    {
        $result = array();

        if ($this->config->General->recent_orders) {
            $order = new XLite_Model_Order();
            $order->collectGarbage();
            $where = "status in ('Q','P')";
            $count = $order->count($where);
            $from = $count - $this->config->General->recent_orders;

            if ($from < 0) {
                $from = 0;
            }

            $order->_range = null;
            $result = array_reverse($order->findAll($where, "date", null, "$from, $count"));

        }

        return $result;
    }

    /**
     * doActionExportXls 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionExportXls()
    {
        $w = new XLite_View_ExportXLS();
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
     * columnCount 
     * 
     * @param mixed $order ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function columnCount($order)
    {
        return 6;
    }

    /**
     * rowCount 
     * 
     * @param mixed $order ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function rowCount($order)
    {
        return 38 + count($order->get('items'));
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
        if (isset(XLite_Core_Request::getInstance()->order_ids)) {
            foreach (XLite_Core_Request::getInstance()->order_ids as $oid => $value) {
                $order = new XLite_Model_Order($oid);
                $order->remove();
            }
        }
    }

    /**
     * getExportFormats 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getExportFormats()
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
    protected function getStartXML()
    {
        return '<?xml version="1.0"?>'."\n";;
    }
}

