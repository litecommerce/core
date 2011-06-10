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
 * Upgrade
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Upgrade extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Common methods

    /**
     * Run controller
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function run()
    {
        // Clear all selection if you visit the "Available updates" page
        if ($this->isUpdate()) {
            \XLite\Upgrade\Cell::getInstance()->clear();
        }

        if ($this->isIntegrityCheckNeeded()) {
            \XLite\Core\Request::getInstance()->action = 'check_integrity';
        }

        parent::run();
    }

    /**
     * Condition for integrity check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isIntegrityCheckNeeded()
    {
        $request = \XLite\Core\Request::getInstance();
        $cell    = \XLite\Upgrade\Cell::getInstance();

        return $request->isGet() && !isset($request->action) && $cell->isUnpacked();
    }

    // }}}

    // {{{ Methods for viewers

    /**
     * Return the current page title (for the content area)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getTitle()
    {
        if ($this->isCoreSelection()) {
            $result = 'Upgrade core';

        } elseif ($this->isDownload()) {
            $result = 'Downloading updates';

        } else {
            $version = \XLite\Upgrade\Cell::getInstance()->getCoreMajorVersion();

            if (\XLite::getInstance()->checkVersion($version, '<')) {
                $result = 'Upgrade to version ' . $version;

            } else {
                $result = 'Updates for your version (' . $version . ')';
            }
        }

        return $result;
    }

    /**
     * Check if core major version is equal to the current one
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isUpdate()
    {
        return 'install_updates' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if current page is the core version selection dialog
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCoreSelection()
    {
        return 'select_core_version' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if current page is the updates download dialog
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isDownload()
    {
        return 'download_updates' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if next step of upgrade id available
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isNextStepAvailable()
    {
        return \XLite\Upgrade\Cell::getInstance()->isValid();
    }

    /**
     * Common method to set current location
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLocation()
    {
        return $this->isUpdate() ? 'Updates available' : 'Upgrade';
    }

    /**
     * Check the flag in request
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isForce()
    {
        return (bool) \XLite\Core\Request::getInstance()->force;
    }

    /**
     * Get some common params for actions
     *
     * @param boolean $force Flag OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getActionParamsCommon($force = null)
    {
        return ($force ?: $this->isForce()) ? array('force' => true) : array();
    }

    // }}}

    // {{{ Action handlers

    /**
     * Install add-on from marketplace
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionInstallAddon()
    {
        $moduleId = \XLite\Core\Request::getInstance()->moduleId;
        $module   = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);

        if ($module) {

            if ($module->getMarketplaceID()) {
                \XLite\Upgrade\Cell::getInstance()->clear(true, true, !$this->isForce());

                if (\XLite\Upgrade\Cell::getInstance()->addMarketplaceModule($module, true)) {

                    if ($this->isNextStepAvailable()) {
                        if ($this->isForce()) {
                            $this->setReturnURL($this->buildURL('upgrade', 'download', $this->getActionParamsCommon()));
                        }

                    } else {
                        $this->showError(__FUNCTION__);
                    }

                } else {
                    $message = 'unable to add module entry to the install list: "{{name}}"';
                    $this->showError(__FUNCTION__, $message, array('name' => $module->getActualName()));
                }

            } else {
                $message = 'trying to install a non-marketplace module: "{{name}}"';
                $this->showError(__FUNCTION__, $message, array('name' => $module->getActualName()));
            }

        } else {
            $message = 'invalid module ID passed: "{{moduleId}}"';
            $this->showError(__FUNCTION__, $message, array('moduleId' => $moduleId));
        }
    }

    /**
     * Install uploaded add-on
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUploadAddon()
    {
        $this->setReturnURL($this->buildURL('addons_list_installed'));

        $path = \Includes\Utils\FileManager::moveUploadedFile('modulePack');

        if ($path) {
            \XLite\Upgrade\Cell::getInstance()->clear(true, true, false);
            $entry = \XLite\Upgrade\Cell::getInstance()->addUploadedModule($path);

            if (!isset($entry)) {
                $message = 'unable to add module entry to the install list: "{{path}}"';
                $this->showError(__FUNCTION__, $message, array('path' => $path));

            } elseif (\XLite::getInstance()->checkVersion($entry->getMajorVersionNew(), '<')) {
                $message = 'module version "{{version}}" is greater than the core one';
                $this->showError(__FUNCTION__, $message, array('version' => $entry->getMajorVersionNew()));

            } elseif ($this->isNextStepAvailable()) {
                $this->setReturnURL($this->buildURL('upgrade', 'download', $this->getActionParamsCommon(true)));

            } else {
                $this->showError(__FUNCTION__);
            }

        } else {
            $this->showError(__FUNCTION__, 'unable to upload module');
        }
    }

    /**
     * Go to the upgrade third step
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDownload()
    {
        if ($this->isNextStepAvailable()) {

            // :DEVCODE: to remove
            \Includes\Utils\Operator::showMessage('Downloading updates, please wait...');

            // Disable some modules (if needed)
            \XLite\Upgrade\Cell::getInstance()->setIncompatibleModuleStatuses(
                (array) \XLite\Core\Request::getInstance()->toDisable
            );

            if (\XLite\Upgrade\Cell::getInstance()->downloadUpgradePacks()) {
                $this->setReturnURL($this->buildURL('upgrade', 'unpack', $this->getActionParamsCommon()));

            } else {
                $this->showError(__FUNCTION__, 'not all upgrade packs were downloaded');
            }

        } else {
            $this->showError(__FUNCTION__, 'not ready to download packs');
        }
    }

    /**
     * Go to the upgrade third step
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionUnpack()
    {
        $this->setReturnURL($this->buildURL('upgrade'));

        if (\XLite\Upgrade\Cell::getInstance()->isDownloaded()) {

            // :DEVCODE: to remove
            \Includes\Utils\Operator::showMessage('Unpacking archives, please wait...');

            if (!\XLite\Upgrade\Cell::getInstance()->unpackAll()) {
                $this->showError(__FUNCTION__, 'not all archives were unpacked');

            } elseif ($this->isNextStepAvailable()) {
                if ($this->isForce()) {
                    $this->setReturnURL($this->buildURL('upgrade', 'check_integrity', $this->getActionParamsCommon()));
                }

            } else {
                $this->showError(__FUNCTION__);
            }

        } else {
            $this->showError(__FUNCTION__, 'trying to unpack non-downloaded archives');
        }
    }

    /**
     * Go to the upgrade third step
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionCheckIntegrity()
    {
        // To prevent infinite redirect
        $this->setReturnURL(null);

        if (\XLite\Upgrade\Cell::getInstance()->isUnpacked()) {

            // :DEVCODE: to remove
            \Includes\Utils\Operator::showMessage('Checking integrity, please wait...');

            // Perform upgrade in test mode
            \XLite\Upgrade\Cell::getInstance()->upgrade(true);

            if ($this->isForce() && $this->isNextStepAvailable()) {
                $this->setReturnURL($this->buildURL('upgrade', 'install_upgrades', $this->getActionParamsCommon()));
            }

        } else {
            $this->showError(__FUNCTION__, 'unable to test files: not all archives were unpacked');
        }
    }

    /**
     * Third step: install downloaded upgrades
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionInstallUpgrades()
    {
        // :DEVCODE: to remove
        \Includes\Utils\Operator::showMessage('Installing updates, please wait...');

        $toOverwrite = (array) \XLite\Core\Request::getInstance()->toOverwrite;

        // Perform upgrade
        \XLite\Upgrade\Cell::getInstance()->upgrade(false, $toOverwrite);

        // Disable selected modules
        foreach (\XLite\Upgrade\Cell::getInstance()->getIncompatibleModules(true) as $module) {
            \Includes\Utils\ModulesManager::disableModule($module->getActualName());
        }

        if ($this->isForce()) {
            if ($this->isNextStepAvailable()) {
                $target = 'installed';
                $this->showInfo(null, 'Module has been successfully installed');

            } else {
                $target = 'marketplace';
                $this->showError(__FUNCTION__);
            }

            $this->setReturnURL($this->buildURL('addons_list_' . $target));
        }

        // Set cell status
        \XLite\Upgrade\Cell::getInstance()->clear(true, false, false);
        \XLite\Upgrade\Cell::getInstance()->setUpgraded(true);

        // Rebuild cache
        \XLite::setCleanUpCacheFlag(true);
    }

    /**
     * Show log file content
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionViewLogFile()
    {
        \Includes\Utils\Operator::flush(
            '<pre>' . \Includes\Utils\FileManager::read(\XLite\Upgrade\Logger::getLogFile()) . '</pre>'
        );

        exit (0);
    }

    /**
     * Install add-on from marketplace
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionInstallAddonForce()
    {
        $data = array('moduleId' => \XLite\Core\Request::getInstance()->moduleId) + $this->getActionParamsCommon(true);
        $this->setReturnURL($this->buildURL('upgrade', 'install_addon', $data));
    }

    /**
     * Select core version for upgrade
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionSelectCoreVersion()
    {
        $version = \XLite\Core\Request::getInstance()->version;

        if ($version) {
            \XLite\Upgrade\Cell::getInstance()->setCoreVersion($version);
            \XLite\Upgrade\Cell::getInstance()->clear(false);

        } else {
            \XLite\Core\TopMessage::addError('Unexpected error: version value is not passed');
        }
    }

    // }}}
}
