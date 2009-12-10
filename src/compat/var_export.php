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
*
* This file solves compatibility issues.
*
* $Id: var_export.php,v 1.3 2008/10/22 12:12:14 sheriff Exp $
*
*/

/*
* Defines custom var_export function.
* Thanx to khowe at perfnet dot ca 
*/

function var_export_rec ($a, $return, $ext_tab_sep, $inside_array)
{
    if ($inside_array != false)
    {
        $tab_sep = '';
        $sep = ",\n";
    }
    else
    {
        $tab_sep = $ext_tab_sep;
        $sep = "\n";
    }
    $result = '';
    switch ( gettype($a) )
    {
        case 'array':
            reset($a);
            $result = $tab_sep."array(\n";
            while (list($k, $v) = each($a)) {
                $result .= $ext_tab_sep."\t"."'".str_replace("'", "\'", $k)."'".' => '.var_export_rec($v,$return,$ext_tab_sep."\t",true);
            }
            $result .= $ext_tab_sep.')'.$sep;
            break;
        case 'string':
            $result = $tab_sep."'".str_replace("'", "\'", $a)."'".$sep;
            break;
        case 'boolean':
            $result = ($a) ? $tab_sep.'true'.$sep : $tab_sep.'false'.$sep;
            break;
        default:
            $result = $tab_sep.$a.$sep;
            break;
    }
    if ($return != false) {
        return $result;
    } else {
        echo $result;
    }
}

function var_export($a, $r=false) {
    return var_export_rec($a, $r, '', false);
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
