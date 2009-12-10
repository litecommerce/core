<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2007 Creative Development <info@creativedevelopment.biz>  |
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
* @package View
* @access public
* @version $Id: Date.php,v 1.3 2007/05/21 11:53:29 osipov Exp $
*/
class CDate extends CFormField
{
    var $params = array();
    var $lowerYear = 2000;
    var $higherYear = 2035;
    var $template = "common/date.tpl";

    function init()
    {
        $dayField = $this->get("field") . "Day";
        $monthField = $this->get("field") . "Month";
        $yearField = $this->get("field") . "Year";
        $this->params = array_merge(array($dayField, $monthField, $yearField), $this->params);

		parent::init();

        if (!is_null($this->get($dayField)) && !is_null($this->get($monthField)) && !is_null($this->get($yearField))) {
            // read form fields
            $date = mktime(0,0,0,$this->get($monthField), $this->get($dayField), $this->get($yearField));
            $this->set("component." . $this->get("field"), $date); 
        }
    }

    function getDays()
    {
        $days = array();
        for ($i=1; $i<32; $i++)
        {
            $days[] = $i;
        }
        return $days;
    }

    function getYears()
    {
        $years = array();
        $yearsRange = $this->get("yearsRange");
        if (isset($yearsRange) && intval($yearsRange) > 0) {
        	$this->set("higherYear", $this->get("lowerYear") + intval($yearsRange));
        }
        for ($i=$this->get("lowerYear"); $i<=$this->get("higherYear"); $i++)
        {
            $years[] = $i;
        }
        return $years;
    }
    
    function fillForm()
    {
        parent::fillForm();
        $value = $this->get("value");
        if (is_null($value)) {
            // current date is the default
            $value = time();
        }
        $dayField = $this->get("field") . "Day";
        $monthField = $this->get("field") . "Month";
        $yearField = $this->get("field") . "Year";
        $date = getdate($value);
        $this->set($dayField, $date["mday"]);
        $this->set($monthField, $date["mon"]);
        $this->set($yearField, $date["year"]);
    }

    function getMonth()
    {
        $monthField = $this->get("field") . "Month";
        return $this->get($monthField);
    }

    function getDay()
    {
        $field = $this->get("field") . "Day";
        return $this->get($field);
    }

    function getYear()
    {
        $field = $this->get("field") . "Year";
        return $this->get($field);
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
