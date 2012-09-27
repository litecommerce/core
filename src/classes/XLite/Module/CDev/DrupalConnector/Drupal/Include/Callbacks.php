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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU General Pubic License (GPL 2.0)
 * @link      http://www.litecommerce.com/
 */

/**
 * Return LC controller title
 *
 * @return string
 */
function lcConnectorGetControllerTitle()
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Controller::getInstance()->getTitle();
}

/**
 * Return LC controller page content
 *
 * @return string
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
 * @return void
 */
function lcConnectorValidateWidgetModifyForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->validateWidgetModifyForm(
        $form,
        $formState
    );
}

/**
 * Submit widget details form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return void
 */
function lcConnectorSubmitWidgetModifyForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitWidgetModifyForm(
        $form,
        $formState
    );
}

/**
 * Submit widget delete confirmation form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return void
 */
function lcConnectorSubmitWidgetDeleteForm(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitWidgetDeleteForm(
        $form,
        $formState
    );
}

/**
 * Submit user profile/register form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return void
 */
function lcConnectorUserProfileFormSubmit(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitUserProfileForm(
        $form,
        $formState
    );
}

/**
 * Submit admin permissions form
 *
 * @param array &$form       Form description
 * @param array &$form_state Form state
 *
 * @return void
 */
function lcConnectorUserPermissionsSubmit(array &$form, array &$formState)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\Admin::getInstance()->submitUserPermissionsForm(
        $form,
        $formState
    );
}

/**
 * Do user accounts synchronization in batch mode
 *
 * @param array &$context Batch process context data
 *
 * @return void
 */
function lcConnectorUserSync(array &$context)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\UserSync::getInstance()->doUserSynchronization(
        $context
    );
}

/**
 * Finalize user accounts synchronization batch process
 *
 * @param boolean $success    Batch process status
 * @param array   $results    Batch process results array
 * @param array   $operations Batch process operations array
 *
 * @return void
 */
function lcConnectorUserSyncFinishedCallback($success, array $results, array $operations)
{
    return \XLite\Module\CDev\DrupalConnector\Drupal\UserSync::getInstance()->doUserSyncFinished(
        $success,
        $results,
        $operations
    );
}
