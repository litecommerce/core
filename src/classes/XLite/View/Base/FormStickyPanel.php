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
 * @since     1.0.10
 */

namespace XLite\View\Base;

/**
 * Form-based sticky panel 
 * 
 * @see   ____class_see____
 * @since 1.0.10
 */
abstract class FormStickyPanel extends \XLite\View\Base\StickyPanel
{
    /**
     * Get buttons widgets
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function getButtons();

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'form/panel';
    }

    /**
     * Get cell class 
     * 
     * @param integer           $idx    Button index
     * @param string            $name   Button name
     * @param \XLite\View\AView $button Button
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getCellClass($idx, $name, \XLite\View\AView $button)
    {
        $classes = array('panel-cell', $name);

        if (1 == $idx) {
            $classes[] = 'first';
        }

        if (count($this->getButtons()) == $idx) {
            $classes[] = 'last';
        }


        return implode(' ', $classes);
    }

    /**
     * Get subcell class (additional buttons)
     *
     * @param integer           $idx    Button index
     * @param string            $name   Button name
     * @param \XLite\View\AView $button Button
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getSubcellClass($idx, $name, \XLite\View\AView $button)
    {
        $classes = array('panel-subcell', $name);

        if (1 == $idx) {
            $classes[] = 'first';
        }

        if (count($this->getAdditionalButtons()) == $idx) {
            $classes[] = 'last';
        }


        return implode(' ', $classes);
    }

    /**
     * Check - sticky panel activat only iof form is changed
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function isFormChangeActivation()
    {
        return true;
    }

    /**
     * Get class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function getClass()
    {
        $class = parent::getClass();

        if ($this->isFormChangeActivation()) {
            $class .= ' form-change-activation';
        }

        return $class;
    }

}
