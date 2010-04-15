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
class XLite_Module_AOM_Model_Wysiwyg_Mediator extends XLite_Model_Wysiwyg_Mediator implements XLite_Base_IDecorator
{
    
    function export($templates)
    {
		$this->AOM_remove_admin_layouts();
		return parent::export($templates);
    }


	function import()
	{
		$this->AOM_remove_admin_layouts();
		return parent::import();
	}

	function AOM_remove_admin_layouts()
	{
		$layout = XLite_Model_Layout::getInstance();

		$list = $layout->list;
		unset($list["order/order.tpl"]);
		unset($list["order/search_form.tpl"]);
		$layout->list = $list;
	}
}
