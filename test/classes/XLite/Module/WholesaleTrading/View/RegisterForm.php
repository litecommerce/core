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

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_WholesaleTrading_View_RegisterForm extends XLite_View_RegisterForm implements XLite_Base_IDecorator
{
	function fillForm()
	{
		$p = $this->get("profile");
		if ($this->xlite->is("adminZone") && is_object($p)) {
			if ($p->get("membership_exp_date") > 0) {
				$this->set("membership_exp_type", "custom");
			} else {
				$this->set("membership_exp_type", "never");
				$p->set("membership_exp_date", time());
			}
		}
		parent::fillForm();
	}

	function getAllParams()
	{
		$params = parent::getAllParams();
		// remove duplicate form parameters: date select and hidden form parameters
		unset($params["membership_exp_dateDay"]);
		unset($params["membership_exp_dateMonth"]);
		unset($params["membership_exp_dateYear"]);

		return $params;
	}
}
