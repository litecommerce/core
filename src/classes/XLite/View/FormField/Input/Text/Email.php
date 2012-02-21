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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\FormField\Input\Text;

/**
 * Email
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Email extends \XLite\View\FormField\Input\Text
{
    /**
     * Check field validity
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkFieldValidity()
    {
        $result = parent::checkFieldValidity();

        if (
            $result
            && $this->getValue()
            && false === filter_var($this->getValue(), FILTER_VALIDATE_EMAIL)
        ) {
            $result = false;
            $this->errorMessage = \XLite\Core\Translation::lbl(
                'The value of the X field has an incorrect format',
                array(
                    'name' => $this->getLabel(),
                )
            );
        }

        return $result;
    }

    /**
     * Assemble validation rules
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function assembleValidationRules()
    {
        $rules = parent::assembleValidationRules();

        $rules[] = 'custom[email]';

        return $rules;
    }
}
