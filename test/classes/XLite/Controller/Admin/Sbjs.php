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
