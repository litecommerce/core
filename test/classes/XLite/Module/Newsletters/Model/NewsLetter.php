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


/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
* Newletter description.
*
* @package Module_Newsletters
* @access public
* @version $Id$
*/
class XLite_Module_Newsletters_Model_NewsLetter extends XLite_Model_Abstract
{
    var $fields = array(
            "news_id"  => 0,
            "subject" => "",
            "body" => "",
            "send_date"  => 0,
            "list_id" => 0,
            );

    var $autoIncrement = "news_id";
    var $alias = "newsletters";
    var $defaultOrder= "send_date DESC";

    var $from    = "";
    var $subject = "";
    var $body    = "";
    
    var $dir = "modules/Newsletters/newsletter";
    var $customHeaders = array();

    var $testMode = false;

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->defaultOrder = $this->config->get("Newsletters.news_order") == "D" ? "send_date DESC" : "send_date ASC";
    }

    function compose($from, $subscribers, $subject, $body, $dir = "")
    {
        $this->set("testMode", true); // do not save to DB
        $this->set("subject",   $subject);
        $this->set("body",      $body);
        $this->set("subscribers", $subscribers);
        if (!empty($dir)) {
            $this->set("dir", $dir);
        }    
    }

    function resend()
    {
        $this->set("testMode", true);
        $this->send();
    }
    
    function send()
    {   
        if (!$this->is("testMode") || ($this->is("testMode") && $this->is("postonlyMode"))) {
            $this->create();
            $this->set("send_date", time());
            $this->update();
        }
		$parentCaller = null;
        if (is_object($this->get("parentCaller"))) {
        	$parentCaller = $this->get("parentCaller");
        	$parentCallerAction = "mail_send_callback";
        	if (!method_exists($parentCaller, $parentCallerAction)) {
        		$parentCaller = null;
        	}
        }
        // loop through subscribers
       	foreach ($this->get("subscribers") as $idx => $subscriber) {
            if (is_object($parentCaller)) {
				$parentCallerStatus = $parentCaller->$parentCallerAction($idx, $subscriber);
				if ($parentCallerStatus == 0) {
					continue;
				}
			}
																			
            // create Mailer instance
            $mailer = new XLite_Model_Mailer();
            $mailer->set("newsSubject", $this->get("subject"));
            $mailer->set("newsBody", $this->get("body"));
			if ($this->get("list_id")) {
                require_once LC_MODULES_DIR . 'Newsletters' . LC_DS . 'encoded.php';
                $mailer->set("list", $this->get("newsList"));
                $mailer->set("code", func_newsletters_gen_code(strtolower($subscriber)));
				$mailer->set("email", $subscriber);
            }
			$mailer->compose($this->get("config.Company.site_administrator"),
                         $subscriber,
                         $this->get("dir"));
 			$mailer->send();                         
            if (!is_object($parentCaller)) {
				echo "Sending e-mail to: <b>$subscriber</b>...<br>"; flush();
			}
			if (is_object($parentCaller) && $parentCallerStatus == 2) {
				exit;
			}
        }            

    	if (is_object($parentCaller)) {
        	$parentCaller->$parentCallerAction();
    	}
    }

    function setSubscribers($subscribers = array())
    {
        $this->subscribers = array();
        foreach ($subscribers as $subscriber) {
            if (!empty($subscriber)) {
                $this->subscribers[] = $subscriber;
            }    
        }
    }

    function getSubscribers()
    {
        if (is_null($this->subscribers)) {
            $this->subscribers = array();
            $ns = new XLite_Module_Newsletters_Model_NewsSubscriber();
            foreach ($ns->findAll("list_id=".$this->get("list_id")) as $subscriber) {
                $this->subscribers[] = $subscriber->get("email");
            }
        }
        return $this->subscribers;
    }

    function filter()
    {
        if (!$this->xlite->is("adminZone")) {
            return $this->get("newsList.show_as_news");
        }
        return parent::filter();
    }

    function getNewsList()
    {
        if (is_null($this->newsList)) {
            $this->newsList = new XLite_Module_Newsletters_Model_NewsList($this->get("list_id"));
        }
        return $this->newsList;
    }
} 

// WARNING:
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
