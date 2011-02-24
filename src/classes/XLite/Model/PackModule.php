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
 * @since     3.0.0
 */

namespace XLite\Model;

/**
 * Module packaging model TODO: refactor with \XLite\Model\PHARModule
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class PackModule extends \XLite\Base
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

    const PHAR          = '.phar';
    const GZ            = '.gz';

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
     * Temporary repository of module package
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $tempDir = null;


    /**
     * Error message
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $error = null;


    /**
     * Module model
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $module = null;



    /**
     * Constructor of module packaging model
     * 
     * @param integer $moduleId Module identificator
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct($moduleId)
    {
        $this->makeTempDir();

        $this->module = \XLite\Core\Database::getRepo('\XLite\Model\Module')->find($moduleId);
    }


    /**
     * Create PHAR package routine
     * 
     * @return string Status of package creation 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createPackage()
    {
        $result = self::STATUS_ERROR;

        if (
            !is_null($this->getTempDir())
            && !is_null($this->getModule())
        ) {
            $result = self::STATUS_OK;

            $this->createIniFile();

            $this->collectClasses();

            $this->collectSkins();

            try {

                $phar = new \Phar($this->getPharName());

                $phar->buildFromDirectory($this->getTempDir());

                // TODO check compatibility. not every server supports it.
                // $phar->compress(\Phar::GZ);

            } catch (\Exception $e) {

                $this->error = $e->getMessage();

                $result = self::STATUS_ERROR; 
            }
        }

        return $result;
    }


    /**
     * Download package routine
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function downloadPackage()
    {
        if (is_file($this->getPharGZName())) {

            $download = $this->getPharGZName();

        } elseif (is_file($this->getPharName())) {

            $download = $this->getPharName();

        } else {

            $download = null;
        }

        if (!is_null($download)) {

            header('Content-Type: application/force-download');
            header('Content-Disposition: attachment; filename="' . basename($download) . '"');
            header('Content-Length: ' . filesize($download));

            @readfile($download);
        }
    }


    /**
     * Removes the temporary files from the temporary local repository of modules.
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cleanUp()
    {
        if (!is_null($this->getTempDir())) {
            \Includes\Utils\FileManager::unlinkRecursive($this->getTempDir());

            @unlink($this->getPharName());
            @unlink($this->getPharGZName());
        }   
    }


    /**
     * Returns error message of .PHAR file operations
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getError()
    {
        return $this->error;
    }


    /**
     * Temporary storage getter
     * 
     * @return mixed
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModule()
    {
        return $this->module;
    }


    /**
     * Return PHAR name of module file
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPharName()
    {
        return LC_LOCAL_REPOSITORY . $this->getModule()->getAuthor() . '-' . $this->getModule()->getName() . self::PHAR;
    }

    /**
     * Return PHAR GZ name of module file
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPharGZName()
    {
        return $this->getPharName() . self::GZ;
    }

    /**
     * Return File filter regexp
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFileFilter()
    {
        return '/(?:' . preg_quote(LC_DS, '/') . '\.(?!htaccess)|CVS)/Ss';
    }


    /**
     * Collect module classes structure into temporary classes catalog
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectClasses()
    {
        $classesTempDir = $this->getClassesTempDir();
        $classesDir     = $this->getModule()->getRootDirectory();

        if (!is_null($classesDir)) {

            \Includes\Utils\FileManager::mkdirRecursive($classesTempDir);

            \Includes\Utils\FileManager::copyRecursive(
                $classesDir,
                $classesTempDir,
                $this->getFileFilter()
            );
        }
    }


    /**
     * Collect module skins structure into temporary skins catalog
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function collectSkins()
    {
        if (!is_null($this->getTempDir())) {

            $skins = $this->getModule()->fetchSkins();

            foreach ($skins as $skinDir) {

                $skinsTempDir = $this->getSkinsTempDir($skinDir);
                $skinsDir     = $this->getModule()->constructSkinPath($skinDir);

                \Includes\Utils\FileManager::mkdirRecursive($skinsTempDir);

                \Includes\Utils\FileManager::copyRecursive(
                    $skinsDir, 
                    $skinsTempDir,
                    $this->getFileFilter()
                );
            }
        }
    }


    /**
     * Returns INI file path in the temporary local repository of module
     * 
     * @return string File path to the INI file
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getClassesTempDir()
    {
        return !is_null($this->getTempDir())
            ? $this->getTempDir() . self::CLASSES_DIR . LC_DS
            : null;
    }


    /**
     * Return temporary skins catalog of module
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSkinsTempDir($skinDir)
    {
        return $this->getTempDir() . self::SKINS_DIR . LC_DS . $skinDir;
    }


    /**
     * Module INI file creation routine
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createIniFile()
    {
        $result = self::STATUS_ERROR;

        if (!is_null($this->getTempDir())) {

            $section        = self::MODULE_SPECIFICATION;

            $module         = self::MODULE;
            $author         = self::MODULE_AUTHOR;
            $moduleDir      = self::MODULE_DIR;
            $moduleVersion  = self::MODULE_VERSION;

            $iniContent = <<<DATA
[{$section}]
 {$module} = "{$this->getModule()->getName()}"
 {$author} = "{$this->getModule()->getAuthor()}"
 {$moduleDir} = "{$this->getModule()->getName()}"
 {$moduleVersion} = "{$this->getModule()->getVersion()}"
DATA;

            $result = file_put_contents($this->getIniFile(), $iniContent);
        }

        return $result;
    }


    /**
     * Makes the unique temporary catalog for the temporary local repository of module
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function makeTempDir()
    {
        $fn = @tempnam(LC_TMP_DIR, 'phar_module');

        @unlink($fn);

        $this->tempDir = \Includes\Utils\FileManager::mkdirRecursive($fn) ? $fn . LC_DS : null;
    }

}
