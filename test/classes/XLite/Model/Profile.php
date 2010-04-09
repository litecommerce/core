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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Class Profile provides access to user profile data 
 * 
 * @package    XLite
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Model_Profile extends XLite_Model_Abstract
{
    // properties {{{
    
    /**
    * @var array fields The profile properties
    *
    * Pay attention to using the "access_level", "status" and 
    * "membership" fields. Prevent modifications with POST'ed data
    */	
    public $fields = array(
              'profile_id' => 0,
                'order_id' => '0',
	               'login' => '',
	            'password' => '',
	       'password_hint' => '',
	'password_hint_answer' => '',
	        'access_level' => '0',
          'cms_profile_id' => '0',
                'cms_name' => '',
	       'billing_title' => '',
	   'billing_firstname' => '',
	    'billing_lastname' => '',
	     'billing_company' => '',
	       'billing_phone' => '',
	         'billing_fax' => '',
	     'billing_address' => '',
	        'billing_city' => '',
	       'billing_state' => '',
    'billing_custom_state' => '',
	     'billing_country' => '',
	     'billing_zipcode' => '',
	      'shipping_title' => '',
	  'shipping_firstname' => '',
	   'shipping_lastname' => '',
	    'shipping_company' => '',
	      'shipping_phone' => '',
	        'shipping_fax' => '',
	    'shipping_address' => '',
	       'shipping_city' => '',
	      'shipping_state' => '',
   'shipping_custom_state' => '',
	    'shipping_country' => '',
	    'shipping_zipcode' => '',
	        'extra_fields' => '',
	           'card_name' => '',
	           'card_type' => '',
	         'card_number' => '',
	         'card_expire' => '',
	           'card_cvv2' => '',
	         'first_login' => '0',
	          'last_login' => '0',
	              'status' => '',
	             'referer' => '',
	          'membership' => '',
	  'pending_membership' => '',
           'sidebar_boxes' => ''
    );	
        
    public $_securefields = array(
              'profile_id' => 0,
	        'access_level' => '0',
	         'first_login' => '0',
	          'last_login' => '0',
	          'membership' => ''
            );	

    public $_adminSecurefields = array(
              'last_login' => '0'
            );	

    public $autoIncrement = "profile_id";	
    public $alias = "profiles";	
    public $defaultOrder = "login";	
    public $_range = "order_id=0";


    
    /**
    * Modifies safe properties (excluding adminSecurefields).
    * Useful when a admin edit(create) profile.
    *
    * @access public
    * @param array $data The properties data to modify
    **/
    function modifyAdminProperties($properties) // {{{
    {
        if (is_array($properties)) {
            foreach ($properties as $key => $value) {
                if (array_key_exists($key, $this->_adminSecurefields)) {
					if (isset($properties[$key])) {
                    	unset($properties[$key]);
                    }
                }
            }
            $this->setProperties($properties);
        }
    }

    /**
    * Modifies safe properties (excluding securefields).
    * Useful when a customer edits his own profile.
    *
    * @access public
    * @param array $data The properties data to modify
    **/
    function modifyProperties($properties) // {{{
    {
        if (is_array($properties)) {
            foreach ($properties as $key => $value) {
                if (array_key_exists($key, $this->_securefields)) {
					if (isset($properties[$key])) {
                		unset($properties[$key]);
                	}
                }
            }
            $this->setProperties($properties);
        }
    } // }}}

    function isEnabled() // {{{
    {
        return strtoupper($this->get("status")) == "E" ? true : false;
    } // }}}

    function getBillingState() // {{{
    {
        $state = new XLite_Model_State($this->get("billing_state"));
		if ($state->get("state_id") == -1)
			$state->set("state", $this->get("billing_custom_state"));

		return $state;
    } // }}}
    function getShippingState() // {{{
    {
        $state = new XLite_Model_State($this->get("shipping_state"));
		if ($state->get("state_id") == -1)
			$state->set("state", $this->get("shipping_custom_state"));

		return $state;
    } // }}}
    function getBillingCountry() // {{{
    {
        return new XLite_Model_Country($this->get("billing_country"));
    } // }}}
    function getShippingCountry() // {{{
    {
        return new XLite_Model_Country($this->get("shipping_country"));
    } // }}}

    function enable() // {{{
    {
        $this->set("status", "E");
    } // }}}

    function disable() // {{{
    {
        $this->set("status", "D");
    } // }}}

    function isExists($login = '') // {{{
    {
        $p = new XLite_Model_Profile();

        return $p->find('login = \'' . addslashes($login) . '\'');
    } // }}}

    function isValid()
    {
        return parent::isExists();
    }

    function isAdmin() // {{{
    {
        $auth = XLite_Model_Auth::getInstance(); 
        return $auth->isAdmin($this);
    } // }}}

    function toXML() // {{{
    {
        $id = "profile_" . $this->get("profile_id");
        $xml = parent::toXML();
        return "<profile id=\"$id\">\n$xml</profile>\n";
    } // }}}

    function import(array $options) // {{{
    {
        parent::import($options);
        // save memberships
        
        $c = new XLite_Model_Config();
        $c->set("category", "Memberships");
        $c->set("name", "memberships");
        $c->set("value", serialize($this->config->getComplex('Memberships.memberships')));
        $c->update();
    } // }}}

    /**
    * Import a row from outside. 
    * It will modify the $this->config->Memberships->memberships variable
    * SO you need to save it after all.
    */
    function _import(array $options) // {{{
    {
        static $line;
        if (!isset($line)) $line = 1; else $line++;
        echo "<b>line# $line:</b> ";

        $properties = $options["properties"];

        $this->_convertProperties($properties, $options['md5_import']);
        $existent = false;
        $profile = new XLite_Model_Profile();
        $login =  $properties["login"];
        if (empty($login)) {
            echo "<font color=red>WARNING!</font> Ignoring import row: \"login\" property not found<br>\n";
            return;
        }
        if ($profile->find("login='" . addslashes($login) . "'")) {
            $profile->set("properties", $properties);
            echo "Updating user: ";
            $profile->update();
        } else {
            $profile->set("properties", $properties);
            echo "Creating user: ";
            $profile->create();
        }
        echo  $login . "<br>\n";
        func_flush();
        if (!empty($properties["membership"])) {
            $found = array_search($properties["membership"], $this->config->getComplex('Memberships.memberships'));
            if ($found === false || $found === null) {
                $memberships = $this->config->getComplex('Memberships.memberships');
                $memberships[] = $properties["membership"];
                $this->config->setComplex("Memberships.memberships", $memberships);
            }
        }
    } // }}}

    function _convertProperties(array &$p, $md5_import = '') // {{{
    {
        // X-CART Gold/Pro compatibility check for profile import
        if (!empty($p["status"])) {
            if ($p["status"] == 1 || $p["status"] == 'Y' || $p["status"] == 'y' || $p["status"] == 'E') {
                $p["status"] = 'E';
            } else {
                $p["status"] = 'D';
            }
        }
        if (isset($p["password"])) {
            if($md5_import == "yes")
                $p["password"] = $p["password"];
            else
                $p["password"] = md5($p["password"]);
        }
        if (isset($p["billing_state"])) {
        	$p["billing_state"] = $this->_convertState($p["billing_state"]);
        }
        if (isset($p["billing_country"])) {
        	$p["billing_country"] = $this->_convertCountry($p["billing_country"]);
        }
        if (isset($p["shipping_state"])) {
        	$p["shipping_state"] = $this->_convertState($p["shipping_state"]);
        }
        if (isset($p["shipping_country"])) {
        	$p["shipping_country"] = $this->_convertCountry($p["shipping_country"]);
        }
    } // }}}

    function _convertState($value)
    {
    	$state = new XLite_Model_State();
    	$value = addslashes($value);
    	if ($state->find("code='$value'") || $state->find("state='$value'") || $state->find("state_id='$value'")) {
    		return $state->get("state_id");
    	}
    	return -1;
    }

    function _convertCountry($value)
    {
    	$country = new XLite_Model_Country();
    	$value = addslashes($value);
    	if ($country->find("code='$value'") || $country->find("country='$value'")) {
    		return $country->get("code");
    	}
    	return "";
    }

    function getImportFields($layout = null) // {{{
    {
        $layout = array();
        if (!is_null($this->config->getComplex('ImportExport.user_layout'))) {
            $layout = explode(',', $this->config->getComplex('ImportExport.user_layout'));
        }
        // build import fields list
        $fields = array();
        $fields["NULL"] = false;
        $result = array();
        // get object properties ad prepare import fields list
        foreach ($this->fields as $name => $value) {
            if ($name == "profile_id" || $name == "order_id" || $name == "sidebar_boxes" || $name == "extra_fields" || $name == "password_hint" || $name == "password_hint_answer") {
                continue;
            }
            $fields[$name] = false; 
        }
        // get count(fields) of fields array
        foreach ($fields as $field) {
            $result[] = $fields;
        }
        // fill fields array with the default layout
        foreach ($result as $id => $fields) {
            if (isset($layout[$id])) {
                $selected = $layout[$id];
                $result[$id][$selected] = true;
            }    
        }
        return $result;
    } // }}}
    
    function _beforeSave()
    {
        $this->auth->_reReadProfiles(true);
        parent::_beforeSave();
    }
}

