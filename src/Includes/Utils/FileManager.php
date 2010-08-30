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
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class FileManager extends AUtils
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
     * Create directories tree recursive
     * 
     * @param string $dir directory path
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function mkdirRecursive($dir, $mode = 0755)
    {
        if (!file_exists($dir)) {
            mkdir($dir, $mode, true);   
        }
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
            $iterator = \Includes\Utils\FileFilter::getIterator(
                $dir,
                \RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($iterator as $file) {
                $file->isDir() ? rmdir($file->getPathname()) : unlink($file->getPathname());
            }

            rmdir($dir);
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
        $dir = \Includes\Utils\Converter::trimTrailingChars($dir, LC_DS) . LC_DS;
        $pathinfo = pathinfo($file);
        $counter = 1;

        $path = $dir . $file;
        while (static::isFile($path)) {
            $file = $pathinfo['filename'] . '_' . $counter++ . '.' . $pathinfo['extension'];
            $path = $dir . $file;
        }

        return $path;
    }

    /**
     * Return relative path by an absolute one
     * 
     * @param string $path      path to convert
     * @param string $compareTo base part of the path
     * @param string $extension file extension
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getRelativePath($path, $compareTo, $extension = 'php')
    {
        return preg_replace(
            '/^' . preg_quote($compareTo, '/') . '(.*)\.' . $extension . '$/i',
            '$1.' . $extension,
            $path
        );
    }
}
