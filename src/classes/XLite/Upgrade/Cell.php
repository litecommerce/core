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

namespace XLite\Upgrade;

/**
 * Cell
 *
 */
class Cell extends \XLite\Base\Singleton
{
    /**
     * Name of TmpVar
     */
    const CELL_NAME = 'upgradeCell';

    /**
     * Dedicated cell entry - LC core
     */
    const CORE_IDENTIFIER = '____CORE____';

    /**
     * Reserve of free disk space (5Mb)
     */
    const FREE_SPACE_RESERVE = 5000000;

    /**
     * List of cell entries
     *
     * @var array
     */
    protected $entries = array();

    /**
     * Core version to upgrade to
     *
     * @var string
     */
    protected $coreVersion;

    /**
     * List of cores received from marketplace (cache)
     *
     * @var array
     */
    protected $coreVersions;

    /**
     * List of incompatible modules
     *
     * @var array
     */
    protected $incompatibleModules = array();

    /**
     * List of error messages
     *
     * @var array
     */
    protected $errorMessages;

    /**
     * Flag to determine if upgrade is already performed
     *
     * @var boolean
     */
    protected $isUpgraded = false;


    // {{{ Public methods

    /**
     * Check if cell is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return ! (bool) array_filter($this->getErrorMessages());
    }

    /**
     * Getter
     *
     * @return array
     */
    public function getEntries()
    {
        return $this->entries;
    }

    /**
     * Return list of incompatible modules
     *
     * @param boolean $onlySelected Flag to return only the modules selected by admin OPTIONAL
     *
     * @return array
     */
    public function getIncompatibleModules($onlySelected = false)
    {
        $result = array();

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Module')->findByEnabled(true) as $module) {
            $key = $module->getMarketplaceID();

            if (isset($this->incompatibleModules[$key]) && (!$onlySelected || $this->incompatibleModules[$key]))  {
                $result[$key] = $module;
            }
        }

