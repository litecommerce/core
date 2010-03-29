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
* Class description.
*
* @package $Package$
* @version $Id$
*/
class XLite_Controller_Admin_Sbjs extends XLite_Controller_Admin_Abstract
{
    function init()
    {
        parent::init();

        switch ($this->mode)
        {
        	case "get_sidebar_box_status":
        		$this->getSidebarBoxStatus();
        	break;

        	case "change_sidebar_box_status":
        		$this->changeSidebarBoxStatus();
        	break;
        }
    }

    function getSidebarBoxStatus($boxHead = null)
    {
		$this->getSidebarBoxStatuses();

		if ($this->isValidSidebarBoxId())
		{
		 	if (!isset($this->sidebar_box_statuses[$this->sidebar_box_id]))
		 	{
		 		$this->sidebar_box_statuses[$this->sidebar_box_id] = true;
				$this->setSidebarBoxStatuses();
		 	}

			echo "if (!sidebar_box_statuses) { var sidebar_box_statuses = new Array(); }\nsidebar_box_statuses[\"".$this->sidebar_box_id."\"]=".intval($this->sidebar_box_statuses[$this->sidebar_box_id]).";\n\n";
		}
    }

    function changeSidebarBoxStatus()
    {
		$this->getSidebarBoxStatuses();

		if ($this->isValidSidebarBoxId())
		{
		 	if (isset($this->sidebar_box_statuses[$this->sidebar_box_id]))
		 	{
		 		$this->sidebar_box_statuses[$this->sidebar_box_id] = ($this->sidebar_box_statuses[$this->sidebar_box_id]) ? false : true;
		 	}
		 	else
		 	{
		 		$this->sidebar_box_statuses[$this->sidebar_box_id] = true;
		 	}


			$this->setSidebarBoxStatuses();

			echo "if (!sidebar_box_statuses) { var sidebar_box_statuses = new Array(); }\nsidebar_box_statuses[\"".$this->sidebar_box_id."\"]=".intval($this->sidebar_box_statuses[$this->sidebar_box_id]).";";
		}
    }

    function isValidSidebarBoxId()
    {
		if (isset($this->sidebar_box_id))
		{
			$this->sidebar_box_id = strval($this->sidebar_box_id);
    		if (strlen($this->sidebar_box_id) > 0)
    		{
    			return true;
    		}
    	}

    	return false;
    }

    function getSidebarBoxStatuses()
    {
		if (!$this->session->isRegistered("sidebar_box_statuses"))
		{
			$this->sidebar_box_statuses = array();
        	if ($this->auth->is("logged")) 
        	{
        		$this->sidebar_box_statuses = unserialize($this->auth->getComplex('profile.sidebar_boxes'));
        	}
		}
		else
		{
			$this->sidebar_box_statuses = $this->session->get("sidebar_box_statuses");
		}
    }

    function setSidebarBoxStatuses()
    {
        $this->session->set("sidebar_box_statuses", $this->sidebar_box_statuses);
        $this->session->writeClose();
		
		if ($this->auth->is("logged"))
		{
			$profile = $this->auth->get("profile");
            $profile->set("sidebar_boxes", serialize($this->sidebar_box_statuses));
            $profile->update();
		}
    }
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
