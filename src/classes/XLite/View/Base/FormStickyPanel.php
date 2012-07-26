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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\Base;

/**
 * Form-based sticky panel 
 * 
 */
abstract class FormStickyPanel extends \XLite\View\Base\StickyPanel
{
    /**
     * Get buttons widgets
     * 
     * @return array
     */
    abstract protected function getButtons();

    /**
     * Return templates directory name
     *
     * @return string
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
     */
    protected function isFormChangeActivation()
    {
        return true;
    }

    /**
     * Get class
     *
     * @return string
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
