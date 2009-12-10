<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package Dialog
* @access public
* @version $Id: order_list.php,v 1.43 2008/10/23 11:45:20 sheriff Exp $
*/
class Admin_Dialog_order_list extends Admin_Dialog
{
    var $params = array('target', 'mode', 'order_id1', 'order_id2', 'login', 'status');
    var $noSuchUser = false;

    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set("startDate", mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }
 
    function isQuickSearch()
    {
    	if ($this->action == "export_xls") {
    		return false;
    	}

    	return true;
    }

    function &getOrders()
    {
		$this->origProfile = true;
		$this->enhacedSearch = false;

        if (is_null($this->orders)) {
            $order =& func_new("Order");
            $order->collectGarbage();
			$order->fetchKeysOnly = true;
			$order->fetchObjIdxOnly = $this->is("quickSearch");
            if ($this->get("login")) {
                $profile =& func_new("Profile");
				$profile->_range = null;
                if (!$profile->find("login='" . addslashes($this->get("login")) . "' AND order_id<>'0'")) {
                    $this->noSuchUser = true;
                	if ($profile->find("login='" . addslashes($this->get("login")) . "' AND order_id<>'0'")) {
                    	$this->noSuchUser = false;
                    	$this->origProfile = false;
                	} else {
                        $where = "login LIKE '%" . addslashes($this->get("login")) . "%'";
                        $users = $profile->findAll($where);
                        if (is_array($users) && count($users) > 0) {
							$this->noSuchUser = false;
                        	$this->enhacedSearch = true;
                        }
                	}
                }
            } else {
                $profile = null;
            }

            if (!$this->enhacedSearch) {
            	if (($this->get("login") && $profile->get("profile_id")) || !$this->get("login")) {
                    $this->orders =& $order->search(
                            $profile,
                            $this->get("order_id1"),
                            $this->get("order_id2"),
                            $this->get("status"),
                            $this->get("startDate"),
                            $this->get("endDate")+24*3600,
                            $this->origProfile);
            	}
            	if (count($this->orders) == 0 && is_object($profile)) {
                    $where = "login='" . addslashes($this->get("login")) . "'";
                    $users = $profile->findAll($where);
                    $only_normal_profile = true;
            	}
            } 
            if ($this->enhacedSearch || (!$this->enhacedSearch && count($this->orders) == 0)) {
            	$orders =& $order->search(
                        null,
                        $this->get("order_id1"),
                        $this->get("order_id2"),
                        $this->get("status"),
                        $this->get("startDate"),
                        $this->get("endDate")+24*3600); 
                $this->orders = array();
				if (is_array($orders) && count($orders) > 0) {
					for($i=0; $i<count($orders); $i++) {
                		if ($order->isObjectDescriptor($orders[$i])) {
                			$orders[$i] = $order->descriptorToObject($orders[$i]);
                		}
						$profile_id = $orders[$i]->get("profile_id");
						$orig_profile_id = $orders[$i]->get("orig_profile_id");
						for($j=0; $j<count($users); $j++) {
							$uid = $users[$j]->get("profile_id");
							if (!$only_normal_profile) {
    							if ($uid == $profile_id || $uid == $orig_profile_id) {
    								$this->orders[] = $orders[$i];
    								break;
    							}
    						} else {
    							if ($uid == $profile_id) {
    								$this->orders[] = $orders[$i];
    								break;
    							}
    						}
						}
					}
				}
            }

            if ($this->action == "export_xls") {
            	foreach($this->orders as $ord_idx => $order) {
            		$taxes = 0;
            		foreach($order->getDisplayTaxes() as $tax_name => $tax) {
            			$taxes += $tax;
            		}
            		$this->orders[$ord_idx]->set("tax", $taxes);
            	}
            }
        }
        return $this->orders;
    }

    function getCount() {
        // how many orders were found
        return count($this->get("orders"));
    }

    function getRecentOrders()
    {
        if ($this->config->get("General.recent_orders")) {
            $order = func_new("Order");
            $order->collectGarbage();
            $where = "status in ('Q','P')";
            $count = $order->count($where);
            $from = $count - $this->config->get("General.recent_orders");
            if ($from < 0) {
                $from = 0;
            }
            $order->_range = null;
            return array_reverse($order->findAll($where, "date", null, "$from, $count"));
        } else {
            return array();
        }
    }

	function action_export_xls()
	{
        $w = func_new("Widget");
        $w->component =& $this;
        $w->set("template", "order/export_xls.tpl");
        $this->startDownload("orders.xls");
        $this->ColumnCount = 9;
        $this->RowCount = $this->get("count") + 2;
        $this->endRow = $this->get("count") + 1;
        $profile =& $this->auth->get("profile");
        $time = time();
        $this->create_date = strftime("%Y-%m-%d", $time);
        $this->create_time = strftime("%H:%M:%S", $time);
        $this->author = $profile->get("billing_firstname") . " " . $profile->get("billing_lastname");
        $w->init();
        $w->display();

        // do not output anything
        $this->set("silent", true);
	}

    function columnCount($order)
    {
        return 6;
    }

    function rowCount($order)
    {
        return 38 + count($order->get("items"));
    }

	function action_delete()
	{
		if (isset($_POST["order_ids"])) {
			foreach ($_POST["order_ids"] as $oid => $value) {
				$order = func_new("Order",$oid);
				$order->remove();
			}
		}
	}

    function getExportFormats()
    {
        return array("export_xls" => "MS Excel XP/XML");
    }

    function getStartXML()
    {
        return '<?xml version="1.0"?>'."\n";;
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
