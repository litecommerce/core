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

namespace XLite\Module\MultiCategories\Validator;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class MultiCategoriesValidator extends \XLite\Validator\RequiredValidator
{
    public $template = "common/required_validator.tpl";
    
    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $result = (!empty($_POST[$this->get('field')]) && is_array($_POST[$this->get('field')]) && !(count($_POST[$this->get('field')]) == 1 && empty($_POST[$this->get('field')][0]))) || !isset($_POST[$this->get('field')]);
        if (isset($_POST['action'])) {
        	$field_name = ($_POST['action'] == "add") ? "product_categories" : "categories";
        	if (!isset($_POST[$field_name])) {
        		$result = false;
        	}
        }
        return $result;
    }

}
