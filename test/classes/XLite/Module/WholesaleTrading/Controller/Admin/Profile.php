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

// FIXME - must be completely revised

/**
 * Profile management controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_Controller_Admin_Profile extends XLite_Controller_Admin_Profile implements XLite_Base_IDecorator
{
    /**
     * Check if it is need to show wholesaler fields in the profile details form
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
/*	public function isShowWholesalerFields()
    {
        $obj = new XLite_Module_WholesaleTrading_Model_Profile();
        return $obj->isShowWholesalerFields();
    }

    /**
     * Do action 'register'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*	protected function doActionRegister()
    {
        parent::doActionRegister();

        if ($this->statusData['success']) {

            $profile = $this->getProfile();

            $exp_type = XLite_Core_Request::getInstance()->membership_exp_type;
            $exp_date = mktime(
                0, 0, 0,
                XLite_Core_Request::getInstance()->membership_exp_dateMonth,
                XLite_Core_Request::getInstance()->membership_exp_dateDay,
                XLite_Core_Request::getInstance()->membership_exp_dateYear
            );

            $newMembership = $profile->get('membership');

            if (('never' == $exp_type) || empty($newMembership)) {
                $exp_date = 0;
            }

            $profile->set('membership_exp_date', $exp_date);
            $profile->update();
        }
    }

    /**
     * Do action 'modify'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*	protected function doActionModify()
    {
        $oldProfile = $this->getProfile();
        $oldMembership = $oldProfile->get('membership');

        parent::doActionModify();

        $profile = $this->getProfile();

        if ($this->statusData['success']) {

            $exp_type = XLite_Core_Request::getInstance()->membership_exp_type;
            $exp_date = mktime(
                0, 0, 0,
                XLite_Core_Request::getInstance()->membership_exp_dateMonth,
                XLite_Core_Request::getInstance()->membership_exp_dateDay,
                XLite_Core_Request::getInstance()->membership_exp_dateYear
            );

            $newMembership = $profile->get('membership');

            if ('never' == $exp_type || empty($newMembership)) {
                $exp_date = 0;
            }

            if ($oldMembership != $newMembership || $oldProfile->get('membership_exp_date') != $exp_date) {

                $history = $profile->get('membership_history');

                foreach ($history as $hn_idx => $hn) {
        			if (isset($hn['current']) && $hn['current']) {
        				unset($history[$hn_idx]);
        				break;
                    }
        		}

                if ((!empty($oldMembership)) || ($oldProfile->get('membership_exp_date') > 0)) {
    				$history_node = array();
    				$history_node['membership'] = $oldMembership;
    				$history_node['membership_exp_date'] = $oldProfile->get('membership_exp_date');
                    $history_node['date'] = time();
                    $history_node['current'] = false;
                    $history[] = $history_node;
    			}

                $history_node = array();
                $history_node['membership'] = $newMembership;
                $history_node['membership_exp_date'] = $exp_date;
                $history_node['date'] = time();
                $history_node['current'] = true;
                $history[] = $history_node;

                $profile->set('membership_history', $history);
                $profile->set('membership_exp_date', $exp_date);
                $profile->update();
            }
        }
    }

    /**
     * Get membership history 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
/*	protected function getMembershipHistory()
    {
        $profile = $this->getProfile();

        if (!is_object($profile)) {
            return;
        }

        $history = $profile->get('membership_history');
        if (is_array($history) && count($history) > 0) {
            $history = array_reverse($history);
        }

        return $history;
    }*/
}

