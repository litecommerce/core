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

namespace Includes\Utils;

/**
 * FileFilter
 *
 * @package    XLite
 */
class FileFilter extends \Includes\Utils\AUtils
{
    /**
     * Directory to iterate over
     *
     * @var string
     */
    protected $dir;

    /**
     * Pattern to filter files by path
     *
     * @var string
     */
    protected $pattern;

    /**
     * Mode
     *
     * @var int
     */
    protected $mode;

    /**
     * Cache
     *
     * @var \Includes\Utils\FileFilter\FilterIterator
     */
    protected $iterator;


    /**
     * Return the directory iterator
     *
     * @return \RecursiveIteratorIterator
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
     * @param string $dir     Directory to iterate over
     * @param string $pattern Pattern to filter files
     * @param int    $mode    Filtering mode OPTIONAL
     *
     * @return void
     */
    public function __construct($dir, $pattern = null, $mode = \RecursiveIteratorIterator::LEAVES_ONLY)
    {
        $canonicalDir = \Includes\Utils\FileManager::getCanonicalDir($dir);

        if (empty($canonicalDir)) {
            \Includes\ErrorHandler::fireError('Path "' . $dir . '" is not exists or is not readable.');
        }

        $this->dir     = $canonicalDir;
        $this->pattern = $pattern;
        $this->mode    = $mode;
    }
}
