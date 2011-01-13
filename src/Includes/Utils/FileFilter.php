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
     * Directory to iterate over
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $dir;

    /**
     * Patern to filter files by path
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $patern;

    /**
     * Mode 
     * 
     * @var    int
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mode;

    /**
     * Cache 
     * 
     * @var    \Includes\Utils\FileFilter\FilterIterator
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $iterator;


    /**
     * Return the directory iterator
     * 
     * @return \RecursiveIteratorIterator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getUnfilteredIterator()
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->dir),
            $this->mode,
            \FilesystemIterator::SKIP_DOTS
        );
    }


    /**
     * Return the directory iterator
     * 
     * @return \Includes\Utils\FileFilter\FilterIterator
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIterator()
    {
        if (!isset($this->iterator)) {
            $this->iterator = new \Includes\Utils\FileFilter\FilterIterator(static::getUnfilteredIterator(), $this->pattern);
        }

        return $this->iterator;
    }

    /**
     * Constructor
     * 
     * @param string $dir     directory to iterate over
     * @param string $pattern pattern to filter files
     * @param int    $mode    filtering mode
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($dir, $pattern, $mode = \RecursiveIteratorIterator::LEAVES_ONLY)
    {
        $this->dir     = $dir;
        $this->pattern = $pattern;
        $this->mode    = $mode;
    }
}
