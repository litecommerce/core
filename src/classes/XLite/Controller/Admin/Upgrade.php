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
 * Upgrade 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Upgrade extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Initialize controller
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function init()
    {
        parent::init();

        // Clear all selection if you visit the "Available updates" page
        if ($this->isUpdate()) {
            \XLite\Upgrade\Cell::getInstance()->clear();
        }
    }

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
                \XLite\Upgrade\Cell::getInstance()->clear();
                \XLite\Upgrade\Cell::getInstance()->addMarketplaceModule($module, true);
            } else {
                \XLite\Core\TopMessage::getInstance()->addError('Trying to install non-marketplace module');
            }

        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Invalid module ID passed - "' . $moduleId . '"');
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
        $path = \Includes\Utils\FileManager::moveUploadedFile('modulePack');

        if ($path) {
            \XLite\Upgrade\Cell::getInstance()->clear();
            \XLite\Upgrade\Cell::getInstance()->addUploadedModule($path);
        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Unable to upload module');
        }
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
            \XLite\Core\TopMessage::getInstance()->addError('Unexpected error: version value is not passed');
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
        // :DEVCODE: to remove
        \Includes\Utils\Operator::showMessage('Downloading updates, please wait...');

        // Disable some modules (if needed)
        $this->doActionDisableIncompatibleModules();

        foreach (\XLite\Upgrade\Cell::getInstance()->getEntries() as $entry) {
            $path = $this->unpack($entry);

            if ($path) {
                $entry->setRepositoryPath($path);
            } else {
                break;
            }
        }

        if (!\XLite\Upgrade\Cell::getInstance()->isDownloaded()) {
            \XLite\Core\TopMessage::getInstance()->addError('Not all upgrade entries were downloaded');
        }

        $this->setReturnURL($this->buildURL('upgrade'));
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
        echo 'Installing... (not ready yet)'; die;
    }

    /**
     * Disable some modules
     *
     * :NOTE: this action handler is not called by the dispatcher (only manually)
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function doActionDisableIncompatibleModules()
    {
        foreach ((array) \XLite\Core\Request::getInstance()->toDisable as $moduleID => $value) {
            // :TODO: find a way for modules lazy disabling
        }
    }

    // }}}

    // {{{ PHAR (un)packing routines

    /**
     * Save archive in temporary directory and unpack it
     *
     * @param string $source    Archive content
     * @param string $extension File extension OPTIONAL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function unpack(\XLite\Upgrade\Entry\AEntry $entry, $extension = null)
    {
        $path   = null;
        $source = $entry->getSource();

        if (!empty($source)) {

            // Check and set extension
            if (!isset($extension)) {
                $extension = \Includes\Utils\PHARManager::getExtension() ?: 'tar';
            }

            // Get unique file name
            $file = tempnam($this->getTempDir(), 'phr');

            // Remove temporary file and add the extension
            if ($file) {
                \Includes\Utils\FileManager::delete($file);
                 $file .= '.' . $extension;
            }

            // Save data into created file
            if ($file && \Includes\Utils\FileManager::write($file, $source)) {

                // Extract archive files into a new directory
                $path = \Includes\Utils\PHARManager::unpack($file, $this->getTempDir());

                if ($path) {
                    \Includes\Utils\FileManager::delete($file);
                } else {
                    $error = 'unable to extract archive files';
                }

            } else {
                $error = 'unable to save archive to the temp directory';
            }

        } else {
            $error = 'an empty package recieved';
        }

        if (!empty($error)) {
            \XLite\Core\TopMessage::getInstance()->addError('[' . $entry->getName() . ']: ' . $error);
        }

        return $path;
    }

    /**
     * Return dir to temporary save and unpack archives
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTempDir()
    {
        return LC_DIR_TMP;
    }

    // }}}
}
