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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Controller\Admin;

/**
 * Upgrade
 *
 */
class Upgrade extends \XLite\Controller\Admin\AAdmin
{
    // {{{ Common methods

    /**
     * Run controller
     *
     * @return void
     */
    protected function run()
    {
        // Clear all selection if you visit the "Available updates" page
        if ($this->isUpdate()) {
            \XLite\Upgrade\Cell::getInstance()->clear();
        }

        if (\XLite\Upgrade\Cell::getInstance()->isUpgraded()) {
            if ($this->isForce()) {
                $this->setReturnURL(
                    $this->buildURL(\XLite\Core\Request::getInstance()->redirect ?: 'addons_list_installed')
                );

            } else {
                \XLite\Upgrade\Cell::getInstance()->runHelpers('post_rebuild');
            }

            \XLite\Core\Marketplace::getInstance()->checkForUpdates(0);
            \XLite\Core\Marketplace::getInstance()->getCores(0);
            \XLite\Core\Marketplace::getInstance()->saveAddonsList(0);
        }

        parent::run();
    }

    // }}}

    // {{{ Methods for viewers

    /**
     * Return the current page title (for the content area)
     *
     * @return string
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
                $result = 'Upgrade to version {{version}}';

            } else {
                $result = 'Updates for your version ({{version}})';
            }

            $result = static::t($result, array('version' => $version));
        }

        return $result;
    }

    /**
     * Check if core major version is equal to the current one
     *
     * @return boolean
     */
    public function isUpdate()
    {
        return 'install_updates' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if current page is the core version selection dialog
     *
     * @return boolean
     */
    public function isCoreSelection()
    {
        return 'select_core_version' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if current page is the updates download dialog
     *
     * @return boolean
     */
    public function isDownload()
    {
        return 'download_updates' === \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if next step of upgrade id available
     *
     * @return boolean
     */
    public function isNextStepAvailable()
    {
        return \XLite\Upgrade\Cell::getInstance()->isValid();
    }

    /**
     * Return list of all core versions available
     *
     * @return array
     */
    public function getCoreVersionsList()
    {
        $result = \XLite\Upgrade\Cell::getInstance()->getCoreVersions();
        unset($result[\XLite::getInstance()->getMajorVersion()]);

        return $result;
    }

    /**
     * Check the flag in request
     *
     * @return boolean
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
     */
    protected function doActionInstallAddon()
    {
        $moduleId = \XLite\Core\Request::getInstance()->moduleId;
        $module   = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);

        if ($module) {

            if ($module->getFromMarketplace()) {
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
                    $message = 'unable to add module entry to the installation list: "{{name}}"';
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
     */
    protected function doActionUploadAddon()
    {
        $this->setReturnURL($this->buildURL('addons_list_installed'));

        $path = \Includes\Utils\FileManager::moveUploadedFile('modulePack');

        if ($path) {
            \XLite\Upgrade\Cell::getInstance()->clear(true, true, false);
            $entry = \XLite\Upgrade\Cell::getInstance()->addUploadedModule($path);

            if (!isset($entry)) {
                $message = 'unable to add module entry to the installation list: "{{path}}"';
                $this->showError(__FUNCTION__, $message, array('path' => $path));

            } elseif (\XLite::getInstance()->checkVersion($entry->getMajorVersionNew(), '!=')) {
                $this->showError(
                    __FUNCTION__,
                    'module version "{{module_version}}" is not equal to the core one ("{{core_version}}")',
                    array(
                        'module_version' => $entry->getMajorVersionNew(),
                        'core_version'   => \XLite::getInstance()->getMajorVersion(),
                    )
                );

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
     */
    protected function doActionDownload()
    {
        $this->setReturnURL($this->buildURL('upgrade'));

        if ($this->isNextStepAvailable()) {
            \Includes\Utils\Operator::showMessage('Downloading updates, please wait...');

            // Disable some modules (if needed)
            \XLite\Upgrade\Cell::getInstance()->setIncompatibleModuleStatuses(
                (array) \XLite\Core\Request::getInstance()->toDisable
            );

            if ($this->runStep('downloadUpgradePacks')) {
                $this->setReturnURL($this->buildURL('upgrade', 'unpack', $this->getActionParamsCommon()));

            } else {
                $this->showError(__FUNCTION__, 'not all upgrade packs were downloaded');
            }

        } else {
            $this->showWarning(__FUNCTION__, 'not ready to download packs. Please, try again');
        }
    }

    /**
     * Go to the upgrade third step
     *
     * @return void
     */
    protected function doActionUnpack()
    {
        $this->setReturnURL($this->buildURL('upgrade'));

        if (\XLite\Upgrade\Cell::getInstance()->isDownloaded()) {
            \Includes\Utils\Operator::showMessage('Unpacking archives, please wait...');

            if (!$this->runStep('unpackAll')) {
                $this->showError(__FUNCTION__, 'not all archives were unpacked');

                \XLite\Core\TopMessage::addError(
                    'Try to unpack them manually, and click <a href="'
                    . $this->buildURL('upgrade', 'check_integrity')
                    . '">this link</a>'
                );

            } elseif ($this->isNextStepAvailable()) {
                $this->setReturnURL($this->buildURL('upgrade', 'check_integrity', $this->getActionParamsCommon()));

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
     */
    protected function doActionCheckIntegrity()
    {
        $this->setReturnURL($this->buildURL('upgrade'));

        if (\XLite\Upgrade\Cell::getInstance()->isUnpacked()) {
            \Includes\Utils\Operator::showMessage('Checking integrity, please wait...');

            // Perform upgrade in test mode
            $this->runStep('upgrade', array(true));

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
     */
    protected function doActionInstallUpgrades()
    {
        $this->setReturnURL($this->buildURL('upgrade'));

        if (\XLite\Upgrade\Cell::getInstance()->isUnpacked()) {
            \Includes\Utils\Operator::showMessage('Installing updates, please wait...');

            // Perform upgrade
            $this->runStep('upgrade', array(false, $this->getFilesToOverWrite()));

            $modules = array();

            // Disable selected modules
            foreach (\XLite\Upgrade\Cell::getInstance()->getIncompatibleModules(true) as $module) {
                $module->setEnabled(false);
                $modules[] = $module;
            }

            \XLite\Core\Database::getRepo('\XLite\Model\Module')->updateInBatch($modules);

            if ($this->isForce()) {
                if ($this->isNextStepAvailable()) {
                    $target = 'installed';
                    $this->showInfo(null, 'Module has been successfully installed');

                } else {
                    $target = 'marketplace';
                    $this->showError(__FUNCTION__);
                }

                $this->setReturnURL(
                    $this->buildURL(
                        'upgrade',
                        '',
                        $this->getActionParamsCommon() + array('redirect' => 'addons_list_' . $target)
                    )
                );
            }

            // Set cell status
            \XLite\Upgrade\Cell::getInstance()->clear(true, false, false);
            \XLite\Upgrade\Cell::getInstance()->setUpgraded(true);

            // Rebuild cache
            \XLite::setCleanUpCacheFlag(true);

        } else {
            $this->showWarning(__FUNCTION__, 'unable to install: not all archives were unpacked. Please, try again');
        }
    }

    /**
     * Show log file content
     *
     * @return void
     */
    protected function doActionViewLogFile()
    {
        \Includes\Utils\Operator::flush(
            '<pre>' . \Includes\Utils\FileManager::read(\XLite\Upgrade\Logger::getInstance()->getLogFile()) . '</pre>'
        );

        exit (0);
    }

    /**
     * Install add-on from marketplace
     *
     * @return void
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
     */
    protected function doActionSelectCoreVersion()
    {
        $version = \XLite\Core\Request::getInstance()->version;

        if ($version) {
            \XLite\Upgrade\Cell::getInstance()->setCoreVersion($version);
            \XLite\Upgrade\Cell::getInstance()->clear(false);
            $this->setHardRedirect();

        } else {
            \XLite\Core\TopMessage::addError('Unexpected error: version value is not passed');
        }
    }

    /**
     * Run an upgrade step
     *
     * @param string $method Upgrade cell method to call
     * @param array  $params Call params OPTIONAL
     *
     * @return mixed
     */
    protected function runStep($method, array $params = array())
    {
        return \Includes\Utils\Operator::executeWithCustomMaxExecTime(
            \Includes\Utils\ConfigParser::getOptions(array('marketplace', 'upgrade_step_time_limit')),
            array(\XLite\Upgrade\Cell::getInstance(), $method),
            $params
        );
    }

    // }}}

    // {{{ Some auxiliary methods

    /**
     * Retrive list of files that must be overwritten by request for install upgrades
     *
     * @return array
     */
    protected function getFilesToOverWrite()
    {
        $allFilesPlain = array();

        foreach(\XLite\Upgrade\Cell::getInstance()->getCustomFiles() as $files) {
            $allFilesPlain = array_merge($allFilesPlain, $files);
        }

        return \Includes\Utils\ArrayManager::filterByKeys(
            $allFilesPlain,
            array_keys((array) \XLite\Core\Request::getInstance()->toRemain),
            true
        );
    }

    // }}}
}
