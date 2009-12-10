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

set_time_limit(0); // sending messages is a long-time operation...

/* vim: set expandtab tabstop=4 softtabstop=4 foldmethod=marker shiftwidth=4: */

/**
* @package Module_Newsletters
* @access public
* @version $Id: news_messages.php,v 1.10 2008/10/23 11:57:27 sheriff Exp $
*/
class Admin_Dialog_news_messages extends Admin_Dialog
{
    var $params = array("target", "list_id", "news_id");

    function init()
    {
    	parent::init();

    	$this->request = $_REQUEST;
    	if ($this->mode != "continue") {
        	$this->session->set("sendedMails", null);
    		$this->session->set("sendMailsInfo", null);
			$this->session->set("processedNewsLetter", null);
    	} else {
    		if ($this->session->isRegistered("sendedMails") && $this->session->isRegistered("sendMailsInfo")) {
    			$this->request = $this->session->get("sendMailsInfo");
        		$this->mapRequest($this->request);
        		$action = "action_" . $this->action;
            	if (method_exists($this, $action)) {
            		$this->set("silent", true);
            		$this->$action();
            	}
            }
    	}
    }

    function &getList()
    {
        if (is_null($this->list)) {
            $this->list =& func_new("NewsList", $this->get("list_id"));
        }
        return $this->list;
    }

    function &getMessages()
    {
        if (is_null($this->messages)) {
            $ns =& func_new("NewsLetter");
            $this->messages = $ns->findAll("list_id=".$this->get("list_id"), "send_date DESC");

        }
        return $this->messages;
    }

	function &getMessage()
	{
		if (is_null($this->message)) {
			$this->message =& func_new("NewsLetter", $this->get("news_id"));
		}

		return $this->message;
	}

    function init_multisend()
    {
    	if ($this->session->isRegistered("sendedMails")) {
    		$this->xlite->sendedMails = $this->session->get("sendedMails");
    	} else {
    		$this->xlite->sendedMails = -1;
        	$this->session->set("sendMailsInfo", $_REQUEST);
    	}

    	if (intval($this->get("config.Newsletters.subscribers_per_page_mail")) > 0) {
			if (intval($this->get("config.Newsletters.subscribers_per_page_mail")) != $this->get("config.Newsletters.subscribers_per_page_mail")) {
				$this->config->set("Newsletters.subscribers_per_page_mail", intval($this->get("config.Newsletters.subscribers_per_page_mail")));
			}
?>
<SCRIPT language="javascript">
	loaded = false;

	function refresh() {
		window.scroll(0, 100000);

		if (loaded == false)
			setTimeout('refresh()', 1000);
	}

	setTimeout('refresh()', 1000);
</SCRIPT>
<?php
    	} else {
			$this->config->set("Newsletters.subscribers_per_page_mail", 0);
    	}
    	$this->subscribers_per_page_mail = $this->get("config.Newsletters.subscribers_per_page_mail");
    }

    function action_send_message()
    {
    	if (!isset($this->postonly)) {
        	$this->init_multisend();

            $nl =& func_new("NewsLetter");
            if ($this->subscribers_per_page_mail > 0) {
            	$nl->set("parentCaller", $this);
        		if ($this->mode == "continue") {
        			$nl->set("testMode", true);
        		}
            }
            $nl->set("properties", $this->request); // list_id, subject, body
        } else {
            $nl =& func_new("NewsLetter");
            $nl->set("properties", $this->request); // list_id, subject, body
            $nl->set("subscribers", $this->get("subscribers"));
            $nl->set("testMode", true);
            $nl->set("postonlyMode", true);
            $this->set("valid", false); // leave in POST
        }

		$obj =& func_new("NewsLetter");
		if ($obj->find("news_id='".$this->news_id."'")) {
			$nl->update();
			$nl->set("postonlyMode", false);
			$nl->resend();
		} else {
	        $nl->send();
		}
        $this->set("returnUrl", "admin.php?target=news_messages&list_id=" . $this->list_id);
        $this->jsRedirect();
        exit;
    }

    function action_messages()
    {
    	$ids = (array)$this->get("ids");
    	if (count($ids) == 0) {
    		return;
    	}

		if (!$this->get("delete")) {
    		$this->init_multisend();
    	}

    	if ($this->session->isRegistered("processedNewsLetter")) {
    		$this->processedNewsLetter = $this->session->get("processedNewsLetter");
    	} else {
    		$this->processedNewsLetter = array();
    	}

        foreach ($ids as $id) {
            $nl =& func_new("NewsLetter", $id);
            if ($this->get("delete")) {
                $nl->delete();
            } elseif ($this->get("resend")) {
            	if (!isset($this->processedNewsLetter[$id])) {
                    if ($this->subscribers_per_page_mail > 0) {
                    	$nl->set("parentCaller", $this);
                    }
                    $nl->resend();
                	$this->processedNewsLetter[$id] = true;
                    if ($this->subscribers_per_page_mail > 0) {
    					$this->session->set("processedNewsLetter", $this->processedNewsLetter);
                    	$this->session->set("sendedMails", -1);
                		$this->session->writeClose();

                        if (count($this->processedNewsLetter) == count($ids)) {
                        	$this->set("returnUrl", "admin.php?target=news_messages&list_id=" . $this->list_id);
                        } else {
                        	$this->set("returnUrl", "admin.php?target=news_messages&list_id=" . $this->list_id . "&action=" . $this->action . "&mode=continue&time=".time());
                        }
            			$this->jsRedirect();
            			exit;
                    }
                }
            }
        }
        $this->processedNewsLetter = null;
    }

	function mail_send_callback($sender_idx=null, $email=null)
	{
		if (!isset($sender_idx)) {
			if (($this->action == "messages" || $this->action == "send_message") && !is_null($this->processedNewsLetter)) {
    			$this->session->set("processedNewsLetter", $this->processedNewsLetter);
    			return 0;
			}
        	$this->session->set("sendedMails", null);
        	$this->session->set("sendMailsInfo", null);
    		$this->session->writeClose();

            $this->set("returnUrl", "admin.php?target=news_messages&list_id=" . $this->list_id);
            $this->jsRedirect();
			return 2;
		}

		if ($sender_idx <= $this->xlite->sendedMails) {
			return 0;	// skipping this step
		}

		echo "[<i>".($sender_idx+1)."</i>] Sending e-mail to: <b>$email</b>...<br>"; flush();

        // check for sending limit
        if (($sender_idx+1) % ($this->get("config.Newsletters.subscribers_per_page_mail")) == 0) {
        	$this->session->set("sendedMails", $sender_idx);
    		$this->session->writeClose();

            $this->set("returnUrl", "admin.php?target=news_messages&list_id=" . $this->list_id . "&action=" . $this->action . "&mode=continue&time=".time());
            $this->jsRedirect();
            return 2;
        }

		return 1;	// sending with success
	}

	function jsRedirect()
	{
?>
<HR>Redirecting <a href="<?php echo $this->get("returnUrl"); ?>"><u>...</u></a>
<SCRIPT language="javascript">
	loaded = true;
	setTimeout('window.location="<?php echo $this->get("returnUrl"); ?>";', 1000);
</SCRIPT>
<?php
	}
}
// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
