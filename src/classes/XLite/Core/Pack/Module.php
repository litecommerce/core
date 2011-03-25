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
 * @since     3.0.0
 */

namespace XLite\Core\Pack;

/**
 * Module 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Module extends \XLite\Core\Pack\APack
{
    /**
     * Field names in metadata
     */
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
     * @var    \XLite\Model\Module
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $module;

    // {{{ Public methods

    /**
     * Constructor 
     * 
     * @param \XLite\Model\Module $module Current module
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(\XLite\Model\Module $module)
    {
        $this->module = $module;
    }

    /**
     * Return pack name
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getName()
    {
        // It's the fix for PHAR::compress(): it's triming dots in file names
        return str_replace('\\', '-', $this->module->getActualName()) 
            . '-v' . str_replace('.', '_', $this->module->__call('getVersion'));
    }

    /**
     * Return iterator to walk through directories
     *
     * @return \AppendIterator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMetadata()
    {
        return parent::getMetadata() + array(
            self::METADATA_FIELD_VERSION_MAJOR => $this->module->__call('getMajorVersion'),
            self::METADATA_FIELD_VERSION_MINOR => $this->module->__call('getMinorVersion'),
            self::METADATA_FIELD_NAME          => $this->module->__call('getModuleName'),
            self::METADATA_FIELD_AUTHOR        => $this->module->__call('getAuthorName'),
            self::METADATA_FIELD_ICON_LINK     => $this->module->__call('getIconURL'),
            self::METADATA_FIELD_DESCRIPTION   => $this->module->__call('getDescription'),
            self::METADATA_FIELD_DEPENDENCIES  => $this->module->__call('getDependencies'),
        );
    }

    // }}}

    // {{{ Directories

    /**
     * Return list of module directories
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDirs()
    {
        return array_merge($this->getClassDirs(), $this->getSkinDirs());
    }

    /**
     * Return list of module directories which contain class files
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassDirs()
    {
        return array($this->module->getRootDirectory());
    }

    /**
     * Return list of module directories which contain templates
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSkinDirs()
    {
        $result = array();

        foreach (\XLite\Core\Layout::getInstance()->getSkinsAll() as $interface => $tmp) {
            $result = array_merge($result, \XLite\Core\Layout::getInstance()->getSkinPaths($interface));
        }

        foreach ($result as $key => &$data) {
            $path = $data['fs'] . LC_DS . 'modules' . LC_DS . $this->module->getPath();

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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDirectorySPLIterator($dir)
    {
        return new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
    }

    // }}}
}
