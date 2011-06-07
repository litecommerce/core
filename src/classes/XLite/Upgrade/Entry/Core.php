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

namespace XLite\Upgrade\Entry;

/**
 * Core
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Core extends \XLite\Upgrade\Entry\AEntry
{
    /**
     * Core major version
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $majorVersion;

    /**
     * Core minor version
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $minorVersion;

    /**
     * Core revision date
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $revisionDate;

    /**
     * Pack size (in bytes)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $size;

    /**
     * Return entry readable name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        return 'Core';
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
        return 'skins/admin/en/images/core_image.png';
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
        return \XLite::getInstance()->getMajorVersion();
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
        return \XLite::getInstance()->getMinorVersion();
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
        return $this->majorVersion;
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
        return $this->minorVersion;
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
        return $this->revisionDate;
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
        return 'LC team';
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
        return true;
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
        return true;
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
        return $this->size;
    }

    /**
     * Return module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return $this->getName();
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($majorVersion, $minorVersion, $revisionDate, $size)
    {
        if (!$this->checkMajorVersion($majorVersion) || !$this->checkMinorVersion($majorVersion, $minorVersion)) {
            \Includes\ErrorHandler::fireError(
                'Unallowed core version for upgrade: '
                . \Includes\Utils\Converter::composeVersion($majorVersion, $minorVersion)
            );
        }

        if ($revisionDate >= time()) {
            \Includes\ErrorHandler::fireError('Invalid core revision date: "' . date(DATE_RFC822) . '"');
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function download()
    {
        $this->setRepositoryPath(
            \XLite\Core\Marketplace::getInstance()->getCorePack(
                $this->getMajorVersionNew(),
                $this->getMinorVersionNew()
            )
        );

        $this->saveHashesForInstalledFiles();

        return parent::download();
    }

    /**
     * Check if version is allowed
     *
     * @param string $majorVersion Version to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkMinorVersion($majorVersion, $minorVersion)
    {
        return \XLite::getInstance()->checkVersion($majorVersion, '<')
            || version_compare(\XLite::getInstance()->getMinorVersion(), $minorVersion, '<');
    }
}
