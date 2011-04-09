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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model;

/**
 * PHAR package model
 *
 * FIXME: move all actions into controllers
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class PHARModule extends \XLite\Base
{
    /**
     * Object statuses 
     */

    const STATUS_OK                           = 0;
    const STATUS_EXCEPTION                    = 1;
    const STATUS_FILE_NOT_EXISTS              = 2;
    const STATUS_UNABLE_TO_EXTRACT            = 3;
    const STATUS_UNABLE_TO_CREATE_TMP         = 4;
    const STATUS_MAIN_FILE_NOT_EXISTS         = 5;
    const STATUS_ROOT_DIR_EXISTS              = 6;
    const STATUS_KERNEL_VERSION_NOT_SUPPORTED = 7;


    /**
     * Inner \Phar object
     *
     * @var   \Phar
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $phar;

    /**
     * Temp dir to unpack
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $tempDir;

    /**
     * Status of last operation
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $status = self::STATUS_OK;

    /**
     * Package main class name
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $mainClass;

    /**
     * <Status, Message> pairs
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $errorMessages = array(
        self::STATUS_OK                   => 'No errors',
        self::STATUS_EXCEPTION            => 'Phar class Exception',
        self::STATUS_FILE_NOT_EXISTS      => 'PHAR file not exists in local repository',
        self::STATUS_UNABLE_TO_EXTRACT    => 'Unable to extract PHAR package',
        self::STATUS_UNABLE_TO_CREATE_TMP => 'Unable to create temp dir for PHAR package',
        self::STATUS_MAIN_FILE_NOT_EXISTS => 'Main.php does not exist in package or is not readable',
        self::STATUS_ROOT_DIR_EXISTS      => 'Package root dir is already exists',
    );


    // {{{ Constructor

    /**
     * Constructor
     *
     * @param string $name Name of the .PHAR file in the inner local repository
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($name)
    {
        // The "addons" directory must contain the corresonding PHAR archive
        if (\Includes\Utils\FileManager::isReadable($name = LC_LOCAL_REPOSITORY . $name)) {

            try {
                $this->phar = new \Phar($name);
                $status = $this->deployToTemp();

            } catch (\UnexpectedValueException $exception) {

                $status  = self::STATUS_EXCEPTION;
                $message = $exception->getMessage();
            }

        } else {

            $status = self::STATUS_FILE_NOT_EXISTS;
        }

        if (isset($status)) {
            $this->setStatus($status, empty($message) ? null : $message);
        }
    }

    // }}}

    // {{{  Status and message

    /**
     * Getter
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Getter
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMessage()
    {
        return $this->errorMessages[$this->getStatus()];
    }

    /**
     * Set message and status
     * 
     * @param integer $status  Status to set
     * @param string  $message Message to set OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function setStatus($status, $message = null)
    {
        $this->status = $status;

        if (isset($message)) {
            $this->errorMessages[$status] = $message;
        }
    }

    // }}}

    // {{{ Deployment-related methods

    /**
     * Extract the .PHAR package file content into the temporary local repository
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deployToTemp()
    {
        // Trying to create temp dir
        if ($this->getTempDir()) {

            // Extract files from archive and check package integrity
            $status = $this->extractPHAR() ? $this->checkIntegrity() : self::STATUS_UNABLE_TO_EXTRACT;

        } else {

            // By some reason temporary dir was not created
            $status = self::STATUS_UNABLE_TO_CREATE_TMP;
        }

        return $status;
    }

    /**
     * Extract files from archive
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function extractPHAR()
    {
        return $this->phar->extractTo($this->getTempDir());
    }

    /**
     * Return temporary dir name (to deploy packs there)
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTempDir()
    {
        if (!isset($this->tempDir)) {
            $this->tempDir = $this->makeTempDir();
        }

        return $this->tempDir;
    }

    /**
     * Create temporary dir and return it's name
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function makeTempDir()
    {
        // Get unique file name
        if ($name = $this->getTempDirSuffix()) {

            // We do not need a file, but the dir with unique name
            \Includes\Utils\FileManager::delete($name);

            // Create such dir
            $result = \Includes\Utils\FileManager::mkdirRecursive($name);
        }

        return empty($result) ? null : $name . LC_DS;
    }

    /**
     * Get basename for temp dir
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTempDirSuffix()
    {
        return tempnam(LC_TMP_DIR, 'phar_package');
    }

    // }}}

    // {{{ Integrity check

    /**
     * Check status
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isValid()
    {
        return self::STATUS_OK === $this->getStatus();
    }

    /**
     * Return minimal allowed version of LC kernel
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getVersion()
    {
        return call_user_func(array($this->getMainClass(), 'getVersion'));
    }

    /**
     * Check file structure
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkIntegrity()
    {
        $status = null;

        // Three-step checking
        if (!$this->checkVersion()) {

            // Must be compatible with current version
            $status = self::STATUS_KERNEL_VERSION_NOT_SUPPORTED;

        } elseif (!$this->isMainFileExists()) {

            // Package must contain the Main.php file
            $status = self::STATUS_MAIN_FILE_NOT_EXISTS;

        } elseif ($this->isRootDirExists()) {

            // Package dir is already exists
            $status = self::STATUS_ROOT_DIR_EXISTS;
        }

        return $status;
    }

    /**
     * Check for LC kernel version
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkVersion()
    {
        return version_compare($this->getVersion(), \XLite::getInstance()->getVersion(), '>=');
    }

    /**
     * Check if Main.php exists
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isMainFileExists()
    {
        return \Includes\Utils\FileManager::isFileReadable($this->getMainFile());
    }

    /**
     * Check if package dir already exists
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isRootDirExists()
    {
        return \Includes\Utils\FileManager::isDir($this->getRootDirFull());
    }

    // }}}

    // {{{ Classes, files and dirs

    /**
     * Return name of the main class file
     *
     * FIXME: move Main.php to the root directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMainFile()
    {
        return $this->getTempDir() . 'classes' . LC_DS . 'Main.php';
    }

    /**
     * Return name of package main class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMainClass()
    {
        if (!isset($this->mainClass)) {
            $this->mainClass = \Includes\Utils\Converter::prepareClassName(
                \Includes\Decorator\Utils\Tokenizer::getFullClassName($this->getMainFile()),
                false
            );
            if (!class_exists($this->mainClass, false)) {
                include_once $this->getMainFile();
            }
        }

        return $this->mainClass;
    }

    /**
     * Return module name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModuleName()
    {
        return \Includes\Decorator\Utils\ModulesManager::getModuleNameByClassName($this->getMainClass());
    }

    /**
     * Get module root directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRootDirFull()
    {
        return LC_MODULES_DIR . $this->getRootDirRelative();
    }

    /**
     * Get module root directory
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getRootDirRelative()
    {
        return str_replace('\\', LC_DS, $this->getModuleName());
    }
    
    // }}}

    // {{{ Deploy and cleanup

    /**
     * Deploy 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function deploy()
    {
        if ($this->isValid()) {
            $this->deployClasses();
            $this->deploySkins();
        }
    }

    /**
     * Clean up
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function cleanUp()
    {
        if ($dir = $this->getTempDir()) {
            \Includes\Utils\FileManager::unlinkRecursive($dir);
        }
    }

    /**
     * Copy classes
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deployClasses()
    {
        \Includes\Utils\FileManager::copyRecursive($this->getTempDir() . 'classes', $this->getRootDirFull());
    }

    /**
     * Copy skins
     *
     * TODO: decompose
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function deploySkins()
    {
        foreach (\XLite\Core\Layout::getInstance()->getSkinsAll() as $skin) {

            $paths = \XLite\Core\Layout::getInstance()->getSkinPaths($skin);
            $data  = reset($paths);

            \Includes\Utils\FileManager::copyRecursive(
                $this->getTempDir() . 'skins' . LC_DS . \XLite\Core\Layout::getInstance()->getSkinPathRelative($skin),
                $data['fs'] . LC_DS . 'modules' . LC_DS . $this->getRootDirRelative()
            );
        }
    }

    // }}}
}
