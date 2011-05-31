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
     * Module ID in database
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $moduleIDInstalled;

    /**
     * Module ID in database
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $moduleIDForUpgrade;

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
        return $this->getModuleInstalled()->getMajorVersion();
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
        return $this->getModuleInstalled()->getMinorVersion();
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
        return \XLite\Core\Marketplace::getInstance()->getAddonHash(
            $this->getModuleInstalled()->getMarketplaceID(),
            $this->getModuleInstalled()->getLicenseKey()
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
        $this->moduleIDInstalled  = $moduleInstalled->getModuleID();
        $this->moduleIDForUpgrade = $moduleForUpgrade->getModuleID();

        if (is_null($this->getModuleInstalled())) {
            \Includes\ErrorHandler::fireError(
                'Module with ID "' . $this->moduleIDInstalled . '" is not found in DB'
            );
        }

        if (is_null($this->getModuleForUpgrade()) || !$this->getModuleForUpgrade()->getMarketplaceID()) {
            \Includes\ErrorHandler::fireError(
                'Module with ID "' . $this->moduleIDInstalled . '" is not found in DB'
                . ' or has an invaid markeplace identifier'
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
        $list[] = 'moduleIDInstalled';
        $list[] = 'moduleIDForUpgrade';

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
        $this->setRepositoryPath(
            \XLite\Core\Marketplace::getInstance()->getAddonPack(
                $this->getModuleForUpgrade()->getMarketplaceID(),
                $this->getModuleForUpgrade()->getLicenseKey()
            )
        );

        $this->saveHashesForInstalledFiles();

        return parent::download();
    }

    /**
     * Search for module in DB
     *
     * @param integer $moduleID ID to search by
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModule($moduleID)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleID);
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleInstalled()
    {
        return $this->getModule($this->moduleIDInstalled);
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleForUpgrade()
    {
        return $this->getModule($this->moduleIDForUpgrade);
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

        // Do not enable already installed modules
        if (!isset($installed)) {
            $forUpgrade->setEnabled(true);
        }

        $forUpgrade->setInstalled(true);

        \XLite\Core\Database::getRepo('\XLite\Model\Module')->update($forUpgrade);

        if ($forUpgrade->getModuleID() !== $installed->getModuleID()) {
            \XLite\Core\Database::getRepo('\XLite\Model\Module')->delete($installed);

            $this->moduleIDInstalled = $forUpgrade->getModuleID();
        }
    }
}
