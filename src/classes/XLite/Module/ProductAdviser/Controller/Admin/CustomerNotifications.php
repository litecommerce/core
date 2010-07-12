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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Customer notifications page controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class CustomerNotifications extends AAdmin
{
    /**
     * notifications 
     * 
     * @var    mixed
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $notifications = null;

    /**
     * notificationsNumber 
     * 
     * @var    float
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $notificationsNumber = 0;

    /**
     * __construct 
     * 
     * @param array $params ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
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

    /**
     * init 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function init()
    {
        if ( !isset(\XLite\Core\Request::getInstance()->action)
            && !( isset(\XLite\Core\Request::getInstance()->email)
            || isset(\XLite\Core\Request::getInstance()->pinfo)
            || isset(\XLite\Core\Request::getInstance()->status)
            || isset(\XLite\Core\Request::getInstance()->type)
            || isset(\XLite\Core\Request::getInstance()->prodname)
            || isset(\XLite\Core\Request::getInstance()->period))) {

            $preferences = $this->config->ProductAdviser->filters_preferences;

   			if (is_array($preferences)) {
   				$this->_setParameter('email', $preferences, true);
   			 	$this->_setParameter('pinfo', $preferences, true);
   			 	$this->_setParameter('status', $preferences, true);
   			 	$this->_setParameter('type', $preferences, true);
                $this->_setParameter('prodname', $preferences, true);

                if (intval($this->_setParameter('period', $preferences, true)) != 0) {

                    $period = $preferences['period'];

                    if (6 == $period) {
                        
                        $this->_setParameter('startDateDay', $preferences, true);
                        $this->_setParameter('startDateMonth', $preferences, true);
                        $this->_setParameter('startDateYear', $preferences, true);
                        $this->_setParameter('endDateDay', $preferences, true);
                        $this->_setParameter('endDateMonth', $preferences, true);
                        $this->_setParameter('endDateYear', $preferences, true);

                        $this->_setParameter('startDateRaw', mktime(0, 0, 0, $preferences['startDateMonth'], $preferences['startDateDay'], $preferences['startDateYear']));
                        $this->_setParameter('endDateRaw', mktime(0, 0, 0, $preferences['endDateMonth'], $preferences['endDateDay'], $preferences['endDateYear']));

                    } else {
                		list($startDateRaw, $endDateRaw) = $this->getDatesRaw($period);
    			 		$this->_setParameter('startDateRaw', $startDateRaw);
    			 		$this->_setParameter('endDateRaw', $endDateRaw);
                	}
                }
            }
        }

        parent::init();

        if (!$this->config->ProductAdviser->customer_notifications_enabled) {
            $this->set('returnUrl', $this->buildUrl('module', '', array('page' => 'ProductAdviser')));
            $this->redirect();
        }

    }

    /**
     * getAllParams 
     * 
     * @param mixed $exeptions ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllParams($exeptions = null)
    {
    	$allParams = parent::getAllParams();
        $params = $allParams;

        if (isset($exeptions) && is_array($allParams)) {

            $exeptions = explode(",", $exeptions);

            if (is_array($exeptions)) {

                $params = array();

                foreach ($allParams as $p => $v) {
    				if (!in_array($p, $exeptions)) {
    					$params[$p] = $v;
    				}
    			}
    		}
        }

        return $params;
    }

    /**
     * getFilterParam 
     * 
     * @param mixed $name ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFilterParam($name)
    {
        $value = null;

        if (isset(\XLite\Core\Request::getInstance()->$name)) {
            $value = \XLite\Core\Request::getInstance()->$name;
        }

        return $value;
    }

    /**
     * getParameter 
     * 
     * @param mixed $name    ____param_comment____
     * @param mixed $value   ____param_comment____
     * @param mixed $udecode ____param_comment____
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getParameter($name, &$value, $udecode=false)
    {
        $value = \XLite\Core\Request::getInstance()->$name;

        if (isset($value)) {

        	if ($udecode) {
            	$value = urldecode($value);
            }

        	$value = trim($value);
        	return (strlen($value) > 0) ? true : false;
        }

        return false;
    }

    /**
     * _setParameter 
     * 
     * @param mixed $name       ____param_comment____
     * @param mixed $value      ____param_comment____
     * @param mixed $from_array ____param_comment____
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function _setParameter($name, $value, $from_array=false)
    {
        if ($from_array && is_array($value)) {

    	 	if (isset($value[$name])) {
                $value = $value[$name];
                \XLite\Core\Request::getInstance()->$name = $value;

            } else {
                $value = null;
            }

        } else {

            if (isset($value)) {
                \XLite\Core\Request::getInstance()->$name = $value;
            }

        }

        return isset($value);
    }

    /**
     * getDatesRaw 
     * 
     * @param mixed $period ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDatesRaw($period)
    {
        $currentTime = getdate(time());

        switch ($period) {

            case 1:		// Yesterday
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'] - 1, $currentTime['year']);
                $endDateRaw = $startDateRaw;
            break;

            case 2:		// Current week
                $wday = ($currentTime['wday'] == 0) ? 7 : $currentTime['wday'];
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'] - $wday + 1, $currentTime['year']);
                $endDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'] - $wday + 7, $currentTime['year']);
            break;

            case 3:		// Previous week
                $wday = (($currentTime['wday'] == 0) ? 7 : $currentTime['wday']) + 7;
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'] - $wday + 1, $currentTime['year']);
                $endDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'] - $wday + 7, $currentTime['year']);
            break;

            case 4:		// Current month
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'], 1, $currentTime['year']);
                $endDateRaw = mktime(0, 0, 0, $currentTime['mon'] + 1, 0, $currentTime['year']);
            break;

            case 5:		// Previous month
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'] - 1, 1, $currentTime['year']);
                $endDateRaw = mktime(0, 0, 0, $currentTime['mon'], 0, $currentTime['year']);
            break;

            case 0:		// Today
            default:
                $startDateRaw = mktime(0, 0, 0, $currentTime['mon'], $currentTime['mday'], $currentTime['year']);
                $endDateRaw = $startDateRaw;
    	}

    	return array($startDateRaw, $endDateRaw);
    }

    /**
     * Get date value from the request
     * 
     * @param mixed $fieldName ____param_comment____
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDateValue($fieldName) {

        $dateValue = null;

        $name = $fieldName . 'DateRaw';

        if (isset(\XLite\Core\Request::getInstance()->$name)) {
            $dateValue = \XLite\Core\Request::getInstance()->get($name);

        } else {
            $nameDay   = $fieldName . 'DateDay';
            $nameMonth = $fieldName . 'DateMonth';
            $nameYear  = $fieldName . 'DateYear';

            $dateValue = mktime(
                0, 0, 0,
                \XLite\Core\Request::getInstance()->get($nameMonth),
                \XLite\Core\Request::getInstance()->get($nameDay),
                \XLite\Core\Request::getInstance()->get($nameYear)
            );
        }

        return $dateValue;
    }

    /**
     * getNotifications 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNotifications()
    {
        if (is_null($this->notifications)) {
            $cntfs = new \XLite\Module\ProductAdviser\Model\Notification();
            
            $condition = array();
            
            if ($this->getParameter('email', $email)) {
                $condition[] = "(email LIKE '%".addslashes($email)."%')";
            }

            if ($this->getParameter('pinfo', $pinfo)) {
                $condition[] = "(person_info LIKE '%".addslashes($pinfo)."%')";
            }
            
            if (!$this->getParameter('status', $status)) {
            	$this->set('status', CUSTOMER_REQUEST_QUEUED);
            }

            if ($this->getParameter('status', $status)) {
            	if ($status != "A") {
                    $condition[] = "(status='$status')";
                }
            }

            if ($this->getParameter('type', $type)) {
            	if ($type != "A") {
                    $condition[] = "(type='$type')";
                }
            }

            if ($this->getParameter('prodname', $prodname)) {
            	$this->xlite->set('NotificationProductNameFilter', $prodname);
            }

            if ($this->getParameter('notify_key', $notify_key, true)) {
                $condition[] = "(notify_key='$notify_key')";
            }

            if (!$this->getParameter('period', $period)) {
            	$this->set('period', "0");
                $date = getdate(time());
                $this->set('startDateRaw', mktime(0,0,0,$date['mon'],$date['mday'],$date['year']));
                $this->set('endDateRaw', $this->get('startDateRaw'));

            } else {

                if (6 == $period) { // Custom date range
                    $startDate = $this->getDateValue('start');
                    $endDate = $this->getDateValue('end');

                } else { // Fixed date range
        			list($startDateRaw, $endDateRaw) = $this->getDatesRaw($period);
                    $this->set('startDateRaw', $startDateRaw);
                    $this->set('endDateRaw', $endDateRaw);

                   	$date = getdate($this->get('startDateRaw'));
                    $startDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);

                   	$date = getdate($this->get('endDateRaw'));
                    $endDate = mktime(0, 0, 0, $date['mon'], $date['mday'], $date['year']);

               		$this->params[] = "startDateRaw";
                    $this->params[] = "endDateRaw";
                }
                    
    			$condition[] = "(date>='" . $startDate . "' AND date<'" . ($endDate + 24 * 3600) . "')";
            }

            if (!empty($this->action)) {

            	if ($this->action == "prepare_notifications") {
        			$selected = implode(", ", array_keys($this->selected));
                    $condition[] = "status='".CUSTOMER_REQUEST_UPDATED."'";

        		} else {
        			$selected = 0;
                }
                
                $condition[] = "notify_id IN ($selected)";
            }

            $condition = implode(' AND ', $condition);

            $sortby = $this->get('sortby');

          	if (!(isset($sortby) && ($sortby == "email" || $sortby == "date" || $sortby == "type" || $sortby == "status"))) {
          		$this->sortby = $sortby = "date";
          	}
            
            $this->notifications = $cntfs->findAll($condition, $sortby);
            
            if (is_array($this->notifications)) {
            	$this->notificationsNumber = count($this->notifications);

            	if (isset($this->action) && $this->action == "prepare_notifications") {
            		for ($i=0; $i<$this->notificationsNumber; $i++) {
            			$mail = new \XLite\Model\Mailer();
            			$mail->ntf = $this->notifications[$i];
                        $dir = "modules/ProductAdviser/notifications/".$this->notifications[$i]->get('type')."/";
                        $mail->set('subject', $mail->compile($dir.$mail->get('subjectTemplate')));
                        $mail->set('signature', $mail->compile($mail->get('signatureTemplate')));
                        $mail->set('body', $mail->compile($dir.$mail->get('bodyTemplate'), false));
                        $this->notifications[$i]->set('mail', $mail);
            		}
                }

            } else {
            	$this->notifications = null;
            	$this->notificationsNumber = 0;
            }
        }

        return $this->notifications;
    }

    /**
     * doActionDeclineNotifications 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeclineNotifications()
    {
    	if (isset($this->selected) && is_array($this->selected)) {
    		$this->selected = array_keys($this->selected);

    		$statuses = array();
            $statuses[] = "'".CUSTOMER_REQUEST_QUEUED."'";
            $statuses[] = "'".CUSTOMER_REQUEST_UPDATED."'";
            $statuses = implode(", ", $statuses);

    		foreach ($this->selected as $notify_id) {
                $notification = new \XLite\Module\ProductAdviser\Model\Notification();

        		$condition = array();
                $condition[] = "status IN ($statuses)";
                $condition[] = "notify_id='$notify_id'";
                $condition = implode(' AND ', $condition);
                
                if ($notification->find($condition)) {
                    $notification->set('status', CUSTOMER_REQUEST_DECLINED);
                    $notification->update();
                }
    		}
    	}
    }

    /**
     * doActionDeleteNotifications 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionDeleteNotifications()
    {
    	if (isset($this->selected) && is_array($this->selected)) {
    		$this->selected = array_keys($this->selected);

    		$statuses = array();
            $statuses[] = "'".CUSTOMER_REQUEST_SENT."'";
            $statuses[] = "'".CUSTOMER_REQUEST_DECLINED."'";
            $statuses = implode(", ", $statuses);

    		foreach ($this->selected as $notify_id) {
                $notification = new \XLite\Module\ProductAdviser\Model\Notification();

        		$condition = array();
                $condition[] = "status IN ($statuses)";
                $condition[] = "notify_id='$notify_id'";
                $condition = implode(' AND ', $condition);

                if ($notification->find($condition)) {
                    $notification->delete();
                }
    		}
    	}
    }

    /**
     * doActionPrepareNotifications 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPrepareNotifications()
    {
    	$this->set('mode', "process");
    	$this->set('valid', false);
    }

    /**
     * doActionSendNotifications
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSendNotifications()
    {
    	$this->set('silent', true);

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
    		foreach ($this->ids as $ntf_id) {
                $notification = new \XLite\Module\ProductAdviser\Model\Notification();

        		$condition = array();
                $condition[] = "status='".CUSTOMER_REQUEST_UPDATED."'";
                $condition[] = "notify_id='$ntf_id'";
                $condition = implode(' AND ', $condition);

                if ($notification->find($condition)) {
                    $notification->set('status', CUSTOMER_REQUEST_SENT);
                    $notification->update();

        			$mail = new \XLite\Model\Mailer();
                    $mail->set('subject', $this->subjects[$ntf_id]);
                    $mail->set('body', $this->bodies[$ntf_id]);
                    $mail->set('ignoreDefaultSubjectBody', true);
                    $dir = "modules/ProductAdviser/notifications/".$notification->get('type')."/";
                    $mail->compose(
                            $this->config->Company->site_administrator,
                            $notification->get('email'),
                            $dir);
                    echo "Sending notification to <b>".$notification->get('email')."</b>...";
                    $mail->send();
                    echo "&nbsp;<font color=green>[OK]</font><br>"; flush();
                }
            }

            $url = array();

            foreach ($this->getAllParams('action') as $param => $value) {
                $url[] = "$param=" . urlencode($value);
            }

            $url = implode('&', $url);
            $this->set('returnUrl', "admin.php?$url");
?>
<HR>Redirecting <a href="<?php echo $this->get('returnUrl'); ?>"><u>...</u></a>
<SCRIPT language="javascript">
    loaded = true;
    window.location="<?php echo $this->get('returnUrl'); ?>";
</SCRIPT>
<?php
    	}
    }

    /**
     * doActionSaveFilters 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSaveFilters()
    {
        $preferences = array();

        if ($this->getParameter('email', $email)) {
            $preferences['email'] = $email;
        }

        if ($this->getParameter('pinfo', $pinfo)) {
            $preferences['pinfo'] = $pinfo;
        }

        if ($this->getParameter('status', $status)) {
            $preferences['status'] = $status;

        } else {
            $preferences['status'] = "A";
        }

        if ($this->getParameter('type', $type)) {
            $preferences['type'] = $type;

        } else {
            $preferences['type'] = "A";
        }

        if ($this->getParameter('prodname', $prodname)) {
            $preferences['prodname'] = $prodname;
        }

        if ($this->getParameter('period', $period)) {
            $preferences['period'] = $period;

            if ('6' == $period) {
                $preferences['startDateDay'] = \XLite\Core\Request::getInstance()->startDateDay;
                $preferences['startDateMonth'] = \XLite\Core\Request::getInstance()->startDateMonth;
                $preferences['startDateYear'] = \XLite\Core\Request::getInstance()->startDateYear;
                $preferences['endDateDay'] = \XLite\Core\Request::getInstance()->endDateDay;
                $preferences['endDateMonth'] = \XLite\Core\Request::getInstance()->endDateMonth;
                $preferences['endDateYear'] = \XLite\Core\Request::getInstance()->endDateYear;
            }
        }

        \XLite\Core\Database::getRepo('XLite\Model\Config')->createOption(
            array(
                'category' => 'ProductAdviser',
                'name'     => 'filters_preferences',
                'value'    => serialize($preferences),
                'type'     => 'serialized'
            )
        );

        $this->set('returnUrl', $this->buildUrl('CustomerNotifications'));
        $this->redirect();

    }
}

