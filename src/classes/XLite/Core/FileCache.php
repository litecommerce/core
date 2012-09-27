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

namespace XLite\Core;

/**
 * File system cache
 * FIXME: must be completely refactored
 *
 */
class FileCache extends \Doctrine\Common\Cache\CacheProvider
{
    /**
     * Cache directory path
     *
     * @var string
     */
    protected $path = null;

    /**
     * File header
     *
     * @var string
     */
    protected $header = '<?php die(); ?>';

    /**
     * File header length
     *
     * @var integer
     */
    protected $headerLength = 15;

    /**
     * TTL block length
     *
     * @var integer
     */
    protected $ttlLength = 11;

    /**
     * Validation cache
     *
     * @var array
     */
    protected $validationCache = array();

    protected $_namespace;

    /**
     * Constructor
     *
     * @return void
     */
    public function __construct($path = null)
    {
        $this->setPath($path ? $path : sys_get_temp_dir());
    }

    /**
     * Set cache path
     *
     * @param string $path Path
     *
     * @return void
     */
    public function setPath($path)
    {
        if (is_string($path)) {
            if (!file_exists($path)) {
                \Includes\Utils\FileManager::mkdirRecursive($path);
            }

            if (file_exists($path) && is_dir($path)) {
                $this->path = $path;
            }
        }
    }

    /**
     * Get cache path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * getNamespacedId
     *
     * @param string $id ____param_comment____
     *
     * @return string
     */
    protected function getNamespacedId($id)
    {
        $namespaceCacheKey = sprintf(static::DOCTRINE_NAMESPACE_CACHEKEY, $this->getNamespace());
        $namespaceVersion  = ($this->doContains($namespaceCacheKey)) ? $this->doFetch($namespaceCacheKey) : 1;

        return sprintf('%s[%s][%s]', $this->getNamespace(), $id, $namespaceVersion);
    }

    /**
     * getNamespacedId
     *
     * @param string $id ____param_comment____
     *
     * @return string
     */
    protected function getNamespacedIdToDelete($id)
    {
        $namespaceCacheKey = sprintf(static::DOCTRINE_NAMESPACE_CACHEKEY, $this->getNamespace());
        $namespaceVersion  = ($this->doContains($namespaceCacheKey)) ? $this->doFetch($namespaceCacheKey) : 1;

        return sprintf('%s[%s*', $this->getNamespace(), $id, $namespaceVersion);
    }


    /**
     * Delete by prefix
     *
     * @param string $prefix Prefix
     *
     * @return array
     */
    public function deleteByPrefix($prefix)
    {
        $deleted = array();

        $prefix = $this->getNamespacedIdToDelete($prefix);
        $list = glob($this->path . LC_DS . $prefix);

        if ($list) {
            foreach ($list as $f) {
                if ($this->isKeyValid($f)) {
                    $id = substr(basename($f), 0, -4);
                    \Includes\Utils\FileManager::deleteFile($f);
                    $deleted[] = $id;
                }
            }
        }

        return $deleted;
    }

    /**
     * Delete by regular expression
     *
     * @param string $regex Regular expression
     *
     * @return array
     */
    public function deleteByRegex($regex)
    {
        $iterator = new \XLite\Core\FileCache\Iterator(new \FilesystemIterator($this->path));
        $iterator->setRegexp($regex);

        $deleted = array();

        foreach ($iterator as $path => $info) {
            if ($this->isKeyValid($path)) {
                $id = substr(basename($path), 0, -4);
                $this->delete($id);
                $deleted[] = $id;
            }
        }

        return $deleted;
    }

    /**
     * Delete all cache entries
     *
     * @return array Array of the deleted cache ids
     */
    public function deleteAll()
    {
        $keys = array();

        $list = glob($this->path . LC_DS . '*.php');

        if ($list) {
            foreach ($list as $f) {
                if (unlink($f)) {
                    $keys[] = substr(basename($f), 0, -4);
                }
            }
        }

        return $keys;
    }

    /**
     * Get cache cell by id
     *
     * @param string $id CEll id
     *
     * @return mixed
     */
    protected function doFetch($id)
    {
        $path = $this->getPathById($id);

        $result = false;

        if (file_exists($path) && $this->isKeyValid($path)) {
            $result = unserialize(file_get_contents($path, false, null, $this->headerLength + $this->ttlLength));
        }

        return $result;
    }

    /**
     * Check - repository has cell with specified id or not
     *
     * @param string $id CEll id
     *
     * @return boolean
     */
    protected function doContains($id)
    {
        $path = $this->getPathById($id);

        return file_exists($path) && $this->isKeyValid($path);
    }

    /**
     * Save cell data
     *
     * @param string  $id       Cell id
     * @param mixed   $data     Cell data
     * @param integer $lifeTime Cell TTL OPTIONAL
     *
     * @return boolean
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $lifeTime = strval(min(0, intval($lifeTime)));

        return \Includes\Utils\FileManager::write(
            $this->getPathById($id),
            $this->header . str_repeat(' ', $this->ttlLength - strlen($lifeTime)) . $lifeTime . serialize($data)
        );
    }

    /**
     * Delete cell
     *
     * @param string $id Cell id
     *
     * @return boolean
     */
    protected function doDelete($id)
    {
        $path = $this->getPathById($id);

        $result = false;

        if (file_exists($path)) {
            $result = @unlink($path);
        }

        return $result;
    }

    /**
     * doFlush
     *
     * @return boolean
     */
    protected function doFlush()
    {
        return true;
    }

    /**
     * doGetStats
     *
     * @return array
     */
    protected function doGetStats()
    {
        return array();
    }

    /**
     * Get cell path by cell id
     *
     * @param string $id Cell id
     *
     * @return string
     */
    protected function getPathById($id)
    {
        return $this->path . LC_DS . str_replace('\\', '_', $id) . '.php';
    }

    /**
     * Check - cell file is valid or not
     *
     * @param string $path CEll file path
     *
     * @return boolean
     */
    protected function isKeyValid($path)
    {
        if (!isset($this->validationCache[$path]) || !$this->validationCache[$path]) {

            $result = true;

            $ttl = intval(file_get_contents($path, false, null, $this->headerLength, $this->ttlLength));

            if (0 < $ttl && time() > $ttl) {
                unlink($path);
                $result = false;
            }

            $this->validationCache[$path] = $result;
        }

        return $this->validationCache[$path];
    }
}
