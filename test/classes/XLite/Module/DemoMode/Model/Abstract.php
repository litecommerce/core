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
class XLite_Module_DemoMode_Model_Abstract extends XLite_Model_Abstract implements XLite_Base_IDecorator
{
	public function __construct($id = null)
	{
		parent::__construct($id);

		global $safeData;
		if (!isset($safeData)) {
			$safeData = XLite_Model_Session::getInstance()->get('safeData');
			if (is_null($safeData)) {
				$safeData = array();
			}
		}
	}
	
	function _setSessionVar($path, $value)
	{
		global $safeData;
		$ptr = $safeData;
		foreach ($path as $key) {
			if (!isset($ptr[$key])) {
				$ptr[$key] = array();
			}
			$ptr = $ptr[$key];
		}
		$ptr = $value;
		XLite_Model_Session::getInstance()->set('safeData', $safeData);
	}

	function _getSessionVar($path)
	{
		global $safeData;
		$ptr = $safeData;
		foreach ($path as $key) {
			if (!isset($ptr[$key])) {
				return null;
			}
			if (is_scalar($ptr)) {
				return $ptr;
			}
			$ptr = $ptr[$key];
		}	
		return $ptr;
	}

	function _updateProperties(array $properties = array())
	{
		if (!XLite_Model_Session::getInstance()->get("superUser")) {
			$path = array($this->alias, '');
			foreach ($this->primaryKey as $pkey) {
				$path[] = $properties[$pkey];
			}
			foreach ($properties as $name => $value) {
				$path[1] = $name;
				$val = $this->_getSessionVar($path);
				if (isset($val)) {
					$properties[$name] = $val;
				}
			}
		}
		parent::_updateProperties($properties);
	}

	function update()
	{
		if (!XLite_Model_Session::getInstance()->get("superUser")) {
			$path = array($this->alias, '');
			foreach ($this->primaryKey as $pkey) {
				$path[] = $this->properties[$pkey];
			}
			if ($this->alias == "config" ||
				$this->alias == "modules" ||
				$this->alias == "payment_methods" ||
				$this->alias == "profiles" && $this->get("profile_id") == 1) {
				$this->_beforeSave();
				foreach ($this->properties as $key => $value) {
					if ($this->alias == "payment_methods" && ($key != "orderby" ) || $this->alias != "payment_methods") {
						$path[1] = $key;
						$this->_setSessionVar($path, $value);
					}
				}
			} else {
				parent::update();
			}
		} else {
			parent::update();
		}
	}

}
