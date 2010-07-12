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

namespace XLite\View\Button;

/**
 * 'Delete selecetd' button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class DeleteSelected extends \XLite\View\Button\Regular
{
    /**
     * Widget parameter names
     */

    const PARAM_CONFIRMATION = 'confirm';

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_CONFIRMATION => new \XLite\Model\WidgetParam\String('Confirmation text', '', true),
        );

        $this->widgetParams[self::PARAM_ACTION]->setValue('delete');
        $this->widgetParams[self::PARAM_LABEL]->setValue('Delete selected');
    }

    /**
     * JavaScript: default JS code to execute
     *
     * @return string
     * @access protected
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

