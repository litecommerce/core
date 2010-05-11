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
 * DB-based configuration registry
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Config extends XLite_Model_Abstract implements XLite_Base_ISingleton
{	
	protected $parsedData = null;

    public $fields = array(
        'category' => '',
        'name' => '',
        'comment' => '',
        'value' => '',
        'category' => '',
        'orderby' => '0',
        'type' => 'text');	
    
    public $primaryKey = array('category', 'name');	
    public $alias = 'config';	
    public $defaultOrder = "orderby";	
    public $configClass = "XLite_Model_Config";
    
    // GET methods {{{
    function getCategories()
    {
        return array("General", "Company", "Email", "Security","AdminIP", "Captcha", "Environment");
    }

    function getCategoryNames()
    {
        return array("General", "Company", "Email", "Security","Admin IP protection", "Captcha protection", "Environment");
    }
    
    function getByCategory($category)
    {
        return $this->findAll("category='$category'", "orderby");
    }
    

    // IS methods {{{
    function isText()
    {
        return $this->get("type") == "text";
    }

    function isCheckbox()
    {
        return $this->get("type") == "checkbox";
    }

    function isCountry()
    {
        return $this->get("type") == "country";
    }

    function isState()
    {
        return $this->get("type") == "state";
    }

    function isChecked()
    {
        return $this->get("value") == 'Y';
    }

    function isSelect()
    {
        return $this->get("type") == "select";
    }
    
    function isSelected($property, $value = null, $prop = null)
    {
        return parent::isSelected("value", $property);
    }

    function isName($name)
    {
        return $this->get("name") == $name;
    }

    function isTextArea()
    {
        return $this->get("type") == "textarea";
    }
    
    function isSeparator()
    {
        return $this->get("type") == "separator";
    }
    
    

    /**
    * Read config variables
    */
    public function readConfig($force = false)
    {
		if (!is_null($this->parsedData) && !$force) {
			return $this->parsedData;
		}

        $config = new XLite_Base();
		$row = new $this->configClass;
        $r = $row->iterate();
        while ($row->next($r)) {
            $category = $row->get("category");
            if (!isset($config->$category)) {
                $config->$category = new XLite_Base();
            }
            $name = $row->get("name");
            if ($row->get("type") == "checkbox") {
                $config->$category->$name = $row->get("value") == 'Y' ? true : false;
            } else if ($row->get("type") == "serialized") {
                $config->$category->$name = unserialize($row->get("value"));
            } else {    
                $config->$category->$name = $row->get("value");
            }    
        }
        $config->Company->locationCountry = new XLite_Model_Country($config->Company->location_country);
        $config->Company->locationState = new XLite_Model_State($config->Company->location_state);
		if ($config->Company->locationState->get("state_id") == -1) {
			$config->Company->locationState->set("state", $config->Company->get("custom_location_state"));
		}
        $config->General->defaultCountry = new XLite_Model_Country($config->General->default_country);
		$config->Memberships->memberships = array();
		if (isset($config->Memberships->membershipsCollection)) {
			if (is_array($config->Memberships->membershipsCollection)) {
    			foreach($config->Memberships->membershipsCollection as $membership) {
    				$config->Memberships->memberships[] = $membership['membership'];
    			}
    		} else {
				$config->Memberships->membershipsCollection = array();
    		}
		} else {
			$config->Memberships->membershipsCollection = array();
		}

        return ($this->parsedData = $config); 
    } 

    function createOption($category, $name, $value, $type = null, $comment = null, $orderby = null) 
    {
        $config = new $this->configClass;
        if ($config->find("name='$name' AND category='$category'")) {
            $config->set("value", $value);
            if (!is_null($type)) {
                $config->set("type", $type);
            }
            if (!is_null($comment)) {
                $config->set("comment", $comment);
            }
            if (!is_null($orderby)) {
                $config->set("orderby", $orderby);
            }
            $config->update();
        } else {
            $config->set("name", $name);
            $config->set("category", $category);
            $config->set("value", $value);
            if (!is_null($type)) {
                $config->set("type", $type);
            }
            if (!is_null($comment)) {
                $config->set("comment", $comment);
            }
            if (!is_null($orderby)) {
                $config->set("orderby", $orderby);
            }
            $config->create();
        }
    } 


	public function update()
	{
		parent::update();

		$this->readConfig(true);
	}

	public static function getInstance()
    {
        return self::getInternalInstance(__CLASS__)->readConfig();
    }
}
