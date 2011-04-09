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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\FormField\Input\Checkbox;

/**
 * \XLite\View\FormField\Input\Checkbox\ShipAsBill 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShipAsBill extends \XLite\View\FormField\Input\Checkbox
{
    /**
     * Return a value for the "id" attribute of the field input tag
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getFieldId()
    {
        return 'ship-as-bill';
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/ship_as_bill.js';

        return $list;
    }

    
    /**
     * getDefaultValue
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultValue()
    {
        return true;
    }

    /**
     * getDefaultLabel
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultLabel()
    {
        return 'The same as billing';
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/ship_as_bill.tpl';
    }

    /**
     * Determines if checkbox is checked
     *
     * @return boolean 
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isChecked()
    {
        return parent::isChecked() || $this->callFormMethod('getShipAsBillFlag');
    }
}
