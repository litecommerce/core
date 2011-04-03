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
 * @since     3.0.0
 */

namespace XLite\View\Button;

/**
 * 'Delete selecetd' button
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class DeleteSelected extends \XLite\View\Button\Regular
{
    /**
     * Widget parameter names
     */

    const PARAM_CONFIRMATION = 'confirm';


    /**
     * getDefaultLabel
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultLabel()
    {
        return 'Delete selected';
    }

    /**
     * getDefaultAction
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultAction()
    {
        return 'delete';
    }

    /**
     * getDefaultConfirmationText 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultConfirmationText()
    {
        return 'Do you really want to delete selected items?';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_CONFIRMATION => new \XLite\Model\WidgetParam\String(
                'Confirmation text', $this->getDefaultConfirmationText(), true
            ),
        );
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultJSCode()
    {
        $code = parent::getDefaultJSCode();

        if ($this->getParam(self::PARAM_CONFIRMATION)) {
            $code = 'if (confirm(\'' . $this->getParam(self::PARAM_CONFIRMATION) . '\')) { ' . $code . ' }';
        }

        return $code;
    }
}

