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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      1.0.0
 */

namespace XLite\Module\CDev\DrupalConnector\View\Model\Profile;

/**
 * \XLite\Module\CDev\DrupalConnector\View\Model\Profile\Main
 * TODO: check if this class needed
 *
 * @package XLite
 * @see     ____class_see____
 * @since   1.0.0
 */
class Main extends \XLite\View\Model\Profile\Main implements \XLite\Base\IDecorator
{
    /**
     * Process the errors occured during the "validateInput" action
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessErrorActionValidateInput()
    {
        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            // Highligth the "Email" field using Drupal function
            form_set_error('mail', t($this->getErrorActionValidateInputMessage($this->getRequestData('login'))));
        } else {
            parent::postprocessErrorActionValidateInput();
        }
    }

    /**
     * Perform certain action for the model object
     *
     * @return boolean 
     * @access protected
     * @since  1.0.0
     */
    protected function performActionValidateInput()
    {
        $result = parent::performActionValidateInput();

        // Success validation if controller is launched from Drupal context by an administrator
        if (!$result && \XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $result = \XLite\Core\Auth::getInstance()->isAdmin();
        }

        return $result;
    }

    /**
     * Make 'Access level' field available when administrator modifies other user's profile
     * 
     * @param array &$data Widget params
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareFieldParamsAccessLevel(&$data)
    {
        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS() && (\XLite\Core\Auth::getInstance()->isAdmin() || defined('XLITE_INSTALL_MODE'))) {
            $data[\XLite\View\FormField\AFormField::PARAM_IS_ALLOWED_FOR_CUSTOMER] = true;
        }
    }

    /**
     * Make 'Status' field available when administrator modifies other user's profile
     * 
     * @param array &$data Widget params
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareFieldParamsStatus(&$data)
    {
        if (\XLite\Module\CDev\DrupalConnector\Handler::getInstance()->checkCurrentCMS()) {
            $data[\XLite\View\FormField\AFormField::PARAM_IS_ALLOWED_FOR_CUSTOMER] = true;
        }
    }

    /**
     * Populate model object properties by the passed data
     * 
     * @param array $data Data to set up
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setModelProperties(array $data)
    {
        parent::setModelProperties($data);

        if (isset($data['drupal_roles']) && is_array($data['drupal_roles'])) {
            $this->getModelObject()->updateDrupalRoles($data['drupal_roles']);
        }
    }
}
