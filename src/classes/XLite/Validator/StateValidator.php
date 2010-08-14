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
class StateValidator extends \XLite\Validator\AValidator
{
    public $template = "common/state_validator.tpl";

    /**
    * Validates a form field.
    * @param object $formData  An object containing form fields' values.
    * @return boolean
    */
    function isValid()
    {
        if (!parent::isValid()) {
            return false;
        }

        $field = $this->field;
        $countryField = $this->countryField;
        if (isset($_POST[$field]) && isset($_POST[$countryField]) && $_POST[$field] != '') {
            $state = \XLite\Core\Database::getEM()->find('XLite\Model\State', $_POST[$field]);
            $country = \XLite\Core\Database::getEM()->find('XLite\Model\Country', $_POST[$countryField]);
            return !$state
                || $state->country_code == ''
                || ($country && $state->country_code == $country->code);
        }

        return true;
    }
}
