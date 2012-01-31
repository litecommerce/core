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

namespace XLite\View\FormField\Select;

/**
 * Form multiple selector
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Multiple extends \XLite\View\FormField\Select\ASelect
{
    /**
     * Set value
     *
     * @param mixed $value Value to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function setValue($value)
    {
        if (is_object($value) && $value instanceOf \Doctrine\Common\Collections\Collection) {
            $value = $value->toArray();

        } elseif (!is_array($value)) {
            $value = array($value);
        }

        foreach ($value as $k => $v) {
            if (is_object($v) && $v instanceOf \XLite\Model\AEntity) {
                $value[$k] = $v->getUniqueIdentifier();
            }
        }

        parent::setValue($value);
    }

    /**
     * getDefaultAttributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultAttributes()
    {
        return parent::getDefaultAttributes() + array('multiple' => 'multiple');
    }

    /**
     * Check - current value is selected or not
     *
     * @param mixed $value Value
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.13
     */
    protected function isOptionSelected($value)
    {
        return in_array($value, $this->getValue());
    }

}
