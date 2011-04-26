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
class Upgrade extends \XLite\Controller\Admin\Base\PackManager
{
    /**
     * List of cores recieved from marketplace (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $coreVersions;


    // {{{ Cache management

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

        // Upload addons info into the database
        \XLite\Core\Marketplace::getInstance()->saveAddonsList($this->getCacheTTL());
    }

    /**
     * Return so called "short" TTL
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCacheTTL()
    {
        return \XLite\Core\Marketplace::TTL_SHORT;
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

        } else {
            $version = $this->getCoreMajorVersionForUpdate();

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
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isUpgrade()
    {
        return 'install_updates' !== \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Check if current page is the core version selection dialog
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCoreSelection()
    {
        return 'select_core_version' === \XLite\Core\Request::getInstance()->mode;
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
        return $this->isUpgrade() ? 'Upgrade' : 'Updates available';
    }

    // }}}

    // {{{ Versions

    /**
     * Get list of available kernel versions from the marketplace
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAvailableCoreVersions()
    {
        if (!isset($this->coreVersions)) {
            $this->coreVersions = (array) \XLite\Core\Marketplace::getInstance()->getCoreVersions($this->getCacheTTL());
        }

        return $this->coreVersions;
    }

    /**
     * Check if we can upgrade to the version
     * 
     * @param string $version Version to check
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCoreVersionAvailable($version)
    {
        $result = isset($version);

        if ($result) {
            $list   = $this->getAvailableCoreVersions();
            $result = isset($list[$version]);
        }

        return $result;
    }

    /**
     * Return major version of core to update/upgrade
     *
     * :TODO: remove if not used
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCoreMajorVersionForUpdate()
    {
        $version = \XLite\Core\Request::getInstance()->version;

        return $this->isCoreVersionAvailable($version) ? $version : \XLite::getInstance()->getMajorVersion();
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
                \XLite\Upgrade\Cell::getInstance()->addMarketplaceModule($module);
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
            \XLite\Upgrade\Cell::getInstance()->addUploadedModule($path);
        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Unable to upload module');
        }
    }

    // }}}






























    // {{{ Methods for viewers

    /**
     * List of cores recieved from marketplace (cache)
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
//    protected $coreVersions;

    /**
     * Check if core major version 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function isUpgrade()
    {
        return 'install_updates' !== \XLite\Core\Request::getInstance()->mode;
    }

    /**
     * Return major version of core to update/upgrade
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getCoreMajorVersionForUpdate()
    {
        $result = \XLite\Core\Request::getInstance()->version;

        if (isset($result)) {
            foreach ($this->getAvailableCoreVersions() as $data) {
                $data = $data[\XLite\Core\Marketplace::RESPONSE_FIELD_CORE_VERSION];

                if (version_compare($data[\XLite\Core\Marketplace::FIELD_VERSION_MAJOR], $result, '=')) {
                    $found = true;
                    break;
                }
            }
        }

        return (!empty($found) && !empty($result)) ? $result : \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Return minor version of core to update/upgrade
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getCoreMinorVersionForUpdate()
    {
        $result = null;

        foreach ($this->getAvailableCoreVersions() as $data) {
            $data = $data[\XLite\Core\Marketplace::RESPONSE_FIELD_CORE_VERSION];
            $majorVersion = $data[\XLite\Core\Marketplace::FIELD_VERSION_MAJOR];

            if (version_compare($majorVersion, $this->getCoreMajorVersionForUpdate(), '=')) {
                $minorVersion = $data[\XLite\Core\Marketplace::FIELD_VERSION_MINOR];

                if (!isset($result) || version_compare($minorVersion, $result, '>')) {
                    $result = $minorVersion;
                }
            }
        }

        return $result;
    }

    /**
     * Returns list of upgradable modules
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getModulesForUpdate()
    {
        $result = array();

        $cnd = new \XLite\Core\CommonCell();
        $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd) as $module) {
            $result[] = $this->getModuleForUpdate($module);
        }

        return array_filter($result);
    }

    /**
     * Search for installed module
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getModuleInstalled(\XLite\Model\Module $module)
    {
        return $module->getRepository()->getModuleInstalled($module);
    }

    /**
     * Method to get module for update/upgrade
     *
     * @param \XLite\Model\Module $module Currently installed module version
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getModuleForUpdate(\XLite\Model\Module $module)
    {
        $version = $this->getCoreMajorVersionForUpdate();
        $method  = \XLite::getInstance()->checkVersion($version, '<') ? 'getModuleForUpgrade' : 'getModuleForUpdate';

        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->$method($module, $version);
    }

    // }}}

    // {{{ Marketplace-related methods

    /**
     * Get list of available kernel versions from the marketplace
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getAvailableCoreVersions()
    {
        if (!isset($this->coreVersions)) {
            $this->coreVersions = (array) \XLite\Core\Marketplace::getInstance()->getCoreVersions($this->getCacheTTL());
        }

        return $this->coreVersions;
    }

    /**
     * Return so called "short" TTL
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getCacheTTL()
    {
        return \XLite\Core\Marketplace::TTL_SHORT;
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
/*    protected function doActionInstallAddon()
    {
        $moduleId = \XLite\Core\Request::getInstance()->moduleId;
        $module   = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);

        if ($module) {

            if ($module->getMarketplaceID()) {
                \XLite\Upgrade\Cell::getInstance()->addMarketplaceModule($module);
            } else {
                \XLite\Core\TopMessage::getInstance()->addError('Trying to install non-marketplace module');
            }

        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Invalid module ID passed - "' . $moduleId . '"');
        }

        var_dump(\XLite\Upgrade\Cell::getInstance());die;
    }

    /**
     * Install uploaded add-on
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function doActionUploadAddon()
    {
        $path = \Includes\Utils\FileManager::moveUploadedFile('modulePack');

        if ($path) {
            \XLite\Upgrade\Cell::getInstance()->addUploadedModule($path);
        } else {
            \XLite\Core\TopMessage::getInstance()->addError('Unable to upload module');
        }

        var_dump(\XLite\Upgrade\Cell::getInstance());die;
    }*/

    // }}}
}
