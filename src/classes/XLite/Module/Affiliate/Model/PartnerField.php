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
class XLite_Module_Affiliate_Model_PartnerField extends XLite_Model_AModel
{
    public $fields = array (
            "field_id" => 0,
            "field_type" => "Text",
            "name" => "",
            "value" => "",
            "cols" => 25,
            "rows" => 4,
            "orderby" => 0,
            "required" => 1,
            "enabled" => 1,
            );

    public $autoIncrement = "field_id";
    public $alias = "partner_fields";
    public $defaultOrder = "orderby";

    function filter()
    {
        if (!$this->xlite->is('adminZone')) {
            return (boolean) $this->get('enabled');
        }
        return parent::filter();
    }

    function getFieldOptions()
    {
        $array = explode("\n", $this->get('value'));
        for ($i = 0; $i < count($array); $i++) {
            $array[$i] = trim($array[$i]);
        }
        return $array;
    }
}
