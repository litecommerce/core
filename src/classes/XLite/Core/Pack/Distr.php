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

namespace XLite\Core\Pack;

/**
 * Distr
 *
 */
class Distr extends \XLite\Core\Pack\APack
{
    /**
     * Field names in metadata
     */
    const METADATA_FIELD_VERSION_MINOR = 'VersionMinor';
    const METADATA_FIELD_VERSION_MAJOR = 'VersionMajor';

    /**
     * List of patterns which are not required in pack
     *
     * @var array
     */
    protected $exclude = array();

    /**
     * List of exception patterns
     *
     * @var array
     */
    protected $include = array();

    /**
     * Exclude pattern
     *
     * @var string
     */
    protected $excludePattern;

    /**
     * Include pattern
     *
     * @var string
     */
    protected $includePattern;

    // {{{ Public methods

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct()
    {
        $this->exclude[] = 'var';
        $this->exclude[] = 'files';
        $this->exclude[] = 'images';
        $this->exclude[] = 'sql';
        $this->exclude[] = 'etc' . LC_DS . 'config.local.php';

        $this->include[] = 'var' . LC_DS . '.htaccess';
        $this->include[] = 'files' . LC_DS . '.htaccess';
        $this->include[] = 'images' . LC_DS . '.htaccess';
        $this->include[] = 'images' . LC_DS . 'spacer.gif';
        $this->include[] = 'sql' . LC_DS . 'xlite_data.yaml';
    }

    /**
     * Return pack name
     *
     * @return string
     */
    public function getName()
    {
        // It's the fix for PHAR::compress(): it's triming dots in file names
        return 'LC-Distr-v' . str_replace('.', '_', \XLite::getInstance()->getVersion());
    }

    /**
     * Return iterator to walk through directories
     *
     * @return \Iterator
     */
    public function getDirectoryIterator()
    {
        $result = new \Includes\Utils\FileFilter(LC_DIR_ROOT);
        $result = $result->getIterator();
        $this->preparePatterns();
        $result->registerCallback(array($this, 'filterCoreFiles'));

        return $result;
    }

    /**
     * Return pack metadata
     *
     * @return array
     */
    public function getMetadata()
    {
        return parent::getMetadata() + array(
            self::METADATA_FIELD_VERSION_MAJOR => \XLite::getInstance()->getMajorVersion(),
            self::METADATA_FIELD_VERSION_MINOR => \XLite::getInstance()->getMinorVersion(),
        );
    }

    /**
     * Preapre patterns
     *
     * @return void
     */
    protected function preparePatterns()
    {
        $list = array();
        foreach ($this->exclude as $pattern) {
            $list[] = preg_quote($pattern, '/');
        }

        $this->excludePattern = '/^(?:' . implode('|', $list) . ')/Ss';

        $list = array();
        foreach ($this->include as $pattern) {
            $list[] = preg_quote($pattern, '/');
        }

        $this->includePattern = '/^(?:' . implode('|', $list) . ')/Ss';

    }

    // }}}

    // {{{ Auxiliary methods

    /**
     * Callback to filter files
     *
     * @param \Includes\Utils\FileFilter\FilterIterator $iterator Directory iterator
     *
     * @return boolean
     */
    public function filterCoreFiles(\Includes\Utils\FileFilter\FilterIterator $iterator)
    {
        // Relative path in LC root directory
        $path = \Includes\Utils\FileManager::getRelativePath($iterator->getPathname(), LC_DIR_ROOT);

        return !preg_match($this->excludePattern, $path)
            || preg_match($this->includePattern, $path);
    }

    // }}}
}
