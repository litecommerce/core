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
 * @copyright  Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Model;

/**
 * Module deploying model
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
    protected $phar       = null;


    /**
     * Module model for inner use
     * 
     * @var    mixed
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
     * Status of last operation
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $status     = null;


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
     * @param mixed $phar Name of the .PHAR file in the inner local repository
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($phar)
    {
        $result = self::STATUS_ERROR;

        $this->makeTempDir();

        if (is_file(LC_LOCAL_REPOSITORY . $phar)) {

            try {

                $this->phar = new \Phar(LC_LOCAL_REPOSITORY . $phar);

                $result = $this->deployToTemp();

            } catch (\UnexpectedValueException $e) {

                $this->error = $e->getMessage();
            }
        }

        $this->status = $result;
    }


    /**
     * Checks the .PHAR file
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function check()
    {
        $result = self::STATUS_ERROR;

        if (
            !is_null($this->getPhar())
            && !is_null($this->getTempDir())
        ) {
            $result = $this->checkFileStructure();

            if (self::STATUS_OK === $result) {

                $result = $this->checkIniFile();

                if (self::STATUS_OK === $result) {

                    $this->initModule();

                    $result = $this->checkInstall();
                }
            }
        }

        $this->status = $result;
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
        if (!is_null($this->getModule())) {

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
        if (!is_null($this->getTempDir())) {
            \Includes\Utils\FileManager::unlinkRecursive($this->getTempDir());
        }
    }


    /**
     * Error message of .PHAR file operations getter
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Status of last operation getter
     * 
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStatus()
    {
        return $this->status;
    }


    /**
     * Temporary storage getter
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getTempDir()
    {
        return $this->tempDir;
    }


    /**
     * Module model getter
     * 
     * @return mixed
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModule()
    {
        return $this->module;
    }

    /**
     * PHAR object getter
     * 
     * @return \Phar
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPhar()
    {
        return $this->phar;
    }


    /**
     * Module model initialization
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function initModule()
    {
        $this->module = new \XLite\Model\Module();

        $this->getModule()->setName($this->getModuleName());
        $this->getModule()->setAuthor($this->getAuthor());
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
        $classesTempDir = $this->getClassesTempDir();
        $classesDir     = $this->getModule()->getRootDirectory();

        if (is_dir($classesTempDir)) {

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
        if (is_dir($this->getSkinsTempDir())) {

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
     * @return array List of skins in the format: {new skin path} => {skin path from temporary local repository of module}
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function fetchSkins()
    {
        $result = array();

        $iterator = $this->getFetchSkinsIterator();

        $iterator->setMaxDepth(2);

        while ($iterator->valid()) {

            if (
                $iterator->isDir() 
                && 1 == $iterator->getDepth()
            ) {
                $this->registerFetchedSkin($iterator, $result);
            }

            $iterator->next();
        }

        return $result;
    }


    /**
     * Return file iterator for fetching skins 
     * 
     * @return \RecursiveIteratorIterator
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFetchSkinsIterator()
    {
        return new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->getSkinsTempDir()),
            \RecursiveIteratorIterator::SELF_FIRST,
            \FilesystemIterator::SKIP_DOTS
        );
    }


    /**
     * Register specific modules skins catalogs from file iterator. 
     * 
     * @param \RecursiveIteratorIterator $iterator  File iterator
     * @param array                      &$registry Array for registration the skins dir
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function registerFetchedSkin(\RecursiveIteratorIterator $iterator, &$registry)
    {
        $subPath = $iterator->getSubPathName();

        $dir = $this->getModule()->constructSkinPath($subPath);

        $registry[$dir] = $this->getSkinsTempDir() . LC_DS . $subPath;
    }


    /**
     * Returns INI file path in the temporary local repository of module
     * 
     * @return string File path to the INI file
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getIniFile()
    {
        return !is_null($this->getTempDir())
            ? $this->getTempDir() . self::MODULE_INI
            : null;
    }


    /**
     * Returns the classes catalog inside the temporary local repository of module
     * 
     * @return string Catalog path
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassesTempDir()
    {
        return !is_null($this->getTempDir())
            ? $this->getTempDir() . self::CLASSES_DIR
            : null;
    }
    

    /**
     * Returns the skins catalog inside the temporary local repository of module
     * 
     * @return string Catalog path
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSkinsTempDir()
    {
        return !is_null($this->getTempDir())
            ? $this->getTempDir() . self::SKINS_DIR
            : null;
    }


    /**
     * Module INI file validation 
     * 
     * @return string Status of validation (use self::STATUS_* constants)
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
        );
    }


    /**
     * Return author from INI file
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAuthor()
    {
        return $this->iniFile[self::MODULE_SPECIFICATION][self::MODULE_AUTHOR];
    }


    /**
     * Return module name from INI file 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleName()
    {
        return $this->iniFile[self::MODULE_SPECIFICATION][self::MODULE_DIR];
    }


    /**
     * Validation of Install availability. 
     * Currently you cannot install the module which has the same classes catalog with some already retrieved module.
     * 
     * @return string Status of validation
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkInstall()
    {
        return is_dir($this->getModule()->getRootDirectory()) ? self::STATUS_WRONG_INSTALL : self::STATUS_OK;
    }


    /**
     * Validation of inner file-catalog structure of module file. It must contain INI file, Classes catalog or Skins catalog.
     * 
     * @return string Status of validation
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function  checkFileStructure()
    {
        // Check primary file/catalog structure
        return (
            is_file($this->getIniFile())
            && (
                is_dir($this->getClassesTempDir())
                || is_dir($this->getSkinsTempDir())
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

        $this->tempDir = \Includes\Utils\FileManager::mkdirRecursive($fn) ? $fn . LC_DS : null;
    }


    /**
     * Extract the .PHAR module file content into the temporary local repository of module
     * 
     * @return string Status of deploying
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deployToTemp()
    {
        return (
            !is_null($this->getTempDir()) 
            && $this->getPhar()->extractTo($this->getTempDir())
        ) ? self::STATUS_OK : self::STATUS_ERROR;
    }
}
