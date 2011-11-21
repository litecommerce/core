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

namespace XLite\Upgrade\Entry\Module;

/**
 * Marketplace
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Marketplace extends \XLite\Upgrade\Entry\Module\AModule
{
    /**
     * Identifier for installed module
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $moduleInfoInstalled;

    /**
     * Identifier for upgrade module
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $moduleInfoForUpgrade;

    /**
     * Old major version (cache)
     *
     * :WARNING: do not remove this variable:
     * it's required for the proper upgrade process
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $majorVersionOld;

    /**
     * Old minor version (cache)
     *
     * :WARNING: do not remove this variable:
     * it's required for the proper upgrade process
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $minorVersionOld;

    /**
     * Return module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return $this->getModuleInstalled()->getActualName();
    }

    /**
     * Return entry readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return $this->getModuleForUpgrade()->getModuleName();
    }

    /**
     * Return icon URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getIconURL()
    {
        return $this->getModuleForUpgrade()->getIconURL();
    }

    /**
     * Return entry old major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersionOld()
    {
        if (!isset($this->majorVersionOld)) {
            $this->majorVersionOld = $this->getModuleInstalled()->getMajorVersion();
        }

        return $this->majorVersionOld;
    }

    /**
     * Return entry old minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersionOld()
    {
        if (!isset($this->minorVersionOld)) {
            $this->minorVersionOld = $this->getModuleInstalled()->getMinorVersion();
        }

        return $this->minorVersionOld;
    }

    /**
     * Return entry new major version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMajorVersionNew()
    {
        return $this->getModuleForUpgrade()->getMajorVersion();
    }

    /**
     * Return entry new minor version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMinorVersionNew()
    {
        return $this->getModuleForUpgrade()->getMinorVersion();
    }

    /**
     * Return entry revision date
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRevisionDate()
    {
        $this->getModuleForUpgrade()->getRevisionDate();
    }

    /**
     * Return module author readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getAuthor()
    {
        return $this->getModuleForUpgrade()->getAuthorName();
    }

    /**
     * Check if module is enabled
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isEnabled()
    {
        return (bool) $this->getModuleInstalled()->getEnabled();
    }

    /**
     * Check if module is installed
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isInstalled()
    {
        return (bool) $this->getModuleInstalled()->getInstalled();
    }

    /**
     * Return entry pack size
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPackSize()
    {
        return $this->getModuleForUpgrade()->getPackSize();
    }

    /**
     * Download hashes for current version
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function loadHashesForInstalledFiles()
    {
        $licenseKey = $this->getModuleForUpgrade()->getLicenseKey();

        return \XLite\Core\Marketplace::getInstance()->getAddonHash(
            $this->getModuleInstalled()->getMarketplaceID(),
            $licenseKey ? $licenseKey->getKeyValue() : null
        );
    }

    /**
     * Constructor
     *
     * @param \XLite\Model\Module $moduleInstalled  Module model object
     * @param \XLite\Model\Module $moduleForUpgrade Module model object
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\XLite\Model\Module $moduleInstalled, \XLite\Model\Module $moduleForUpgrade)
    {
        $this->moduleInfoInstalled  = $this->getPreparedModuleInfo($moduleInstalled, false);
        $this->moduleInfoForUpgrade = $this->getPreparedModuleInfo($moduleForUpgrade, true);

        if (is_null($this->getModuleInstalled())) {
            \Includes\ErrorHandler::fireError(
                'Module ["' . implode('", "', $this->moduleInfoInstalled) . '"] is not found in DB'
            );
        }

        if (is_null($this->getModuleForUpgrade())) {
            \Includes\ErrorHandler::fireError(
                'Module ["' . implode('", "', $this->moduleInfoForUpgrade) . '"] is not found in DB'
                . ' or is not a marketplace module'
            );
        }

        parent::__construct();
    }

    /**
     * Names of variables to serialize
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __sleep()
    {
        $list = parent::__sleep();
        $list[] = 'moduleInfoInstalled';
        $list[] = 'moduleInfoForUpgrade';

        return $list;
    }

    /**
     * Download package
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function download()
    {
        $result = false;
        $licenseKey = $this->getModuleForUpgrade()->getLicenseKey();

        $path = \XLite\Core\Marketplace::getInstance()->getAddonPack(
            $this->getModuleForUpgrade()->getMarketplaceID(),
            $licenseKey ? $licenseKey->getKeyValue() : null
        );
        
        $params = array('name' => $this->getActualName());

        if (isset($path)) {
            $this->addFileInfoMessage('Module pack ("{{name}}") is recieved', $path, true, $params);

            $this->setRepositoryPath($path);
            $this->saveHashesForInstalledFiles();

            $result = parent::download();

        } else {
            $this->addFileErrorMessage(
                'Module pack ("{{name}}") is not recieved',
                \XLite\Core\Marketplace::getInstance()->getError(),
                true,
                $params
            );
        }

        return $result;
    }

    /**
     * Prepare and return module identity data
     *
     * @param \XLite\Model\Module $module          Module to get info
     * @param boolean             $fromMarketplace Flag
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.6
     */
    protected function getPreparedModuleInfo(\XLite\Model\Module $module, $fromMarketplace)
    {
        // :WARNING: do not change the summands order:
        // it's important for the "updateDBRecords()" method
        return array('fromMarketplace' => $fromMarketplace) + $module->getIdentityData();
    }

    /**
     * Search for module in DB
     *
     * @param array $moduleInfo Info to search by
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule(array $moduleInfo)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->findOneBy($moduleInfo);
    }

    /**
     * Alias
     *
     * :WARNING: do not cache this object: identity info may be changed
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleInstalled()
    {
        return $this->getModule($this->moduleInfoInstalled) ?: $this->getModuleForUpgrade();
    }

    /**
     * Alias
     *
     * :WARNING: do not cache this object: identity info may be changed
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleForUpgrade()
    {
        return $this->getModule($this->moduleInfoForUpgrade);
    }

    /**
     * Update database records
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateDBRecords()
    {
        $forUpgrade = $this->getModuleForUpgrade();
        $installed  = $this->getModuleInstalled();

        if ($forUpgrade->getIdentityData() !== $installed->getIdentityData()) {
            $forUpgrade->setEnabled($installed->getEnabled());

            \XLite\Core\Database::getRepo('\XLite\Model\Module')->delete($installed);

            $this->moduleInfoInstalled = $this->getPreparedModuleInfo($forUpgrade, false);

        } else {
            $forUpgrade->setEnabled(true);
        }

        $forUpgrade->setInstalled(true);
        $forUpgrade->setFromMarketplace(false);
        $this->moduleInfoForUpgrade['fromMarketplace'] = false;

        \XLite\Core\Database::getRepo('\XLite\Model\Module')->update($forUpgrade);
    }
}
