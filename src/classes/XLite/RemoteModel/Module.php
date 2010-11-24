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
 * @subpackage RemoteModel
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\RemoteModel;

/**
 * Module 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends \XLite\RemoteModel\AModel
{
    const NO_EXISTS = 0;
    const EXISTS    = 1;
    const OBSOLETE  = 2;

    const UPLOAD_CODE_LENGTH = 32;

    /**
     * Status 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $status = self::NO_EXISTS;

    /**
     * Name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $name;

    /**
     * Description 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $description;

    /**
     * Version 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $version;

    /**
     * Changelog 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $changelog = array();

    /**
     * Hash 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $hash;

    /**
     * Install pack hash 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $packHash;

    /**
     * Price 
     * 
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $price = 0;

    /**
     * Currency code
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currency = 'USD';

    /**
     * Upload code 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $uploadCode;

    /**
     * Upload URL 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $uploadURL = 'https://litecommerce.com/module/%1$s/upload?code=%2$s';

    /**
     * Model (cache)
     * 
     * @var    \XLite\Model\Module
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $model = null;

    /**
     * Get status 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get description 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get version 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Get changelog 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getChangelog()
    {
        return $this->changelog;
    }

    /**
     * Get hash 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Get price 
     * 
     * @return float
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get model 
     * 
     * @param boolean $overrideCache Ovveride internal cache OPTIONAL
     *  
     * @return \XLite\Model\Module
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModel($overrideCache = false)
    {
        if (!isset($this->model) || $overrideCache) {
            $this->model = \Xlite\Core\Database::getRepo('\XLite\Model\Module')->findByName($this->getName());
            if (!$this->model) {
                $this->model = false;
            }
        }

        return $this->model;
    }

    /**
     * Check - can upload module or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canUpload()
    {
        return self::UPLOAD_CODE_LENGTH == strlen($this->uploadCode);
    }

    /**
     * Upload module
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function upload()
    {
        $result = false;

        if ($this->canUpload()) {
            $request = new \XLite\Model\HTTPS();
            $request->url = sprintf($this->uploadURL(), $this->getName(), $this->uploadCode);
            $request->method = 'get';
            if (
                $request::HTTPS_SUCCESS == $request->request()
                && $request->response
                && $this->packHash == hash('sha512', $request->response)
            ) {
                $result = tempnam(LC_TMP_DIR, 'module');
                file_put_contents($result, $request->response);
            }
        }

        return $result;
    }

    /**
     * Install (with upload) module
     * 
     * @param boolean $overrideExists Ovverride exist module OPTIONAL
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function install($overrideExists = false)
    {
        $result = false;

        if (!$this->getModel() || $overrideExists) {
            $path = $this->upload();
            if ($path) {
                $newPath = LC_CLASSES_DIR . $this->getName() . '.phar';
                rename($path, $newPath);
                $this->getModel()->disableDepended();
                \XLite\Core\Database::getEM()->remove($this->getModel());
                \XLite\Core\Database::getEM()->flush();

                if ($this->depack($newPath)) {
                    $module = new \XLite\Model\Module();
                    $module->create($this->getName());
                    $this->getModel(true);
                    $result = true;
                }
            }
        }

        return $result;
    }

    /**
     * Depack install pack
     * 
     * @param string $path Install pack path
     *  
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function depack($path)
    {
        $result = false;

        if (file_exists($path) && is_readable($path) && preg_match('/\.phar/Ss', $path)) {
            $p = new \Phar($path, 0, basename($path));
            $result = $p->decompressFiles();
        }

        return $result;
    }

}
