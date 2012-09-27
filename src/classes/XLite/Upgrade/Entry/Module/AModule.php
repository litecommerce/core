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

namespace XLite\Upgrade\Entry\Module;

/**
 * AModule
 *
 */
abstract class AModule extends \XLite\Upgrade\Entry\AEntry
{
    /**
     * Update database records
     *
     * @return array
     */
    abstract protected function updateDBRecords();

    /**
     * Perform upgrade
     *
     * @param boolean    $isTestMode       Flag OPTIONAL
     * @param array|null $filesToOverwrite List of custom files to overwrite OPTIONAL
     *
     * @return void
     */
    public function upgrade($isTestMode = true, $filesToOverwrite = null)
    {
        parent::upgrade($isTestMode, $filesToOverwrite);

        if (!$isTestMode) {
            list($author, $name) = explode('\\', $this->getActualName());

            if (!$this->isValid()) {
                \Includes\SafeMode::markModuleAsUnsafe($author, $name);
            }

            // Load fixtures
            if (!$this->isInstalled()) {
                $yaml = \Includes\Utils\ModulesManager::getModuleYAMLFile($author, $name);

                if (\Includes\Utils\FileManager::isFileReadable($yaml)) {
                    \XLite\Core\Database::getInstance()->loadFixturesFromYaml($yaml);
                }
            }

            $this->updateDBRecords();
        }
    }

    /**
     * Return path where the upgrade helper scripts are placed
     *
     * @return string
     */            
    protected function getUpgradeHelperPath()
    {
        list($author, $name) = explode('\\', $this->getActualName());

        return \Includes\Utils\FileManager::getRelativePath(
            \Includes\Utils\ModulesManager::getAbsoluteDir($author, $name),
            LC_DIR_ROOT
        ) . LC_DS;
    }
}