        return $result;
    }

    /**
     * Set statuses (enable/disable) for incompatible modules
     *
     * @param array $statuses List of statuses (<moduleID,status>)
     *
     * @return void
     */
    public function setIncompatibleModuleStatuses(array $statuses)
    {
        $this->incompatibleModules = array_intersect_key($statuses, $this->incompatibleModules)
            + $this->incompatibleModules;
    }

    /**
     * Return list of custom files
     *
     * @return array
     */
    public function getCustomFiles()
    {
        return array_merge(
            \Includes\Utils\ArrayManager::getObjectsArrayFieldValues($this->getEntries(), 'getCustomFiles')
        );
    }

    /**
     * Method to clean up cell
     *
     * @param boolean $clearCoreVersion Flag OPTIONAL
     * @param boolean $clearEntries     Flag OPTIONAL
     * @param boolean $collectEntries   Flag OPTIONAL
     *
     * @return void
     */
    public function clear($clearCoreVersion = true, $clearEntries = true, $collectEntries = true)
    {
        foreach ($this->getEntries() as $entry) {
            $entry->clear();
        }

        $this->incompatibleModules = array();
        $this->setUpgraded(false);

        if ($clearCoreVersion) {
            $this->setCoreVersion(null);
        }

        if ($clearEntries) {
            $this->entries = array();
        }

        if ($collectEntries) {
            $this->collectEntries();
        }
    }

    /**
     * Define version of core to upgrade to
     *
     * @param string $version Version to set
     *
     * @return void
     */
    public function setCoreVersion($version)
    {
        $this->coreVersion = $version;
    }

    /**
     * Set cell status
     *
     * @param boolean $value Flag
     *
     * @return void
     */
    public function setUpgraded($value)
    {
        $this->isUpgraded = (bool) $value;

        if ($this->isUpgraded) {
            foreach ($this->getEntries() as $entry) {
                $entry->setUpgraded();
            }
        }
    }

    /**
     * Add module to update/install
     *
     * @param \XLite\Model\Module $module Module model
     * @param boolean             $force  Flag to install modules OPTIONAL
     *
     * @return \XLite\Upgrade\Entry\Module\Marketplace
     */
    public function addMarketplaceModule(\XLite\Model\Module $module, $force = false)
    {
        if ($force) {
            $toUpgrade = $module;

        } else {
            $method = $this->isUpgrade() ? 'getModuleForUpgrade' : 'getModuleForUpdate';

            // "ForUpgrade" or "ForUpdate" method call
            $toUpgrade = \XLite\Core\Database::getRepo('\XLite\Model\Module')->$method(
                $module,
                $this->getCoreMajorVersion()
            );
        }

        $hash = $module->getActualName();

        if ($toUpgrade) {
            return $this->addEntry($hash, 'Module\Marketplace', array($module, $toUpgrade));

        } elseif ($module->getEnabled()) {
            $this->incompatibleModules[$module->getMarketplaceID()] = false;
        }
    }

    /**
     * Add module to update/install
     *
     * @param string $path Path to uploaded module pack
     *
     * @return \XLite\Upgrade\Entry\Module\Uploaded
     */
    public function addUploadedModule($path)
    {
        return $this->addEntry(md5($path), 'Module\Uploaded', array($path));
    }

    // }}}

    // {{{ Core version routines

    /**
     * Quick access to the "Core" entry
     *
     * @return string
     */
    public function getCoreMajorVersion()
    {
        return $this->callCoreEntryMethod('getMajorVersionNew') ?: \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Quick access to the "Core" entry
     *
     * @return string
     */
    public function getCoreMinorVersion()
    {
        return $this->callCoreEntryMethod('getMinorVersionNew') ?: \XLite::getInstance()->getMinorVersion();
    }

    /**
     * Quick access to the "Core" entry
     *
     * @return string
     */
    public function getCoreVersion()
    {
        return $this->callCoreEntryMethod('getVersionNew') ?: \XLite::getInstance()->getVersion();
    }

    /**
     * Get list of available kernel versions from the marketplace
     *
     * @return array
     */
    public function getCoreVersions()
    {
        if (!isset($this->coreVersions)) {
            $this->coreVersions = (array) \XLite\Core\Marketplace::getInstance()->getCores($this->getCacheTTL());
        }

        return $this->coreVersions;
    }

    /**
     * Check if we upgrade core major version
     *
     * @return boolean
     */
    public function isUpgrade()
    {
        return \XLite::getInstance()->checkVersion($this->getCoreMajorVersion(), '<');
    }

    /**
     * Helper
     *
     * @param string $method Name of method to call
     *
     * @return mixed
     */
    protected function callCoreEntryMethod($method)
    {
        $entry = \Includes\Utils\ArrayManager::getIndex($this->getEntries(), self::CORE_IDENTIFIER, true);

        // If core entry found, call method with the passed name on it
        return isset($entry) ? $entry->$method() : null;
    }

    // }}}

    // {{{ "Magic" methods

    /**
     * Save data in DB
     *
     * @return void
     */
    public function __destruct()
    {
        \XLite\Core\TmpVars::getInstance()->{self::CELL_NAME} = array(
            $this->getEntries(),
            $this->incompatibleModules,
            $this->isUpgraded(),
        );
    }

    /**
     * Protected constructor
     *
     * @return void
     */
    protected function __construct()
    {
        parent::__construct();

        // Upload addons info into the database
        \XLite\Core\Marketplace::getInstance()->saveAddonsList($this->getCacheTTL());

        list($entries, $incompatibleModules, $isUpgraded) = \XLite\Core\TmpVars::getInstance()->{self::CELL_NAME};

        if (is_array($entries)) {
            $this->entries = array_merge($this->entries, $entries);
            $this->incompatibleModules = $this->incompatibleModules + (array) $incompatibleModules;
            $this->setUpgraded(!empty($isUpgraded));

        } else {
            $this->collectEntries();
        }
    }

    /**
     * Return so called "short" TTL
     *
     * @return integer
     */
    protected function getCacheTTL()
    {
        return \XLite\Core\Marketplace::TTL_SHORT;
    }

    // }}}

    // {{{ Methods to collect entries

    /**
     * Check and add (if needed) upgrade entries
     *
     * @return void
     */
    protected function collectEntries()
    {
        if (!$this->isUpgraded()) {

            // :NOTE: do not change call order!
            $this->checkForCoreUpgrade();
            $this->checkForModulesUpgrade();
        }
    }

    /**
     * Check and add (if needed) core upgrade entry
     *
     * @return \XLite\Upgrade\Entry\Core
     */
    protected function checkForCoreUpgrade()
    {
        $majorVersion = $this->coreVersion ?: \XLite::getInstance()->getMajorVersion();
        $data = \Includes\Utils\ArrayManager::getIndex($this->getCoreVersions(), $majorVersion, true);

        if (is_array($data)) {
            return $this->addEntry(self::CORE_IDENTIFIER, 'Core', array_merge(array($majorVersion), $data));
        }
    }

    /**
     * Check and add (if needed) upgrade entries
     *
     * @return void
     */
    protected function checkForModulesUpgrade()
    {
        $cnd = new \XLite\Core\CommonCell();
        $cnd->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\Module')->search($cnd) as $module) {
            $this->addMarketplaceModule($module);
        }
    }

    /**
     * Common method to add entries
     *
     * @param string $index Index in the "entries" array
     * @param string $class Entry class name
     * @param array  $args  Constructor arguments OPTIONAL
     *
     * @return \XLite\Upgrade\Entry\AEntry
     */
    protected function addEntry($index, $class, array $args = array())
    {
        try {
            $entry = \Includes\Pattern\Factory::create('\XLite\Upgrade\Entry\\' . $class, $args);

        } catch (\Exception $exception) {
            $entry = null;
            \XLite\Upgrade\Logger::getInstance()->logError($exception->getMessage());
        }

        if (isset($entry)) {
            $this->entries[$index] = $entry;
        }

        return $entry;
    }

    // }}}

    // {{{ Errors handling

    /**
     * Return list of error messages
     *
     * @return array
     */
    public function getErrorMessages()
    {
        if (!isset($this->errorMessages)) {
            $this->errorMessages = array();

            if (!$this->isUnpacked()) {
                $this->errorMessages[self::CORE_IDENTIFIER] = $this->checkDiskFreeSpace();
            }

            $this->errorMessages = array_merge(
                $this->errorMessages,
                \Includes\Utils\ArrayManager::getObjectsArrayFieldValues($this->getEntries(), 'getErrorMessages')
            );

            $this->errorMessages = array_filter($this->errorMessages);
        }

        return $this->errorMessages;
    }

    /**
     * Check if there is enough disk free space.
     * Return message on error
     *
     * @return string
     */
    protected function checkDiskFreeSpace()
    {
        $message = null;

        $totalSize = \Includes\Utils\ArrayManager::sumObjectsArrayFieldValues($this->getEntries(), 'getPackSize');
        $freeSpace = max(0, \Includes\Utils\FileManager::getDiskFreeSpace(LC_DIR_TMP) - self::FREE_SPACE_RESERVE);

        if ($totalSize > $freeSpace) {
            $message = \XLite\Core\Translation::getInstance()->translate(
                'Not enough disk space. Required: {{req}} (+{{reserve}} reserve). Available: {{avail}}',
                array(
                    'req'     => \XLite\Core\Converter::formatFileSize($totalSize),
                    'reserve' => \XLite\Core\Converter::formatFileSize(self::FREE_SPACE_RESERVE),
                    'avail'   => \XLite\Core\Converter::formatFileSize($freeSpace),
                )
            );
        }

        return $message;
    }

    // }}}

    // {{{ Check cell status

    /**
     * Check if all entry packages were downloaded
     *
     * @return boolean
     */
    public function isDownloaded()
    {
        return $this->checkCellPackages(false);
    }

    /**
     * Check if all entry packages were unpacked
     *
     * @return boolean
     */
    public function isUnpacked()
    {
        return $this->checkCellPackages(true);
    }

    /**
     * Check if upgrade is already performed
     *
     * @return boolean
     */
    public function isUpgraded()
    {
        return $this->isUpgraded;
    }

    /**
     * Common method to check entry packages
     *
     * @param boolean $isUnpacked Check type
     *
     * @return boolean
     */
    protected function checkCellPackages($isUnpacked)
    {
        $list  = $this->getEntries();
        $count = count($list);

        if (0 < $count) {
            $callback = function (\XLite\Upgrade\Entry\AEntry $entry) use ($isUnpacked) {
                return $entry->{$isUnpacked ? 'isUnpacked' : 'isDownloaded'}();
            };

            $result = count(array_filter(array_map($callback, $list))) === $count;
        }

        return !empty($result);
    }

    // }}}

    // {{{ Download and unpack archives

    /**
     * Download all update packs
     *
     * @return boolean
     */
    public function downloadUpgradePacks()
    {
        return $this->manageEntryPackages(false);
    }

    /**
     * Unpack all archives
     *
     * @return boolean
     */
    public function unpackAll()
    {
        $result = false;

        if (!$this->isDownloaded()) {
            \XLite\Upgrade\Logger::getInstance()->logError('Trying to unpack non-downloaded archives');

        } else {
            $result = $this->manageEntryPackages(true);
        }

        return $result;
    }

    /**
     * Common method to manage entry packages
     *
     * @param boolean $isUnpack Operation type
     *
     * @return boolean
     */
    protected function manageEntryPackages($isUnpack)
    {
        foreach ($this->getEntries() as $entry) {
            $isUnpack ? $entry->unpack() : $entry->download();
        }

        return $isUnpack ? $this->isUnpacked() : $this->isDownloaded();
    }

    // }}}

    // {{{ Upgrade

    /**
     * Perform upgrade
     *
     * @param boolean $isTestMode       Flag OPTIONAL
     * @param array   $filesToOverwrite List of custom files to overwrite OPTIONAL
     *
     * @return boolean
     */
    public function upgrade($isTestMode = true, array $filesToOverwrite = array())
    {
        $result = false;

        if (!$this->isUnpacked()) {
            \XLite\Upgrade\Logger::getInstance()->logError(
                'Trying to perform upgrade while not all archives were unpacked'
            );

        } else {

            if (!$isTestMode) {
                $this->preloadLibraries();
            }

            $this->runHelpers('pre_upgrade', $isTestMode);

            foreach ($this->getEntries() as $entry) {
                $entry->upgrade($isTestMode, $filesToOverwrite);
            }

            $this->runHelpers('post_upgrade', $isTestMode);
            $result = $this->isValid();
        }

        return $result;
    }

    /**
     * Preload libraries
     *
     * @return void
     */
    protected function preloadLibraries()
    {
        // Preload lib directory
        $dirIterator = new \RecursiveDirectoryIterator(LC_DIR_LIB);
        $iterator    = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        $logLibDir = LC_DIR_LIB . 'Log' . LC_DS;

        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match('/\.php$/Ss', $filePath) && (false === stristr($filePath, $logLibDir))) {

                require_once $filePath;
            }
        }

        // Preload \Includes
        $dirIterator = new \RecursiveDirectoryIterator(LC_DIR_INCLUDES);
        $iterator    = new \RecursiveIteratorIterator($dirIterator, \RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($iterator as $filePath => $fileObject) {
            if (preg_match('/\.php$/Ss', $filePath) && !preg_match('/install/Ss', $filePath)) {
                require_once $filePath;
            }
        }

    }

    // }}}

    // {{{ So called upgrade helpers

    /**
     * Execute some methods
     *
     * @param string  $type       Helper type
     * @param boolean $isTestMode Flag OPTIONAL
     *
     * @return void
     */
    public function runHelpers($type, $isTestMode = false)
    {
        if (!$isTestMode) {
            foreach ($this->getEntries() as $entry) {
                $entry->runHelpers($type);
            }
        }
    }

    // }}}
}
