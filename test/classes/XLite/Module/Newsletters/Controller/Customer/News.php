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
* @package Module_Newsletters
* @access public
* @version $Id$
*/
class XLite_Module_Newsletters_Controller_Customer_News extends XLite_Controller_Abstract
{	
    public $params = array("target", "mode", "news_id");


	/**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */
    protected function getLocation()
    {
        $location = parent::getLocation();

        switch ($this->get('mode')) {
            case 'view':
                $location = 'News message';
                break;
            case 'view_all':
                $location = 'News and announcements';
                break;
            case 'confirm_message':
                $location = 'Subscription request received';
                break;
            case 'subscribe_confirmed':
				$location = 'Subscription confirmed';
				break;
            case 'subscription_failed':
                $location = 'Subscription failed';
                break;
        }

		if (!isset($location) && 'subscribe' === XLite_Core_Request::getInstance()->action) {
			$location = 'Newsletter subscription';
		}

        return $location;
    }



    function init()
    {
    	parent::init();

    	if (isset($this->mode) && isset($this->news_id)) {
    		$this->getNews();
    		if (isset($this->newsError) && $this->newsError) {
    			$this->redirect("cart.php?target=main");
    		}
    	}
    }

    function getNews()
    {
        if (is_null($this->news)) {
            $this->news = new XLite_Module_Newsletters_Model_NewsLetter();
            $news_id = intval($this->get("news_id"));
            $this->newsError = false;
            $found = $this->news->find("news_id='$news_id'");
            if (!$found) {
            	$this->newsError = true;
            }
        }
        return $this->news;
    }
    
    function getAllNews()
    {
        if (is_null($this->allNews)) {
            $nl = new XLite_Module_Newsletters_Model_NewsLetter();
            $this->allNews = $nl->findAll();
        }
        return $this->allNews;
    }
    
    function action_confirm()
    {
		$method = $this->get("type");
		$nl = new XLite_Module_Newsletters_Model_NewsList();
		if ($method != "unsubscribe" && $nl->get("showedListsNumber") == 0) {
			if (isset($this->params)) {
				$this->params = array();
			}
			$this->redirect("cart.php");
			return;
		}
        require_once LC_MODULES_DIR . 'Newsletters' . LC_DS . 'encoded.php';
        // confirm subscription
        $confirmed = func_newsletters_check_code($this->get("email"), $this->get("code"));

		$mode = substr($method, 0 , (strlen($method)-2)) . "ption";
        if ($confirmed) {
            // subscribe/unsubscribe 
            $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
            $nl = new XLite_Module_Newsletters_Model_NewsList();
            switch($method) {
            	case "subscribe":
            		$nlList = $nl->findAll("show_as_news=1");
            	break;
            	case "unsubscribe":
            		$adminZone = $this->xlite->is("adminZone");
            		$this->xlite->set("adminZone", true);
            		$nlList = $nl->findAll();
            		$this->xlite->set("adminZone", $adminZone);
            	break;
            	default:
            		$method = null;
            		$this->set("mode", $mode . "_failed");
            	return;
            }
            if (isset($method)) {
                foreach ($nlList as $l) {
                    $ns->$method($this->get("email"), $l->get("list_id"));
                }                
                // redirect to subscription message
                // subscribe_confirmed or unsubscribe_confirmed
                $this->set("mode", $method . "_confirmed");
            }
        } else {
			// redirect to status message
			// subscription_failed or unsubscription_failed
            $this->set("mode", $mode . "_failed");
        }
    }

    function action_subscribe()
    {
		$nl = new XLite_Module_Newsletters_Model_NewsList();
		if ($nl->get("showedListsNumber") == 0) {
			if (isset($this->params)) {
				$this->params = array();
			}
			$this->redirect("cart.php");
			return;
		}

        // validate email
        $val = new XLite_Validator_EmailValidator();
        $val->set("field", "email");
        $this->set("valid", $val->is("valid"));
        if (!$this->is("valid")) {
            return;
        }
        // send subscribe confirmation
        require_once LC_MODULES_DIR . 'Newsletters' . LC_DS . 'encoded.php';

        $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
        $ns->request($this->get("email"));
        // show confirmation notification
        $this->set("mode", "confirm_message");
    }
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
