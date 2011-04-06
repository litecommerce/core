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

namespace XLite\Controller\Admin;

/**
 * Modules
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Modules extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getTitle()
    {
        return 'Manage add-ons' . $this->getUpgradableModulesFlag();
    }
    
    /**
     * Call controller action or special default action
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function handleRequest()
    {
        // :FIXME: to remove
        // \XLite\Core\Database::getRepo('XLite\Model\Module')->checkModules();

        parent::handleRequest();
    }

    /**
     * Common method to determine current location
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLocation()
    {
        return 'Manage add-ons';
    }

    /**
     * Return upgradable modules flag label:
     * - empty string if no any
     * - number of upgradable modules in brackets
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUpgradableModulesFlag()
    {
        // :FIXME: actualize
        /*$upgradeables = count(\Xlite\Core\Database::getRepo('XLite\Model\Module')->findUpgradableModules());

        return 0 < $upgradeables ? ' (' . $upgradeables . ')' : '';*/

        return '';
    }

    /**
     * Enable module
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionEnable()
    {
        $this->setReturnURL($this->buildURL('modules'));

        $id = \XLite\Core\Request::getInstance()->moduleId;

        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($id);

        if ($module) {

            $module->setEnabled(true);

            \XLite\Core\Database::getEM()->flush();

            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Pack module into PHAR module file
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionPack()
    {
        $this->setReturnURL($this->buildURL('modules'));

        if (LC_DEVELOPER_MODE) {

            $moduleId = \XLite\Core\Request::getInstance()->moduleId;
            $module   = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);

            if ($module) {
                \Includes\Utils\PHARManager::packModule(new \XLite\Core\Pack\Module($module));
            } else {
                \XLite\Core\TopMessage::addError('Module with ID "' . $moduleId . '" is not found');
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
     * @since  3.0.0
     */
    protected function doActionDisable()
    {
        $this->setReturnURL($this->buildURL('modules'));

        $id     = \XLite\Core\Request::getInstance()->moduleId;
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($id);

        if ($module) {

            \Includes\Decorator\Utils\ModulesManager::disableModule($module->getActualName());

            \XLite::setCleanUpCacheFlag(true);
        }
    }

    /**
     * Uninstall module
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUninstall()
    {
        $module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find(
            \XLite\Core\Request::getInstance()->moduleId
        );

        if (!$module) {

            \XLite\Core\TopMessage::addError('The module to uninstall has not been found');

        } else {

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
        
        $this->setReturnURL($this->buildURL('modules'));
    }
}
