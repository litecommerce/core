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
 * @subpackage Validator
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Validator;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ArrayFieldValidator extends AValidator
{
    public $template = "common/required_validator.tpl";

    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        preg_match('/^(.+)\[(.+)\]$/',$this->get('field'),$field);
  	    $result = !isset($_POST["$field[1]"]["$field[2]"]) || (trim($_POST[$field[1]][$field[2]]) != "");
        return $result;
    }

    function isValidationUnnecessary()
    {
        $class = strtolower(get_class($this));
        preg_match('/^(.+)\[(.+)\]$/',$this->get('field'), $field);
        return (!isset($_POST['VALIDATE'][$class][$field[1]][$field[2]]));
    }

    function display()
    {
        if ($this->is('visible')) {
            $class = strtolower(get_class($this));
            preg_match('/^(.+)\[(.+)\]$/',$this->get('field'),$field);
            echo "<input type='hidden' name='VALIDATE[$class][" . $field[1] . "][" . $field[2] ."]' value='1'>\n";
        }
        
        if (!$this->is('valid')) {
            parent::display();
        }
    }

}
