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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Includes_Utils
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace Includes\Utils;

/**
 * FileManager 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class FileManager extends \Includes\Utils\AUtils
{
    /**
     * Checks whether a file or directory exists
     *
     * @param string $file file name to check
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isExists($file)
    {
        return file_exists($file);
    }

    /**
     * Checks whether a file or directory is readable
     *
     * @param string $file file name to check
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isReadable($file)
    {
        return is_readable($file);
    }

    /**
     * Tells whether the filename is a regular file
     *
     * @param string $file file name to check
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isFile($file)
    {
        return static::isExists($file) && is_file($file);
    }

    /**
     * Tells whether the filename is a directory
     *
     * @param string $file dir name to check
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isDir($file)
    {
        return static::isExists($file) && is_dir($file);
    }

    /**
     * Check if file is readable 
     * 
     * @param string $file file to check
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isFileReadable($file)
    {
        return static::isFile($file) && static::isReadable($file);
    }

    /**
     * Check if dir is readable
     *
     * @param string $file dir to check
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isDirReadable($file)
    {
        return static::isDir($file) && static::isReadable($file);
    }

    /**
     * Return directory where a file is located
     * 
     * @param string $file File path
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getDir($file)
    {
        return dirname($file);
    }

    /**
     * Return real path
     * 
     * @param string $dir Path to prepare
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRealPath($dir)
    {
        return realpath($dir);
    }

    /**
     * Return relative path by an absolute one
     *
     * @param string $path      Path to convert
     * @param string $compareTo Base part of the path
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRelativePath($path, $compareTo)
    {
        return str_replace(static::getCanonicalDir($compareTo), '', static::getRealPath($path));
    }

    /**
     * Prepare file path
     *
     * @param string  $dir   Dir to prepare
     * @param boolean $check Call or not "realpath()"
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getCanonicalDir($dir, $check = true)
    {
        return \Includes\Utils\Converter::trimTrailingChars($check ? static::getRealPath($dir) : $dir, LC_DS) . LC_DS;
    }

    /**
     * Create directory
     *
     * @param string  $dir  Directory path
     * @param integer $mode Permissions
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function mkdir($dir, $mode = 0755)
    {
        return mkdir($dir, $mode) && static::chmod($dir, $mode);
    }

    /**
     * Create directories tree recursive
     * 
     * @param string  $dir  Directory path
     * @param integer $mode Permissions
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function mkdirRecursive($dir, $mode = 0755)
    {
        return static::isExists($dir) ?: 
            (static::mkdirRecursive(static::getDir($dir), $mode) && static::mkdir($dir, $mode));
    }

    /**
     * Remove directories tree recursive
     * 
     * @param string $dir directory path
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function unlinkRecursive($dir)
    {
        if (static::isDir($dir)) {

            $filter = new \Includes\Utils\FileFilter($dir, null, \RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($filter->getIterator() as $file) {
                $file->isDir() ? rmdir($file->getRealPath()) : static::delete($file->getRealPath());
            }

            // Unset is required to release directory 
            // and avoid 'Permission denied' warning on rmdir() on Windoows servers
            unset($filter);

            rmdir($dir);
        }
    }

    /**
     * Copy the whole directory tree
     * 
     * @param string $dirFrom Catalog from which files will be copied
     * @param string $dirTo   Catalog to which files will be copied
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function copyRecursive($dirFrom, $dirTo)
    {
        if (static::isDir($dirFrom)) {

            $dirFrom = static::getCanonicalDir($dirFrom);
            $dirTo   = static::getCanonicalDir($dirTo, false);

            $filter = new \Includes\Utils\FileFilter($dirFrom, null, \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($filter->getIterator() as $file) {
                $pathTo = $dirTo . static::getRelativePath($pathFrom = $file->getRealPath(), $dirFrom);
                $file->isDir() ? static::mkdirRecursive($pathTo) : copy($fromPath, $pathTo);
            }
        }
    }

    /**
     * Return hash of the file
     *
     * @param string $path file path
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getHash($path)
    {
        return static::isFileReadable($path) ? md5_file($path) : null;
    }

    /**
     * Get unique file name in the certain directory
     * 
     * @param string $dir  directory name
     * @param string $file file name
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getUniquePath($dir, $file)
    {
        $dir      = static::getCanonicalDir($dir, false);
        $pathinfo = pathinfo($file);
        $counter  = 1;

        while (static::isFile($path = $dir . $file)) {
            $file = $pathinfo['filename'] . '_' . $counter++ . '.' . $pathinfo['extension'];
        }

        return $path;
    }

    /**
     * Change file or directory permissions
     * 
     * @param string  $path File path 
     * @param integer $mode Permissions
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function chmod($path, $mode)
    {
        return chmod($path, $mode);
    }

    /**
     * Read data from a file
     *
     * @param string $path file path
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function read($path)
    {
        return static::isExists($path) ? file_get_contents($path) : null;
    }

    /**
     * Write data to a file
     * 
     * @param string $path  File path
     * @param string $data  Data to write
     * @param int    $mode  Permisions to set
     * @param int    $flags Some optional flags
     *  
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function write($path, $data, $mode = 0644, $flags = 0)
    {
        // Create directory if not exists
        static::isDir($dir = static::getDir($path)) ?: static::mkdirRecursive($dir);

        return (false !== file_put_contents($path, $data, $flags)) && static::chmod($path, $mode);
    }

    /**
     * Replace data to a file by pattern
     *
     * @param string $path    File path
     * @param string $data    Data to write
     * @param string $pattern Pattern to use for replacement
     * @param int    $mode    Permisions to set
     * @param int    $flags   Some optional flags
     *
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function replace($path, $data, $pattern, $mode = 0644, $flags = 0)
    {
        return static::write($path, preg_replace($pattern, $data, static::read($path)), $mode, $flags);
    }

    /**
     * Delete file
     * 
     * @param string $path file path
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function delete($path)
    {
        return !static::isExists($path) ?: unlink($path);
    }
}
