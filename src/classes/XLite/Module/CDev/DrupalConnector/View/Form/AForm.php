<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Pubic License (GPL 2.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-2.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 *
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\View\Form;

/**
 * Abstract form widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class AForm extends \XLite\View\Form\AForm implements \XLite\Base\IDecorator
{
    /**
     * Chech if widget is exported into Drupal and current form has its method = "GET"
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDrupalGetForm()
    {
        return \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()
            && 'get' == strtolower($this->getParam(self::PARAM_FORM_METHOD));
    }

    /**
     * This JavaScript code will be performed when form submits
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getJSOnSubmitCode()
    {
        return ($this->isDrupalGetForm() ? 'drupalOnSubmitGetForm(this); ' : '') . parent::getJSOnSubmitCode();
    }

    /**
     * JavaScript: compose the "{'a':<a>,'b':<b>,...}" string (JS array) by the params array
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFormParamsAsJSArray()
    {
        return '[\'' . implode('\',\'', array_keys($this->getFormParams())) . '\']';
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

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(array('q' => ''));
    }
}
