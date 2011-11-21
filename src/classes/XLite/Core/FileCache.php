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

namespace XLite\Core;

/**
 * File system cache
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class FileCache extends \Doctrine\Common\Cache\AbstractCache
{
    /**
     * Cache directory path
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $path = null;

    /**
     * File header
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $header = '<?php die(); ?>';

    /**
     * File header length
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $headerLength = 15;

    /**
     * TTL block length
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $ttlLength = 11;

    /**
     * Validation cache 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $validationCache = array();

    protected $_namespace;

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Delete by prefix 
     * 
     * @param string $prefix Prefix
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function deleteByPrefix($prefix)
    {
        $deleted = array();

        $prefix = $this->_getNamespacedId($prefix);

        $list = glob($this->path . LC_DS . $prefix . '*.php');

        if ($list) {
            foreach ($list as $f) {
                if ($this->isKeyValid($f)) {
                    $id = substr(basename($f), 0, -4);
                    $this->delete($id);
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Get cache repository ids list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getIds()
    {
        $keys = array();

        $list = glob($this->path . LC_DS . '*.php');

        if ($list) {
            foreach ($list as $f) {
                if ($this->isKeyValid($f)) {
                    $keys[] = substr(basename($f), 0, -4);
                }
            }
        }

        return $keys;
    }

    /**
     * Delete all cache entries
     *
     * @return array Array of the deleted cache ids
     * @see    ____func_see____
     * @since  1.0.0
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
     * Get id + namespace
     * 
     * @param string $id Cell id
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function _getNamespacedId($id)
    {
        return (!$this->_namespace || strpos($id, $this->_namespace) === 0)
            ? $id
            : $this->_namespace . $id;
    }

    /**
     * Get cache cell by id
     *
     * @param string $id CEll id
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function _doFetch($id)
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function _doContains($id)
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function _doSave($id, $data, $lifeTime = 0)
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function _doDelete($id)
    {
        $path = $this->getPathById($id);

        $result = false;

        if (file_exists($path)) {
            $result = @unlink($path);
        }

        return $result;
    }

    /**
     * Get cell path by cell id
     *
     * @param string $id Cell id
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
