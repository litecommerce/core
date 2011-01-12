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
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class PHARModule extends \XLite\Base
{

    /**
     * Values of the statuses
     */
    const STATUS_OK                     = 'ok';
    const STATUS_ERROR                  = 'error';
    const STATUS_WRONG_SPECIFICATION    = 'wrong_specification';
    const STATUS_INI_CORRUPTED          = 'ini_corrupted';
    const STATUS_WRONG_STRUCTURE        = 'wrong_structure';
    const STATUS_WRONG_INSTALL          = 'wrong_install';


    /**
     * .PHAR file structure specific values
     */
    const MODULE_INI    = 'module.ini';
    const CLASSES_DIR   = 'classes';
    const SKINS_DIR     = 'skins';


    /**
     * Main section name in the INI file inside of .PHAR file 
     */
    const MODULE_SPECIFICATION  = 'module_specification';


    /**
     * Section variables in the INI file inside of .PHAR file 
     */
    const MODULE            = 'module';
    const MODULE_DIR        = 'module_dir';
    const MODULE_AUTHOR     = 'module_author';
    const MODULE_VERSION    = 'module_version';



    /**
     * Inner \Phar object
     * 
     * @var    \Phar
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $module     = null;

    /**
     * Inner catalog to the first temporary deploying
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $tempDir    = null;

    /**
     * Error message of the \Phar object
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $error      = null;

    /**
     * Array of values from the INI file
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $iniFile    = null;


    /**
     * Constructor of the class. 
     * 
     * @param mixed $module name of the .PHAR file in the inner local repository
     *  
     * @return string status of the initialization and deploying
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($module)
    {
        $result = self::STATUS_ERROR;

        if (is_file(LC_LOCAL_REPOSITORY . $module)) {

            try {

                $this->module = new \Phar(LC_LOCAL_REPOSITORY . $module);

                $result = $this->deployToTemp();

            } catch (UnexpectedValueException $e) {

                $this->error = $e->getMessage();
            }
        }

        return $result;
    }


    /**
     * Checks the .PHAR file
     * 
     * @return string status of validation
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function check()
    {
        $result = self::STATUS_ERROR;

        if (
            !is_null($this->module)
            && !is_null($this->tempDir)
        ) {
            $result = $this->checkFileStructure();

            if (self::STATUS_OK === $result) {

                $result = $this->checkIniFile();

                if (self::STATUS_OK === $result) {

                    $result = $this->checkInstall();

                }
            }
        }

        return $result;
    }


    /**
     * Deploys .PHAR module file into LiteCommerce module structure
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deploy()
    {
        if (!is_null($this->iniFile)) {

            $this->deployClasses();

            $this->deploySkins();
        }
    }


    /**
     * Removes the temporary files from the temporary local repository of modules.
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cleanUp()
    {
        if (!is_null($this->tempDir)) {
            \Includes\Utils\FileManager::unlinkRecursive($this->tempDir);
        }
    }


    /**
     * Returns error message of .PHAR file operations
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Deploys module classes structure into LiteCommerce classes catalog
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deployClasses()
    {
        $classesTempDir = $this->getClassesDir();
        $classesDir     = $this->getModuleClassesDir();

        if (!is_null($classesTempDir)) {

            \Includes\Utils\FileManager::mkdirRecursive($classesDir);

            \Includes\Utils\FileManager::copyRecursive(
                $classesTempDir,
                $classesDir
            );
        }
    }


    /**
     * Deploys module skins structure into LiteCommerce skins catalog
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deploySkins()
    {
        if (!is_null($this->tempDir)) {

            $skins = $this->fetchSkins();

            foreach ($skins as $skinDir => $skinTempDir) {

                \Includes\Utils\FileManager::mkdirRecursive($skinDir);

                \Includes\Utils\FileManager::copyRecursive($skinTempDir, $skinDir);
            }
        }
    }


    /**
     * Retrieve skins list from the temporary local repository of module
     * 
     * @return array list of skins in the format: {new skin path} => {skin path from temporary local repository of module}
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function fetchSkins()
    {
        $result = array();

        $skinsDir = $this->getSkinsDir();

        if (!is_null($skinsDir)) {

            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($skinsDir), \RecursiveIteratorIterator::SELF_FIRST);

            $iterator->setMaxDepth(2);

            while($iterator->valid()) {

                if (!$iterator->isDot() && $iterator->isDir() && 1 == $iterator->getDepth()) {

                    $subPath = $iterator->getSubPathName();

                    $dir = $this->constructSkinPath($subPath);

                    $result[LC_SKINS_DIR . $dir] = $skinsDir . LC_DS . $subPath;
                }

                $iterator->next();
            }
        }

        return $result;
    }


    /**
     * Construct relative path to module skin inside of LiteCommerce skin module repository
     * 
     * @param string $dir Catalog path
     *  
     * @return string relative path to skin
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function constructSkinPath($dir)
    {
        return $dir . LC_DS . 'modules' . LC_DS . $this->getModuleSpecificPath();
    }


    /**
     * Returns INI file path in the temporary local repository of module
     * 
     * @return string file path to the INI file
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIniFile()
    {
        return !is_null($this->tempDir) && is_file($this->tempDir . self::MODULE_INI)
            ? $this->tempDir . self::MODULE_INI
            : null;
    }


    /**
     * Returns the classes catalog inside the temporary local repository of module
     * 
     * @return string catalog path
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassesDir()
    {
        return !is_null($this->tempDir) && is_dir($this->tempDir . self::CLASSES_DIR)
            ? $this->tempDir . self::CLASSES_DIR
            : null;
    }
    

    /**
     * Returns the skins catalog inside the temporary local repository of module
     * 
     * @return string catalog path
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSkinsDir()
    {
        return !is_null($this->tempDir) && is_dir($this->tempDir . self::SKINS_DIR)
            ? $this->tempDir . self::SKINS_DIR
            : null;
    }


    /**
     * Module INI file validation 
     * 
     * @return string status of validation (use self::STATUS_* constants)
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkIniFile()
    {
        $this->iniFile = parse_ini_file($this->getIniFile(), true);

        $result = self::STATUS_INI_CORRUPTED;

        if (
            is_array($this->iniFile)
            && isset($this->iniFile[self::MODULE_SPECIFICATION])
            && !empty($this->iniFile[self::MODULE_SPECIFICATION])
        ) {

            $specification = $this->iniFile[self::MODULE_SPECIFICATION];

            $result = self::STATUS_OK;

            foreach ($this->getRequiredFields() as $field) {
                if (
                    !isset($specification[$field])
                    || empty($specification[$field])
                ) {
                    $result = self::STATUS_WRONG_SPECIFICATION;
                }
            }
        }

        return $result;
    }


    /**
     * Returns required fields in the module specification section in the INI file of module.
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRequiredFields()
    {
        return array(
            self::MODULE,
            self::MODULE_DIR,
            self::MODULE_AUTHOR,
            self::MODULE_VERSION,
        );
    }


    /**
     * Returns classes catalog of the deployed module inside of the LiteCommerce classes structure
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleClassesDir()
    {
        return
            LC_CLASSES_DIR 
            . 'XLite' 
            . LC_DS . 'Module' 
            . LC_DS . $this->getModuleSpecificPath();
    }


    /**
     * Returns the module specific relative path. It provides the unique author-module_dir pair inside the LiteCommerce module catalogs
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleSpecificPath()
    {
        return $this->iniFile[self::MODULE_SPECIFICATION][self::MODULE_AUTHOR]
            . LC_DS . $this->iniFile[self::MODULE_SPECIFICATION][self::MODULE_DIR];
    }


    /**
     * Validation of Install availability. Currently you cannot install the module that has the same classes catalog with some already retrieved module.
     * 
     * @return string status of validation
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkInstall()
    {
        return is_dir($this->getModuleClassesDir()) ? self::STATUS_WRONG_INSTALL : self::STATUS_OK;
    }


    /**
     * Validation of inner file-catalog structure of module file. It must contain INI file, Classes catalog or Skins catalog.
     * 
     * @return string status of validation
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function  checkFileStructure()
    {
        // Check primary file/catalog structure
        return (
            !is_null($this->getIniFile())
            && (
                !is_null($this->getClassesDir())
                || !is_null($this->getSkinsDir())
            )   
        ) ? self::STATUS_OK : self::STATUS_WRONG_STRUCTURE;
    }


    /**
     * Makes the unique temporary catalog for the temporary local repository of module
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function makeTempDir()
    {
        $fn = @tempnam(LC_TMP_DIR, 'phar_module');

        @unlink($fn);

        $this->tempDir = @mkdir($fn) ? $fn . LC_DS : null;
    }


    /**
     * Extract the .PHAR module file content into the temporary local repository of module
     * 
     * @return string status of deploying
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deployToTemp()
    {
        $this->makeTempDir();

        return (
            !is_null($this->tempDir) 
            && $this->module->extractTo($this->tempDir)
        ) ? self::STATUS_OK : self::STATUS_ERROR;
    }
}
