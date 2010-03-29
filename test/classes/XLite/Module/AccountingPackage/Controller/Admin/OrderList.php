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
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_AccountingPackage
* @access public
* @version $Id$
*/
class XLite_Module_AccountingPackage_Controller_Admin_OrderList extends XLite_Controller_Admin_OrderList implements XLite_Base_IDecorator
{	
    public $delimiter  = "\t";	
    public $crlf       = "\r\n";	
    public $dateParams = array(
            'startDateMonth',
            'startDateDay',
            'startDateYear',
            'endDateMonth',
            'endDateDay',
            'endDateYear'
            );

    public function __construct(array $params) // {{{
    {
        parent::__construct($params);
        $this->params = array_merge($this->params, $this->dateParams);
    } // }}}

    function initView() // {{{
    {
        parent::initView();
        if ($this->get("mode") == "export_myob" || $this->get("mode") == "export_pt") {
            $this->setComplex("searchOrdersForm.visible", false);
        }
    } // }}}
    
    function updateConfig() // {{{
    {
        foreach (func_get_args() as $name) {
            if (isset($this->$name)) {
                $value = $this->$name;
                $config = new XLite_Model_Config();
                if ($config->find("category='ImportExport' AND name='$name'")) {
                    $config->set("value", $value);
                    $config->update();
                } else {
                    $config->set("name", $name);
                    $config->set("category", "ImportExport");
                    $config->set("value", $value);
                    $config->create();
                }
            }
        }
    } // }}}
    
    function isQuickSearch()
    {
    	if ($this->action == "export_qb" || $this->action == "export_myob" || $this->action == "export_pt") {
    		return false;
    	}

    	return parent::isQuickSearch();
    }

    function action_export_qb() // {{{
    {
        require_once LC_MODULES_DIR . 'AccountingPackage' . LC_DS . 'encoded.php';
        AccountingPackage_export_qb($this);
    } // }}}
    
    function action_export_myob() // {{{
    {
        if (is_null($this->get("export_result"))) {
            // redirect to export dialog
            $this->set("mode", "export_myob");
        } else {
            // export data
            $this->updateConfig("income_account", "deposit_account");
            $this->export("myob");
        }
    } // }}}

    function action_export_pt() // {{{
    {
        if (is_null($this->get("export_result"))) {
            // redirect to export dialog
            $this->set("mode", "export_pt");
        } else {
            // export data
            $this->updateConfig("receivable_account", "sales_account", "cash_account");
            $this->export("pt");
        }
    } // }}}
    
    function addDistribution($order, $itemType = "item") // {{{
    {
        $orderID = $order->get("order_id");
        if (isset($this->distributions[$orderID])) {
            $this->distributions[$orderID]++;
        } else {
            $this->distributions[$orderID] = 1;
        }
    } // }}}

    function getTotalDistribution($order) // {{{
    {
        $orderID = $order->get("order_id");
        return isset($this->distributions[$orderID]) ? $this->distributions[$orderID] : 0;
    } // }}}
    
    function getDateDue($date, $format = null) // {{{
    {
        if (is_null($format)) {
            $format = $this->getComplex('config.General.date_format');
        }
        return strftime($format, $date);
    } // }}}

    function getCurrentDistribution($order) // {{{
    {
        static $lines;

        if (!isset($lines)) $lines = array();

        $orderID = $order->get("order_id");
        if (isset($lines[$orderID])) {
            $lines[$orderID]++;
        } else {
            $lines[$orderID] = 1;
        }
        return $lines[$orderID];
    } // }}}
    
    function export($format) // {{{
    {
		$price_format = $this->config->getComplex('General.price_format');
        $this->config->setComplex("General.price_format", "%s");
        require_once LC_MODULES_DIR . 'AccountingPackage' . LC_DS . 'encoded.php';
        AccountingPackage_export($this, $format);
		$this->config->setComplex("General.price_format", $price_format);
    } // }}}

    function found($order, $name) // {{{
    {
        return !is_null($order->get($name)) && $order->get($name) > 0;
    } // }}}

    function getExportFormats() // {{{
    {
        $formats = parent::getExportFormats();
        $formats["export_qb"] = "QuickBooks 2003";
        $formats["export_myob"] = "MYOB Accounting 2005 (v14)";
        $formats["export_pt"] = "Peachtree Complete Accounting 2004";
        return $formats;
    } // }}}

    function CSVQuoting($string)
    {
    	$string = str_replace("\"", "\"\"", $string);
    	return $string;
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
