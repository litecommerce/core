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
class XLite_Module_Affiliate_View_PartnerField extends XLite_View_Abstract
{	
    public $field = null;	
    public $formField = null;	
    public $partner = null;

    function getValue()
    {
        // form submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            return isset($_POST[$this->formField][$this->getComplex('field.field_id')]) ?
                          $_POST[$this->formField][$this->getComplex('field.field_id')] : null;
        }
        // value from partner's profile
        elseif (!is_null($this->get("partner.partner_fields.".$this->getComplex('field.field_id')))) {
            return $this->get("partner.partner_fields.".$this->getComplex('field.field_id')); 
        }
        // default field value
        else {
            return $this->getComplex('field.value');
        }
    }

    function valign($type)
    {
        if ($type == "Textarea" || $type == "Radio button") {
            return "top";
        }
        return "middle";
    }

    function isGet()
    {
        return $_SERVER["REQUEST_METHOD"] == "GET";
    }
}
