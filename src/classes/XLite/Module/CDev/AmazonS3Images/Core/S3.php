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
 * @since     1.0.19
 */

namespace XLite\Module\CDev\AmazonS3Images\Core;

/**
 * AWS S3 client
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class S3 extends \XLite\Base\Singleton
{
    const GENERATION_LIMIT = 100;

    /**
     * AWS S3 client 
     * 
     * @var   \S3
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $client;

    /**
     * Valid status
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $valid = false;

    /**
     * URL prefix
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.19
     */
    protected $urlPrefix;

    /**
     * Check valid status
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Write 
     * 
     * @param string $path        Short path
     * @param string $data        Data
     * @param array  $httpHeaders HTTP headers OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function write($path, $data, array $httpHeaders = array())
    {
        $result = false;

        try {
            $result = $this->client->putObject(
                $data,
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path,
                \S3::ACL_PUBLIC_READ,
                array(),
                $httpHeaders
            );
            $result = (bool)$result;
            $message = true;

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Copy 
     * 
     * @param string $from        Full path
     * @param string $to          Short path
     * @param array  $httpHeaders HTTP headers OPTIONAL
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function copy($from, $to, array $httpHeaders = array())
    {
        $result = false;
        if (\Includes\Utils\FileManager::isExists($from)) {
            try {
                $result = $this->client->putObjectFile(
                    $from,
                    \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                    $to,
                    \S3::ACL_PUBLIC_READ,
                    array(),
                    $httpHeaders
                );

            } catch (\S3Exception $e) {
                $result = false;
                \XLite\Logger::getInstance()->registerException($e);
            }
        }

        return $result;
    }

    /**
     * Read 
     * 
     * @param string $path Short path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function read($path)
    {
        try {
            $result = $this->client->getObject(
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path
            );

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return is_object($result) ? $result->body : null;
    }

    /**
     * Delete 
     * 
     * @param string $path Short path
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function delete($path)
    {
        $result = false;
        try {
            $result = $this->client->deleteObject(
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path
            );

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Delete directory
     *
     * @param string $path Short path
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function deleteDirectory($path)
    {
        $result = false;
        try {
            foreach ($this->readDirectory($path) as $k => $v) {
                $this->client->deleteObject(\XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket, $k);
            }
            $result = $this->delete($path);

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Read directory
     *
     * @param string $path Short path
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function readDirectory($path)
    {
        try {
            $result = $this->client->getObject(
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path
            );

        } catch (\S3Exception $e) {
            $result = array();
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Check - path is directory or not
     *
     * @param string $path Short path
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function isDir($path)
    {
        $result = false;
        try {
            $result = $this->client->getObjectInfo(
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path
            );

            if (is_array($result)) {
                $result = $result['type'] == 'binary/octet-stream';

            } else {
                $result = (bool)$this->client->getBucket(
                    \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                    $path
                );
            }

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Get URL by short path
     * 
     * @param string $path Short path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function getURL($path)
    {
        if (!isset($this->urlPrefix)) {
            $config = \XLite\Core\Config::getInstance()->CDev->AmazonS3Images;

            $this->urlPrefix = $config->cloudfront_domain
                ? ('http://' . $config->cloudfront_domain . '/')
                : (rtrim($config->server, '/') . '/' . $config->bucket . '/');
        }

        return $this->urlPrefix . $path;
    }

    /**
     * Check - file is exists or not
     * 
     * @param string $path Short path
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function isExists($path)
    {
        try {
            $result = $this->client->getObjectInfo(
                \XLite\Core\Config::getInstance()->CDev->AmazonS3Images->bucket,
                $path,
                false
            );

        } catch (\S3Exception $e) {
            $result = false;
            \XLite\Logger::getInstance()->registerException($e);
        }

        return $result;
    }

    /**
     * Generate unique path 
     * 
     * @param string $path Short path
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function generateUniquePath($path)
    {
        if ($this->isExists($path)) {
            if (preg_match('/^(.+)\.([^\.]+)$/Ss', $path, $match)) {
                $base = $match[1] . '.';
                $ext = '.' . $match[2];

            } else {
                $base = $path . '.';
                $ext = '';
            }

            $i = 0;
            do {
                $path = $base . uniqid('', true) . $ext;
                $i++;
            } while ($this->isExists($path) && self::GENERATION_LIMIT > $i);
        }

        return $path;
    }

    /**
     * Check settings 
     * 
     * @param string $accessKey AWS access key
     * @param string $secretKey AWS secret key
     * @param string $bucket    S3 bucket
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.24
     */
    public function checkSettings($accessKey, $secretKey, $bucket)
    {
        $valid = false;

        $client = new \S3($accessKey, $secretKey);
        \S3::setExceptions(true);

        try {
            if (!$client->getBucketLocation($bucket)) {
                $client->putBucket($bucket);
            }
            $valid = true;

        } catch (\Exception $e) {
        }

        return $valid;
    }

    /**
     * Constructor
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function __construct()
    {
        require_once LC_DIR_CLASSES . '/XLite/Module/CDev/AmazonS3Images/lib/S3.php';

        $config = \XLite\Core\Config::getInstance()->CDev->AmazonS3Images;

        $this->client = new \S3($config->access_key, $config->secret_key);
        \S3::setExceptions(true);

        try {
            if (!$this->client->getBucketLocation($config->bucket)) {
                $this->client->putBucket($config->bucket);
            }
            $this->valid = true;

        } catch (\S3Exception $e) {
            \XLite\Logger::getInstance()->registerException($e);
        }
    }

}

