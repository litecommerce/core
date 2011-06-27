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

namespace XLite\Core\Pack;

/**
 * Module
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Module extends \XLite\Core\Pack\APack
{
    /**
     * Field names in metadata
     */
    const METADATA_FIELD_ACTUAL_NAME   = 'ActualName';
    const METADATA_FIELD_VERSION_MINOR = 'VersionMinor';
    const METADATA_FIELD_VERSION_MAJOR = 'VersionMajor';
    const METADATA_FIELD_NAME          = 'Name';
    const METADATA_FIELD_AUTHOR        = 'Author';
    const METADATA_FIELD_ICON_LINK     = 'IconLink';
    const METADATA_FIELD_DESCRIPTION   = 'Description';
    const METADATA_FIELD_DEPENDENCIES  = 'Dependencies';

    /**
     * Current module
     *
     * @var   \XLite\Model\Module
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $module;

    // {{{ Public methods

    /**
     * Constructor
     *
     * @param \XLite\Model\Module $module Current module
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(\XLite\Model\Module $module)
    {
        $this->module = $module;
    }

    /**
     * Return pack name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getName()
    {
        // It's the fix for PHAR::compress(): it's triming dots in file names
        return str_replace('\\', '-', $this->module->getActualName())
            . '-v' . str_replace('.', '_', $this->module->callModuleMethod('getVersion'));
    }

    /**
     * Return iterator to walk through directories
     *
     * @return \AppendIterator
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDirectoryIterator()
    {
        $result = new \AppendIterator();

        foreach ($this->getDirs() as $dir) {
            $result->append($this->getDirectorySPLIterator($dir));
        }

        return $result;
    }

    /**
     * Return pack metadata
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMetadata()
    {
        return parent::getMetadata() + array(
            self::METADATA_FIELD_ACTUAL_NAME   => $this->module->getActualName(),
            self::METADATA_FIELD_VERSION_MAJOR => $this->module->callModuleMethod('getMajorVersion'),
            self::METADATA_FIELD_VERSION_MINOR => $this->module->callModuleMethod('getMinorVersion'),
            self::METADATA_FIELD_NAME          => $this->module->callModuleMethod('getModuleName'),
            self::METADATA_FIELD_AUTHOR        => $this->module->callModuleMethod('getAuthorName'),
            self::METADATA_FIELD_ICON_LINK     => $this->module->callModuleMethod('getIconURL'),
            self::METADATA_FIELD_DESCRIPTION   => $this->module->callModuleMethod('getDescription'),
            self::METADATA_FIELD_DEPENDENCIES  => $this->module->callModuleMethod('getDependencies'),
        );
    }

    // }}}

    // {{{ Directories

    /**
     * Return list of module directories
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDirs()
    {
        return array_merge($this->getClassDirs(), $this->getSkinDirs());
    }

    /**
     * Return list of module directories which contain class files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClassDirs()
    {
        return array(
            \Includes\Utils\ModulesManager::getRootDirectory($this->module->getAuthor(), $this->module->getName())
        );
    }

    /**
     * Return list of module directories which contain templates
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSkinDirs()
    {
        $result = array();

        foreach (\XLite\Core\Layout::getInstance()->getSkinsAll() as $interface => $tmp) {
            $result = array_merge($result, \XLite\Core\Layout::getInstance()->getSkinPaths($interface));
        }

        foreach ($result as $key => &$data) {
            $path = \Includes\Utils\ModulesManager::getRelativeDir($this->module->getAuthor(), $this->module->getName());
            $path = $data['fs'] . LC_DS . 'modules' . LC_DS . $path;

            if (\Includes\Utils\FileManager::isDirReadable($path)) {
                $data = $path;

            } else {
                unset($result[$key]);
            }
        }

        return array_values(array_unique($result));
    }

    /**
     * Return iterator for a directory
     *
     * @param string $dir Full directory path
     *
     * @return \RecursiveIteratorIterator
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDirectorySPLIterator($dir)
    {
        return new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
    }

    // }}}
}
