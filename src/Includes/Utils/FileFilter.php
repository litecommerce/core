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
 * @subpackage Include_Utils
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
 * FileFilter 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class FileFilter extends AUtils
{
    /**
     * Return the directory iterator
     * 
     * @param string $dir  folder to iterate
     * @param int    $mode iteration mode
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getUnfilteredIterator($dir, $mode)
    {
        return new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir), $mode);
    }


    /**
     * Return the directory iterator
     * 
     * @param string $dir  folder to iterate
     * @param int    $mode iteration mode
     *  
     * @return \Includes\Utils\FileFilter\FilterIterator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getIterator($dir, $mode = \RecursiveIteratorIterator::LEAVES_ONLY)
    {
        return new \Includes\Utils\FileFilter\FilterIterator(static::getUnfilteredIterator($dir, $mode));
    }

    /**
     * Return the directory iterator filtered by file extension
     * 
     * @param string $dir       folder to iterate
     * @param string $extension file extension for use in filtering
     *  
     * @return \Includes\Utils\FileFilter\FilterIterator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function filterByExtension($dir, $extension)
    {
        return static::getIterator($dir)->addFilter('byExtension', array($extension));
    }
}
