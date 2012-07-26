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

namespace XLite\Upgrade\Entry;

/**
 * Core
 *
 */
class Core extends \XLite\Upgrade\Entry\AEntry
{
    /**
     * Core major version
     *
     * @var string
     */
    protected $majorVersion;

    /**
     * Core minor version
     *
     * @var string
     */
    protected $minorVersion;

    /**
     * Core revision date
     *
     * @var integer
     */
    protected $revisionDate;

    /**
     * Pack size (in bytes)
     *
     * @var integer
     */
    protected $size;

    /**
     * Return entry readable name
     *
     * @return string
     */
    public function getName()
    {
        return 'Core';
    }

    /**
     * Return icon URL
     *
     * @return string
     */
    public function getIconURL()
    {
        return 'skins/admin/en/images/core_image.png';
    }

    /**
     * Return entry old major version
     *
     * @return string
     */
    public function getMajorVersionOld()
    {
        return \XLite::getInstance()->getMajorVersion();
    }

    /**
     * Return entry old minor version
     *
     * @return string
     */
    public function getMinorVersionOld()
    {
        return \XLite::getInstance()->getMinorVersion();
    }

    /**
     * Return entry new major version
     *
     * @return string
     */
    public function getMajorVersionNew()
    {
        return $this->majorVersion;
    }

    /**
     * Return entry new minor version
     *
     * @return string
     */
    public function getMinorVersionNew()
    {
        return $this->minorVersion;
    }

    /**
     * Return entry revision date
     *
     * @return integer
     */
    public function getRevisionDate()
    {
        return $this->revisionDate;
    }

    /**
     * Return module author readable name
     *
     * @return string
     */
    public function getAuthor()
    {
        return 'LC team';
    }

    /**
     * Check if module is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return true;
    }

    /**
     * Check if module is installed
     *
     * @return boolean
     */
    public function isInstalled()
    {
        return true;
    }

    /**
     * Return entry pack size
     *
     * @return integer
     */
    public function getPackSize()
    {
        return $this->size;
    }

    /**
     * Return module actual name
     *
     * @return string
     */
    public function getActualName()
    {
        return $this->getName();
    }

    /**
     * Download hashes for current version
     *
     * @return array
     */
    protected function loadHashesForInstalledFiles()
    {
        return \XLite\Core\Marketplace::getInstance()->getCoreHash(
            $this->getMajorVersionOld(),
            $this->getMinorVersionOld()
        );
    }

    /**
     * Constructor
     *
     * @param string  $majorVersion Core major version
     * @param string  $minorVersion Core minor version
     * @param integer $revisionDate Core revison date
     * @param integer $size         Pack size
     *
     * @return void
     */
    public function __construct($majorVersion, $minorVersion, $revisionDate, $size)
    {
        if (!$this->checkMajorVersion($majorVersion) || !$this->checkMinorVersion($majorVersion, $minorVersion)) {
            $version = \Includes\Utils\Converter::composeVersion($majorVersion, $minorVersion);
            \Includes\ErrorHandler::fireError('Unallowed core version for upgrade: ' . $version);
        }

        if ($revisionDate >= time()) {
            \Includes\ErrorHandler::fireError('Invalid core revision date: "' . date(DATE_RFC822, $revisionDate) . '"');
        }

        $this->majorVersion = $majorVersion;
        $this->minorVersion = $minorVersion;
        $this->revisionDate = $revisionDate;
        $this->size         = $size;

        parent::__construct();
    }

    /**
     * Names of variables to serialize
     *
     * @return array
     */
    public function __sleep()
    {
        $list = parent::__sleep();
        $list[] = 'majorVersion';
        $list[] = 'minorVersion';
        $list[] = 'revisionDate';
        $list[] = 'size';

        return $list;
    }

    /**
     * Download package
     *
     * @return boolean
     */
    public function download()
    {
        $result = false;

        $majorVersion = $this->getMajorVersionNew();
        $minorVersion = $this->getMinorVersionNew();

        $path   = \XLite\Core\Marketplace::getInstance()->getCorePack($majorVersion, $minorVersion);
        $params = array('major' => $majorVersion, 'minor' => $minorVersion);

        if (isset($path)) {
            $this->addFileInfoMessage('Core pack (v.{{major}}.{{minor}}) is received', $path, true, $params);

            $this->setRepositoryPath($path);
            $this->saveHashesForInstalledFiles();

            $result = parent::download();

        } else {
            $this->addFileErrorMessage('Core pack (v.{{major}}.{{minor}}) is not received', $path, true, $params);
        }

        return $result;
    }

    /**
     * Return path where the upgrade helper scripts are placed
     *
     * @return string
     */
    protected function getUpgradeHelperPath()
    {
        return '';
    }

    /**
     * Check if version is allowed
     *
     * @param string $majorVersion Version to check
     *
     * @return boolean
     */
    protected function checkMajorVersion($majorVersion)
    {
        return \XLite::getInstance()->checkVersion($majorVersion, '<=');
    }

    /**
     * Check if version is allowed
     *
     * @param string $majorVersion Version to check
     * @param string $minorVersion Version to check
     *
     * @return boolean
     */
    protected function checkMinorVersion($majorVersion, $minorVersion)
    {
        return \XLite::getInstance()->checkVersion($majorVersion, '<')
            || version_compare(\XLite::getInstance()->getMinorVersion(), $minorVersion, '<');
    }
}
