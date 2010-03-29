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
* Admin_Dialog_CustomerNotifications description.
*
* @package Module_ProductAdviser
* @access public
* @version $Id$
*/
class XLite_Module_ProductAdviser_Controller_Admin_CustomerNotifications extends XLite_Controller_Admin_Abstract
{	
	public $notifications = null;	
	public $notificationsNumber = 0;

	public function __construct(array $params)
	{
		parent::__construct($params);
		$this->params[] = "sortby";
		$this->params[] = "email";
		$this->params[] = "pinfo";
		$this->params[] = "prodname";
		$this->params[] = "status";
		$this->params[] = "type";
		$this->params[] = "period";
	}

	function init()
	{
		if (!isset($_REQUEST["action"]) && !(isset($_REQUEST["email"])||isset($_REQUEST["pinfo"])||isset($_REQUEST["status"])||isset($_REQUEST["type"])||isset($_REQUEST["prodname"])||isset($_REQUEST["period"]))) {
            $config = new XLite_Model_Config();
            if ($config->find("name='filters_preferences' AND category='ProductAdviser'")) {
				// TODO: check it (is it needs unserialize(stripslashes(value))? )
				$preferences = $config->get("value");
    			if (is_array($preferences)) {
    				$this->_setParameter("email", $preferences, true);
    			 	$this->_setParameter("pinfo", $preferences, true);
    			 	$this->_setParameter("status", $preferences, true);
    			 	$this->_setParameter("type", $preferences, true);
    			 	$this->_setParameter("prodname", $preferences, true);
    			 	if ($this->_setParameter("period", $preferences, true)) {
            			$period = $preferences["period"];
                		if ($period != 6) {
                			list($startDateRaw, $endDateRaw) = $this->getDatesRaw($period);
    			 			$this->_setParameter("startDateRaw", $startDateRaw);
    			 			$this->_setParameter("endDateRaw", $endDateRaw);
                		}
                    }
    			}
            }

        }

		parent::init();

		if (!$this->config->getComplex('ProductAdviser.customer_notifications_enabled')) {
			$this->set("returnUrl", "admin.php?target=module&page=ProductAdviser");
			$this->redirect();
		}
	}

    function getAllParams($exeptions=null)
    {
    	$allParams = parent::getAllParams();
    	$params = $allParams;
    	if (isset($exeptions)) {
    		$exeptions = explode(",", $exeptions);
    		if (is_array($allParams) && is_array($exeptions)) {
    			$params = array();
    			foreach($allParams as $p => $v) {
    				if (!in_array($p, $exeptions)) {
    					$params[$p] = $v;
    				}
    			}
    		}
    	}
        return $params;
    }

    function getParameter($name, &$value, $udecode=false)
    {
		$value = $this->get($name);
        if (isset($value)) {
        	if ($udecode) {
            	$value = urldecode($value);
            }
        	$value = trim($value);
        	return (strlen($value) > 0) ? true : false;
        }
        return false;
    }

    function _setParameter($name, $value, $from_array=false)
    {
    	if ($from_array && is_array($value)) {
    	 	if (isset($value[$name])) {
    	 		$value = $value[$name];
				$_REQUEST[$name] = $value;
			} else {
				$value = null;
			}
    	} else {
    	 	if (isset($value)) {
				$_REQUEST[$name] = $value;
			}
		}
		return isset($value);
    }

