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
 * Distr 
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class Distr extends \XLite\Core\Pack\APack
{
    /**
     * Field names in metadata
     */
    const METADATA_FIELD_VERSION_MINOR = 'VersionMinor';
    const METADATA_FIELD_VERSION_MAJOR = 'VersionMajor';

    /**
     * List of directories which are not required in pack
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $dirsToExclude = array();

    /**
     * List of files which are not required in pack
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $filesToExclude = array();

    /**
     * List of exceptions
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $dirsToInclude = array();

    /**
     * List of exceptions
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $filesToInclude = array();

    /**
     * Saved value for filtering
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentPath;

    // {{{ Public methods

    /**
     * Constructor 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct()
    {
        $this->dirsToExclude[] = 'var';
        $this->dirsToExclude[] = 'files';
        $this->dirsToExclude[] = 'images';
        $this->dirsToExclude[] = 'sql';

        $this->filesToInclude[] = 'var' . LC_DS . '.htaccess';
        $this->filesToInclude[] = 'files' . LC_DS . '.htaccess';
        $this->filesToInclude[] = 'images' . LC_DS . '.htaccess';
        $this->filesToInclude[] = 'images' . LC_DS . 'spacer.gif';
        $this->filesToInclude[] = 'sql' . LC_DS . 'xlite_data.yaml';

        $this->filesToExclude[] = 'etc' . LC_DS . 'config.local.php';
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
        return 'LC-Distr-v' . \XLite::getInstance()->getVersion();
    }

    /**
     * Return iterator to walk through directories
     *
     * @return \Iterator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDirectoryIterator()
    {
        $result = new \Includes\Utils\FileFilter(LC_ROOT_DIR, null, \RecursiveIteratorIterator::SELF_FIRST);
        $result = $result->getIterator();
        $result->registerCallback(array($this, 'filterCoreFiles'));

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
            self::METADATA_FIELD_VERSION_MAJOR => \XLite::getInstance()->getMajorVersion(),
            self::METADATA_FIELD_VERSION_MINOR => \XLite::getInstance()->getMinorVersion(),
        );
    }

    // }}}

    // {{{ Auxiliary methods

    /**
     * Callback to filter files
     * 
     * @param \Includes\Utils\FileFilter\FilterIterator $iterator Directory iterator
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function filterCoreFiles(\Includes\Utils\FileFilter\FilterIterator $iterator)
    {
        // Relative path in LC root directory
        $path = \Includes\Utils\FileManager::getRelativePath($iterator->getPathname(), LC_ROOT_DIR);

        // Current forbidden directory iteration is over
        if (isset($this->currentPath) && dirname($path) === dirname($this->currentPath)) {
            $this->currentPath = null;
        }

        // New forbidden directory found
        if ($iterator->isDir() && in_array($path, $this->dirsToExclude)) {
            $this->currentPath = $path;
        }

        // One of the files or dirs lists
        $list = ($iterator->isDir() ? 'dirs' : 'files') . 'To' . (isset($this->currentPath) ? 'Include' : 'Exclude');

        // Check for (dis) allowed files and directories
        return isset($this->currentPath) xor !in_array($path, $this->$list);
    }

    // }}}
}
