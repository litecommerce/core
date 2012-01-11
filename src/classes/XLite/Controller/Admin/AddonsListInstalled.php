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
        return static::t('Manage add-ons');
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
        return static::t('Manage add-ons');
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

    /**
     * Search for modules
     *
     * @param string $cellName Request cell name
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModules($cellName)
    {
        $modules = array();

        foreach (((array) \XLite\Core\Request::getInstance()->$cellName) as $id => $value) {
            $modules[] = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find(intval($id));
        }

        return array_filter($modules);
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
                if ($module->getEnabled()) {
                    \Includes\Utils\PHARManager::packModule(new \XLite\Core\Pack\Module($module));

                } else {
                    \XLite\Core\TopMessage::addError('Only enabled modules can be packed');
                }

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
            \Includes\Utils\ModulesManager::disableModule($module->getActualName());

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

            $pack = new \XLite\Core\Pack\Module($module);
            $dirs = $pack->getDirs();

            $nonWritableDirs = array();

            // Check permissions
            foreach ($dirs as $dir) {
                if (!\Includes\Utils\FileManager::isDirWriteable($dir)) {
                    $nonWritableDirs[] = \Includes\Utils\FileManager::getRelativePath($dir, LC_DIR_ROOT);
                }
            }

            $params = array('name' => $module->getActualName());

            if (empty($nonWritableDirs)) {
                $yaml = \Includes\Utils\FileManager::read(
                    \Includes\Utils\ModulesManager::getModuleYAMLFile($module->getAuthor(), $module->getName())
                );

                // Remove from FS
                foreach ($dirs as $dir) {
                    \Includes\Utils\FileManager::unlinkRecursive($dir);
                }

                // Disable this and depended modules
                \Includes\Utils\ModulesManager::disableModule($module->getActualName());

                // Remove from DB
                \XLite\Core\Database::getRepo('\XLite\Model\Module')->delete($module);

                if ($module->getModuleID()) {
                    $message = 'A DB error occured while uninstalling the module "{{name}}"';
                    $this->showError(__FUNCTION__, $message, $params);

                } else {
                    if (!empty($yaml)) {
                        \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($yaml);
                    }

                    $message = 'The module "{{name}}" has been uninstalled successfully';
                    $this->showInfo(__FUNCTION__, $message, $params);
                }

                // To restore previous state
                \XLite\Core\Marketplace::getInstance()->saveAddonsList(0);

                // Flag to rebuild cache
                \XLite::setCleanUpCacheFlag(true);

            } else {
                $message = 'Unable to delete module "{{name}}" files: some dirs have no writable permissions: {{dirs}}';
                $this->showError(__FUNCTION__, $message, $params + array('dirs' => implode(', ', $nonWritableDirs)));
            }
        }
    }

    /**
     * Switch module
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSwitch()
    {
        $changed = false;
        $data    = (array) \XLite\Core\Request::getInstance()->switch;
        $modules = array();

        foreach ($this->getModules('switch') as $module) {
            $old = !empty($data[$module->getModuleId()]['old']);
            $new = !empty($data[$module->getModuleId()]['new']);

            if ($old !== $new) {
                $module->setEnabled(!$old);
                $modules[] = $module;
                $changed = true;
            }
        }

        // Flag to rebuild cache
        if ($changed) {
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->updateInBatch($modules);
            \XLite::setCleanUpCacheFlag(true);
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
