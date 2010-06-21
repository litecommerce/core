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
 * @subpackage Model
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
class XLite_Model_Membership extends XLite_Model_Abstract
{
    public $fields = array
    (
    	"membership_id" => 0,
        "membership"	=> "",
        "orderby"		=> ""
    );
    public $isRead         = true;
    public $memberships    = null;
    public $countRequested = null;
    public $countGranted   = null;

    function getMemberships() 
    {
        if (!is_null($this->memberships)) {
            return $this->memberships;
        }

        $this->memberships = $memberships = $this->config->Memberships->membershipsCollection;

        $oldMemberships = $this->config->Memberships->memberships;

        if (!is_array($memberships)) {

            if (is_array($oldMemberships) && count($oldMemberships) > 0) {

                $id = 1;
                $new_memberships = array();

                foreach ($oldMemberships as $membership) {

                    if (!is_array($membership)) {
    					$new_memberships[$id] = array (
    						'orderby'       => ($id * 10),
    						'membership'    => $membership,
    						'membership_id' => $id
                        );

    				} else {
    					$new_memberships[$id] = $membership;
                    }

                    $id ++;
                }

                $this->memberships = $new_memberships;

                XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Memberships', 'membershipsCollection', serialize($this->memberships), 'serialized');

            } else {
                $this->memberships = array();

                XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Memberships', 'membershipsCollection', serialize($this->memberships), 'serialized');
            }
        }
        
        return $this->memberships;
    }
    
    public function __construct() 
    {
        parent::__construct();

        $memberships = $this->get('memberships');
        $args = func_get_args();

        if (count($args)) {
            if (!is_null($args[0])) {
                $this->set('properties', $memberships[$args[0]]);
            }
        }
    }

    function create() 
    {
        $max = 1;
        $memberships = $this->get('memberships');

        if ($memberships) {
            foreach ($memberships as $membership) {
                if ($membership['membership_id'] >= $max) {
                    $max = $membership['membership_id'] + 1;
                }
            }
        }

        $this->set('membership_id',$max);
        $membershipData = $this->get('properties');
        $membershipData['membership'] = $this->stripInvalidData($membershipData['membership']);
        if (strlen($membershipData['membership']) > 32) {
        	$membershipData['membership'] = substr($membershipData['membership'], 0, 32);
        }
        $memberships[$max] = $membershipData;
        $memberships = (array) $this->sortMemberships($memberships);

        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Memberships', 'membershipsCollection', serialize($memberships), 'serialized');
    }

    function update() 
    {
        $memberships = $this->get('memberships');
        $membershipData = $this->get('properties');
        $membershipData['membership'] = $this->stripInvalidData($membershipData['membership']);
        if (strlen($membershipData['membership']) > 32) {
        	$membershipData['membership'] = substr($membershipData['membership'], 0, 32);
        }
        $memberships[$this->get('membership_id')] = $membershipData;
        $memberships = (array) $this->sortMemberships($memberships);

        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Memberships', 'membershipsCollection', serialize($memberships), 'serialized');
    }

    function delete() 
    {
        $memberships = $this->get('memberships');
        unset($memberships[$this->get('membership_id')]);
        $memberships = (array) $this->sortMemberships($memberships);
        XLite_Core_Database::getRepo('XLite_Model_Config')->createOption('Memberships', 'membershipsCollection', serialize($memberships), 'serialized');
    }

 	function findAll($where = null, $orderby = null, $groupby = null, $limit = null) 
    {
        $result = array();
        $memberships = (array) $this->sortMemberships();
        foreach ($memberships as $membership_id => $membership_) {
            $membership = new XLite_Model_Membership($membership_id);
            $result[$membership_id] = $membership;
        }
        return $result;
    }

    function sortMemberships($memberships = null)
    {
        if (!is_array($memberships)) $memberships = (array) $this->get('memberships');

        uasort($memberships, array($this, "cmp"));
        return $memberships;
    }

    function cmp($a, $b) 
    {
        if  ($a['orderby'] == $b['orderby']) return 0;
        return ($a['orderby'] > $b['orderby']) ? 1 : -1;
    }

    function getRequestedCount() 
    {
        $count = 0;
        if (is_null($this->countRequested)) {
            $prop = $this->get('properties');
            if (is_array($prop)) {
                $profile = new XLite_Model_Profile();
                $where = $profile->_buildWhere("(pending_membership<>membership AND pending_membership='".$prop['membership']."')");
                $count = $profile->count($where);
            }
            $this->countRequested = $count;
        } else {
            $count = $this->countRequested;
        }

        return $count;
    }

    function getGrantedCount() 
    {
        $count = 0;
        if (is_null($this->countGranted)) {
            $prop  = $this->get('properties');
            if (is_array($prop)) {
                $profile = new XLite_Model_Profile();
                $where = $profile->_buildWhere("membership='".$prop['membership']."'");
                $count = $profile->count($where);
            }
            $this->countGranted = $count;
        } else {
            $count = $this->countGranted;
        }

        return $count;
    }

    /*
     * remove from the $value characters, that are slashed by mysql_real_escape_string()
     * they are: x00, \n, \r, \, ', " and \x1a
     */
    function stripInvalidData($value) 
    {
        $expression = $this->getStripRegExp();
        $value = preg_replace("/$expression/", " ", $value);
        $value = trim($value);
        return $value;
    }

    function getStripRegExp()
    {
        $expression = "[\\x00\\n\\r\\\\'\"\\x1a]+";
        return $expression;
    }

}