    function getDatesRaw($period)
    {
		$currentTime = getdate(time());
		switch ($period) {
			case 0:		// Today
				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday'],$currentTime['year']);
				$endDateRaw = $startDateRaw;
			break;
			case 1:		// Yesterday
				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-1,$currentTime['year']);
				$endDateRaw = $startDateRaw;
			break;
			case 2:		// Current week
				$wday = ($currentTime['wday'] == 0) ? 7 : $currentTime['wday'];
				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
				$endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
			break;
			case 3:		// Previous week
				$wday = (($currentTime['wday'] == 0) ? 7 : $currentTime['wday']) + 7;
				$startDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+1,$currentTime['year']);
				$endDateRaw = mktime(0,0,0,$currentTime['mon'],$currentTime['mday']-$wday+7,$currentTime['year']);
			break;
			case 4:		// Current month
				$startDateRaw = mktime(0,0,0,$currentTime['mon'],1,$currentTime['year']);
				$endDateRaw = mktime(0,0,0,$currentTime['mon']+1,0,$currentTime['year']);
			break;
			case 5:		// Previous month
				$startDateRaw = mktime(0,0,0,$currentTime['mon']-1,1,$currentTime['year']);
				$endDateRaw = mktime(0,0,0,$currentTime['mon'],0,$currentTime['year']);
			break;
    	}

    	return array($startDateRaw, $endDateRaw);
    }

    function getNotifications()
    {
        if (is_null($this->notifications)) {
            $cntfs = new XLite_Module_ProductAdviser_Model_Notification();
            
            $condition = array();
			
            if ($this->getParameter("email", $email)) {
				$condition[] = "(email LIKE '%".addslashes($email)."%')";
            }
            if ($this->getParameter("pinfo", $pinfo)) {
				$condition[] = "(person_info LIKE '%".addslashes($pinfo)."%')";
            }
            if (!$this->getParameter("status", $status)) {
            	$this->set("status", CUSTOMER_REQUEST_QUEUED);
            }
            if ($this->getParameter("status", $status)) {
            	if ($status != "A") {
					$condition[] = "(status='$status')";
				}
            }
            if ($this->getParameter("type", $type)) {
            	if ($type != "A") {
					$condition[] = "(type='$type')";
				}
            }
            if ($this->getParameter("prodname", $prodname)) {
            	$this->xlite->set("NotificationProductNameFilter", $prodname);
            }
            if ($this->getParameter("notify_key", $notify_key, true)) {
				$condition[] = "(notify_key='$notify_key')";
            }
            if (!$this->getParameter("period", $period)) {
            	$this->set("period", "0");
                $date = getdate(time());
                $this->set("startDateRaw", mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                $this->set("endDateRaw", $this->get("startDateRaw"));
            }
            if ($this->getParameter("period", $period)) {
            	if ($period >= 0) {
                	if ($period != 6) {
						list($startDateRaw, $endDateRaw) = $this->getDatesRaw($period);
						$this->set("startDateRaw", $startDateRaw);
						$this->set("endDateRaw", $endDateRaw);
                    	$date = getdate($this->get("startDateRaw"));
    					$this->set("startDate", mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                    	$date = getdate($this->get("endDateRaw"));
    					$this->set("endDate", mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                		$this->params[] = "startDateRaw";
                		$this->params[] = "endDateRaw";
                	}
    				$condition[] = "(date>='".$this->get("startDate")."' AND date<'".($this->get("endDate")+24*3600)."')";
				}
            }

            if (isset($this->action)) {
            	if ($this->action == "prepare_notifications") {
        			$selected = implode(", ", array_keys($this->selected));
                	$condition[] = "status='".CUSTOMER_REQUEST_UPDATED."'";
        		} else {
        			$selected = 0;
        		}
                $condition[] = "notify_id IN ($selected)";
            }

            $condition = implode(" AND ", $condition);
			
			$sortby = $this->get("sortby");
          	if (!(isset($sortby) && ($sortby == "email" || $sortby == "date" || $sortby == "type" || $sortby == "status"))) {
          		$this->sortby = $sortby = "date";
          	}
            
            $this->notifications = $cntfs->findAll($condition, $sortby);
            
            if (is_array($this->notifications)) {
            	$this->notificationsNumber = count($this->notifications);

            	if (isset($this->action) && $this->action == "prepare_notifications") {
            		for($i=0; $i<$this->notificationsNumber; $i++) {
            			$mail = new XLite_Model_Mailer();
            			$mail->ntf = $this->notifications[$i];
                        $dir = "modules/ProductAdviser/notifications/".$this->notifications[$i]->get("type")."/";
                        $mail->set("subject", $mail->compile($dir.$mail->get("subjectTemplate")));
                        $mail->set("signature", $mail->compile($mail->get("signatureTemplate")));
                        $mail->set("body", $mail->compile($dir.$mail->get("bodyTemplate"), false));
                        $this->notifications[$i]->set("mail", $mail);
            		}
            	}
            } else {
            	$this->notifications = null;
            	$this->notificationsNumber = 0;
            }
        }
        return $this->notifications;
    }

    function action_decline_notifications()
    {
    	if (isset($this->selected) && is_array($this->selected)) {
    		$this->selected = array_keys($this->selected);

    		$statuses = array();
            $statuses[] = "'".CUSTOMER_REQUEST_QUEUED."'";
            $statuses[] = "'".CUSTOMER_REQUEST_UPDATED."'";
            $statuses = implode(", ", $statuses);

    		foreach($this->selected as $notify_id) {
				$notification = new XLite_Module_ProductAdviser_Model_Notification();

        		$condition = array();
                $condition[] = "status IN ($statuses)";
                $condition[] = "notify_id='$notify_id'";
                $condition = implode(" AND ", $condition);
				if ($notification->find($condition)) {
					$notification->set("status", CUSTOMER_REQUEST_DECLINED);
					$notification->update();
				}
    		}
    	}
    }

    function action_delete_notifications()
    {
    	if (isset($this->selected) && is_array($this->selected)) {
    		$this->selected = array_keys($this->selected);

    		$statuses = array();
            $statuses[] = "'".CUSTOMER_REQUEST_SENT."'";
            $statuses[] = "'".CUSTOMER_REQUEST_DECLINED."'";
            $statuses = implode(", ", $statuses);

    		foreach($this->selected as $notify_id) {
				$notification = new XLite_Module_ProductAdviser_Model_Notification();

        		$condition = array();
                $condition[] = "status IN ($statuses)";
                $condition[] = "notify_id='$notify_id'";
                $condition = implode(" AND ", $condition);
				if ($notification->find($condition)) {
					$notification->delete();
				}
    		}
    	}
    }

    function action_prepare_notifications()
    {
    	$this->set("mode", "process");
    	$this->set("valid", false);
    }

    function action_send_notifications()
    {
    	$this->set("silent", true);

    	if (isset($this->ids) && is_array($this->ids)) {
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
    		foreach($this->ids as $ntf_id) {
				$notification = new XLite_Module_ProductAdviser_Model_Notification();

        		$condition = array();
                $condition[] = "status='".CUSTOMER_REQUEST_UPDATED."'";
                $condition[] = "notify_id='$ntf_id'";
                $condition = implode(" AND ", $condition);
				if ($notification->find($condition)) {
					$notification->set("status", CUSTOMER_REQUEST_SENT);
					$notification->update();

        			$mail = new XLite_Model_Mailer();
                    $mail->set("subject", $this->subjects[$ntf_id]);
                    $mail->set("body", $this->bodies[$ntf_id]);
                    $mail->set("ignoreDefaultSubjectBody", true);
					$dir = "modules/ProductAdviser/notifications/".$notification->get("type")."/";
                    $mail->compose(
                            $this->config->getComplex('Company.site_administrator'),
                            $notification->get("email"),
                            $dir);
                    echo "Sending notification to <b>".$notification->get("email")."</b>...";
                    $mail->send();
                    echo "&nbsp;<font color=green>[OK]</font><br>"; flush();
				}
			}

			$url = array();
			foreach($this->getAllParams("action") as $param => $value) {
				$url[] = "$param=" . urlencode($value);
			}
            $url = implode("&", $url);
            $this->set("returnUrl", "admin.php?$url");
?>
<HR>Redirecting <a href="<?php echo $this->get("returnUrl"); ?>"><u>...</u></a>
<SCRIPT language="javascript">
	loaded = true;
	window.location="<?php echo $this->get("returnUrl"); ?>";
</SCRIPT>
<?php
    	}
    }

    function action_save_filters()
    {
		$preferences = array();
        if ($this->getParameter("email", $email)) {
			$preferences["email"] = $email;
        }
        if ($this->getParameter("pinfo", $pinfo)) {
			$preferences["pinfo"] = $pinfo;
        }
        if ($this->getParameter("status", $status)) {
			$preferences["status"] = $status;
		} else {
			$preferences["status"] = "A";
		}
        if ($this->getParameter("type", $type)) {
			$preferences["type"] = $type;
		} else {
			$preferences["type"] = "A";
		}
        if ($this->getParameter("prodname", $prodname)) {
			$preferences["prodname"] = $prodname;
        }
        if ($this->getParameter("period", $period)) {
			$preferences["period"] = $period;
        }

        $config = new XLite_Model_Config();
        $update_config = true;
        if (!$config->find("name='filters_preferences' AND category='ProductAdviser'")) {
        	$update_config = false;
            $config->set("name", "filters_preferences");
            $config->set("category", "ProductAdviser");
            $config->set("type", "serialized");
        }

		$config->set("value", addslashes(serialize($preferences)));

        if ($update_config) {
            $config->update();
        } else {
            $config->create();
        }
    }
}

?>
