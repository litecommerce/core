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

namespace XLite\Controller\Admin;

/**
 * AddonsListInstalled 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class AddonsListInstalled extends \XLite\Controller\Admin\Base\AddonsList
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        return 'Manage add-ons';
    }
    
    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return 'Manage add-ons';
    }

    // {{{ Short-name methods

    /**
     * Return module identificator
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleId()
    {
        return \XLite\Core\Request::getInstance()->moduleId;
    }

    /**
     * Search for module
     *
     * @return \XLite\Model\Module|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($this->getModuleId());
    }

    // }}}

    // Action handlers

    /**
     * Enable module
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionEnable()
    {
        $module = $this->getModule();

        if ($module) {

            // Update data in DB
            // :TODO: this action should be performed via ModulesManager
            $module->setEnabled(true);
            $module->getRepository()->update($module);

            // Flag to rebuild cache
            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Pack module into PHAR module file
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionPack()
    {
        if (LC_DEVELOPER_MODE) {

            $module = $this->getModule();

            if ($module) {
                \Includes\Utils\PHARManager::packModule(new \XLite\Core\Pack\Module($module));
            } else {
                \XLite\Core\TopMessage::addError('Module with ID "' . $this->getModuleId() . '" is not found');
            }

        } else {

            \XLite\Core\TopMessage::addError(
                'Module packing is available in the DEVELOPER mode only. Check etc/config.php file'
            );
        }
    }

    /**
     * Disable module
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDisable()
    {
        $module = $this->getModule();

        if ($module) {

            // Update data in DB
            \Includes\Decorator\Utils\ModulesManager::disableModule($module->getActualName());

            // Flag to rebuild cache
            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Uninstall module
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUninstall()
    {
        $module = $this->getModule();

        if ($module) {

            $class = $module->getMainClass();
            $notes = $class::getPostUninstallationNotes();

            // Disable this and depended modules
            \Includes\Decorator\Utils\ModulesManager::disableModule($module->getActualName());

            $status = $module->uninstall();

            if ($status) {
                \XLite\Core\TopMessage::addInfo('The module has been uninstalled successfully');
            } else {
                \XLite\Core\TopMessage::addWarning('The module has been partially uninstalled');
            }

            if ($notes) {
                \XLite\Core\TopMessage::addInfo($notes);
            }
        }
    }

    /**
     * Perform some actions before redirect
     *
     * @param string $action Performed action
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function actionPostprocess($action)
    {
        parent::actionPostprocess($action);

        $this->setReturnURL($this->buildURL('addons_list_installed'));
    }

    // }}}
}
