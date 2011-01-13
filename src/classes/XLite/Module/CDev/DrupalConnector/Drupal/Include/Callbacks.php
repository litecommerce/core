<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * @file
 * Stub functions. They are needed since Drupal does not support full-pledged callbacks
 *
 * @category  Litecommerce connector
 * @package   Litecommerce connector
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Return LC controller title
 * 
 * @return string
 * @see    ____func_see____
 * @since  3.0.0
 */
function lcConnectorGetControllerTitle()
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Controller::getInstance()->getTitle();
}

/**
 * Return LC controller page content
 *
 * @return string
 * @see    ____func_see____
 * @since  3.0.0
 */
function lcConnectorGetControllerContent()
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Controller::getInstance()->getContent();
}

/**
 * Validate widget details form
 * 
 * @param array &$form      Form description
 * @param array &$formState Form state
 *  
 * @return null
 * @see    ____func_see____
 * @since  3.0.0
 */
function lcConnectorValidateWidgetModifyForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->validateWidgetModifyForm($form, $formState);
}

/**
 * Submit widget details form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return null
 * @see    ____func_see____
 * @since  3.0.0
 */
function lcConnectorSubmitWidgetModifyForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitWidgetModifyForm($form, $formState);
}

/**
 * Submit widget delete confirmation form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return null
 * @see    ____func_see____
 * @since  3.0.0
 */
function lcConnectorSubmitWidgetDeleteForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitWidgetDeleteForm($form, $formState);
}
