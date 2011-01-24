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
 * Module
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Module")
 * @Table  (name="modules",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="an", columns={"author","name"})
 *      },
 *      indexes={
 *          @Index (name="enabled", columns={"enabled"}),
 *          @Index (name="date", columns={"date"}),
 *          @Index (name="downloads", columns={"downloads"}),
 *          @Index (name="rating", columns={"rating"})
 *      }
 * )
 * @HasLifecycleCallbacks
 */
class Module extends \XLite\Model\AEntity
{
    /**
     * Remote status
     */
    const NOT_EXIST = 0;
    const EXISTS    = 1;

    /**
     * Common params
     */
    const UPLOAD_CODE_LENGTH = 32;
    const MARKETPLACE_URL    = 'https://www.litecommerce.com/marketplace/';
    const MODULE_UPLOAD_PATH = '%1$s/upload?code=%2$s';
    const MODULE_PAGE_PATH   = 'module/%1$s';

    /**
     * Module id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $moduleId;

    /**
     * Name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $name = '';

    /**
     * Author 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $author = '';

    /**
     * Enabled 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Installed status
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $installed = false;

    /**
     * Module data dump (YAML or SQL) installed status
     *
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $data_installed = false;

    /**
     * Status
     *
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $status = self::NOT_EXIST;

    /**
     * Description
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="text")
     */
    protected $description = '';

    /**
     * Module name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
    protected $moduleName = '';

    /**
     * Author name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
    protected $authorName = '';

    /**
     * Order creation timestamp
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Version
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
    protected $version = '';

    /**
     * Changelog
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="array", nullable=true)
     */
    protected $changelog = array();

    /**
     * Hash
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
    protected $hash = '';

    /**
     * Install pack hash
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
    protected $packHash = '';

    /**
     * Price
     *
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $price = 0;

    /**
     * Price
     *
     * @var    float
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
    protected $purchased = false;

    /**
     * Currency code
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=3)
     */
    protected $currency = 'USD';

    /**
     * Upload code
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
    protected $uploadCode = '';

    /**
     * Rating
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $rating = 0;

    /**
     * Downloads
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $downloads = 0;

    /**
     * Icon URL
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
    protected $iconURL = '';

    /**
     * Downloads
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="array", nullable=true)
     */
    protected $dependencies = array();

    /**
     * Old-state of enabled column
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $oldEnabled;

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
     * Main class 
     * 
     * @var    \Xite\Module\AModule
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mainClass = null; 


    /**
     * Check if module has icon
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasIcon()
    {
        return '' !== $this->getIconURL();
    }

    /**
     * Check if the module is free
     *
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFree()
    {
        return 0 >= $this->getPrice();
    }

    /**
     * Get marketplace URL
     * TODO: remove debug condition before release
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */

    public static function getMarketplaceURL()
    {
        $debugOptions = \XLite::getInstance()->getOptions('debug');

        return isset($debugOptions['marketplace_dev_url'])
            ? $debugOptions['marketplace_dev_url']
            : self::MARKETPLACE_URL;
    }

    /**
     * Get external page URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageURL()
    {
        return static::getMarketplaceURL() . sprintf(static::MODULE_PAGE_PATH, $this->getPath());
    }

    /**
     * Get author page URL
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAuthorPageURL()
    {
        return static::getMarketplaceURL() . sprintf(static::MODULE_PAGE_PATH, $this->getAuthor());
    }

    /**
     * Set enabled status
     * 
     * @param boolean $enabled Enabled status
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setEnabled($enabled)
    {
        $result = false;

        if (!$enabled || $this->canEnable()) {
            $this->oldEnabled = $this->enabled;
            $this->enabled = $enabled;
            $result = true;
        }

        return $result;
    }

    /**
     * Call module static method
     * 
     * @param string $method Method name
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callModuleStatic($method)
    {
        $class = $this->getMainClass();

        return ($class && method_exists($class, $method))
            ? call_user_func_array(array($class, $method), array_slice(func_get_args(), 1))
            : null;
    }

    /**
     * Prepare entity before update 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     * @PreUpdate
     */
    public function prepareUpdate()
    {
        if (isset($this->oldEnabled) && $this->oldEnabled != $this->enabled) {
            if ($this->enabled) {
                $this->prepareEnable();
            }
        }
    }

    /**
     * Prepare entity before enable 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareEnable()
    {
        \XLite\Core\Database::getInstance()->setDisabledStructures($this->getActualName());

        // Install YAML fixtures
        if ($this->getDataInstalled()) {
            $this->installWakeUpDBData();
            $this->restoreBackup();

        } else {
            $this->installDBData();
        }
    }

    /**
     * Prepare entity before disable 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function prepareDisable()
    {
        \XLite\Core\Database::getInstance()->setDisabledStructures(
            $this->getActualName(),
            $this->getModuleProtectedStructures()
        );

        $this->installSleepDBData();
        $this->saveBackup();
    }

    /**
     * Save backup 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function saveBackup()
    {
        $data = $this->callModuleStatic('getBackupData', $this);

        $path = $this->getBackupPath();
        if (is_array($data) && $data) {
            \XLite\Core\Operator::getInstance()->saveServiceYAML($path, $data);

        } elseif (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * Restore backup 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function restoreBackup()
    {
        $path = $this->getBackupPath();
        if (file_exists($path)) {
            \Includes\Decorator\Plugin\Doctrine\Utils\FixturesManager::addFixtureToList($path);
        }
    }

    /**
     * Get module backup path 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBackupPath()
    {
        return LC_VAR_DIR . 'backup' . LC_DS . $this->getAuthor() . '-' . $this->getName() . '.php';
    }

    /**
     * Install DB data 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function installDBData()
    {
        // Install YAML fixtures

        $path = $this->getRootDirectory() . 'install.yaml';

        if (file_exists($path)) {
            \Includes\Decorator\Plugin\Doctrine\Utils\FixturesManager::addFixtureToList($path);
        }

        // Install SQL dump
        $path = $this->getRootDirectory() . 'install.sql';

        if (file_exists($path)) {
            try {
                \XLite\Core\Database::getInstance()->importSQLFromFile($path);

            } catch (\InvalidArgumentException $exception) {

            } catch (\PDOException $exception) {

            }
        }

        // Custom install
        $this->callModuleStatic('installModule', $this);

        $this->setDataInstalled(true);
    }

    /**
     * Uninstall DB data 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function uninstallDBData()
    {
        if ($this->callModuleStatic('uninstallModule', $this)) { 

            $upath = $this->getRootDirectory() . 'uninstall.yaml';
            $ipath = $this->getRootDirectory() . 'install.yaml';

            if (file_exists($upath)) {

                // Uninstall special YAML fixtures
                \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($upath);

            } elseif (file_exists($ipath)) {

                // Uninstall standart YAML fixtures
                \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($ipath);
            }
        }

        // Uninstall SQL dump
        $path = $this->getRootDirectory() . 'uninstall.sql';

        if (file_exists($path)) {
            try {
                \XLite\Core\Database::getInstance()->importSQLFromFile($path);

            } catch (\InvalidArgumentException $exception) {

            } catch (\PDOException $exception) {

            }
        }

        $this->setDataInstalled(false);
    }

    /**
     * Install wake-up DB data 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function installWakeUpDBData()
    {
        // Install YAML fixtures
        $path = $this->getRootDirectory() . 'wakeup.yaml';
        if (file_exists($path)) {
            \Includes\Decorator\Plugin\Doctrine\Utils\FixturesManager::addFixtureToList($path);
        }

        // Install SQL dump
        $path = $this->getRootDirectory() . 'wakeup.sql';

        if (file_exists($path)) {
            try {
                \XLite\Core\Database::getInstance()->importSQLFromFile($path);

            } catch (\InvalidArgumentException $exception) {

            } catch (\PDOException $exception) {

            }
        }

        // Custom install
        $this->callModuleStatic('wakeUpModule', $this);
    }

    /**
     * Install sleep DB data 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function installSleepDBData()
    {
        // Install YAML fixtures
        $path = $this->getRootDirectory() . 'sleep.yaml';
        if (file_exists($path)) {
            \XLite\Core\Database::getInstance()->unloadFixturesFromYaml($path);
        }

        // Install SQL dump
        $path = $this->getRootDirectory() . 'sleep.sql';

        if (file_exists($path)) {
            try {
                \XLite\Core\Database::getInstance()->importSQLFromFile($path);

            } catch (\InvalidArgumentException $exception) {

            } catch (\PDOException $exception) {

            }
        }

        // Custom install
        $this->callModuleStatic('sleepModule', $this);
    }


    /**
     * Get translated dependencies
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDependedModules()
    {
        return $this->getRepository()->findAllByModuleIds($this->getDependedModuleIds());
    }

    /**
     * Disable module
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function disableModule()
    {
        $disableIds = array_merge(array($this->getModuleId()), $this->getDependedModuleIds());
        $this->getRepository()->updateInBatchById(array_fill_keys($disableIds, array('enabled' => false)));

        // Uninstall YAML
        foreach ($disableIds as $id) {
            $module = \XLite\Core\Database::getRepo('XLite\Model\Module')->find($id);
            if ($module) {
                $module->prepareDisable();
            }
        }

        // Rebuild Decorator-based modules list
        \Includes\Decorator\Utils\ModulesManager::removeFile();
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @access public
     * @since  1.0
     */
    public function getSettingsFormLink()
    {
        $link = $this->callModuleStatic('getSettingsForm');

        return is_null($link)
            ? \XLite\Core\Converter::buildURL('module', '', array('moduleId' => $this->getModuleId()), 'admin.php')
            : $link;
    }

    /**
     * Get module Main class
     * 
     * @return \XLite\Module\AModule
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMainClass()
    {
        if (!isset($this->mainClass) && $this->includeMainClass()) {
            $class = $this->getMainClassName();
            $this->mainClass = $class;

            if (!is_subclass_of($this->mainClass, '\XLite\Module\AModule')) {
                $this->mainClass = null;
            }
        }

        return $this->mainClass;
    }

    /**
     * Get dependencies modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDependenciesModules()
    {
        $qb    = $this->getRepository()->createQueryBuilder();
        $names = \XLite\Core\Database::buildInCondition($qb, $this->getDependencies(), 'classNames');
        $expr  = $qb->expr()->concat('m.author', $qb->expr()->concat(':delimiter', 'm.name'));

        $qb->setParameter('delimiter', '\\');

        foreach ($names as $k => $dp) {
            $qb->orWhere($qb->expr()->eq($expr, ':classNames' . $k));
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Check - can module enable or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function canEnable()
    {
        $status = true;

        // Check installed status
        if (!$this->getInstalled()) {
            $status = false;
        }

        // Check dependencies
        if ($status && $this->callModuleStatic('getDependencies')) {

            foreach ($this->getDependenciesModules() as $module) {

                if (!$module->getEnabled()) {
                    $status = false;
                    break;
                }

            }
        }

        // Check internal enviroment checker
        if ($status) {
            $module = $this->getMainClass();
            $status = $module::check();
        }

        return $status;
    }

    /**
     * Get module hash 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getHash()
    {
        $path = LC_CLASSES_DIR . $this->getPath() . LC_DS;
        $iterator = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::CHILD_FIRST);

        $list = array();
        foreach ($iterator as $f) {
            $list[] = $f->getRealPath();
        }

        sort($list);

        foreach ($list as $k => $path) {
            $list[$k] = hash_file('sha256', $path);
        }

        return hash('sh1512', implode('', $list));
    }

    /**
     * Create module
     * 
     * @param string $name   Module code (class)
     * @param string $author Module author (code)
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function create($name = null, $author = null)
    {
        // Set common properties
        $this->setName($name);
        $this->setAuthor($author);

        \XLite\Core\Database::getEM()->persist($this);
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Uninstall module
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function uninstall()
    {
        $status = true;

        // Remove repository (if needed)
        \Includes\Utils\FileManager::unlinkRecursive($this->getRootDirectory());

        // Remove skins catalogs (if needed)
        $skins = $this->fetchSkins();

        foreach ($skins as $skinDir) {
            \Includes\Utils\FileManager::unlinkRecursive($this->constructSkinPath($skinDir));
        }

        return $status;
    }

    /**
     * It's possible to call methods of certain module directly
     * 
     * @param string $method Method name
     * @param array  $args   Call arguments
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        return method_exists($this->getMainClass(), $method)
            ? call_user_func_array(array($this, 'callModuleStatic'), func_get_args())
            : parent::__call($method, $args);

    }

    /**
     * Check if newer version exists
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isUpdateAvailable()
    {
        return -1 === version_compare($this->getCurrentVersion(), $this->getLastVersion());
    }

    /**
     * Get last version (from the database)
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastVersion()
    {
        return $this->getVersion();
    }

    /**
     * Get installed version (from the Main class)
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrentVersion()
    {
        return $this->callModuleStatic('getVersion');
    }

    /**
     * Compose module actual name
     *
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActualName()
    {
        return \Includes\Decorator\Utils\ModulesManager::getActualName($this->getAuthor(), $this->getName());
    }

    /**
     * Get module root directory 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRootDirectory()
    {
        return LC_MODULES_DIR . $this->getPath() . LC_DS;
    }

    /**
     * Return relative module path
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPath()
    {
        return str_replace('\\', LC_DS, $this->getActualName());
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
            $request->url = self::getMarketplaceURL() 
                . sprintf(self::MODULE_UPLOAD_PATH, $this->getActualName(), $this->uploadCode);
            $request->method = 'GET';

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
     * Install (with upload) module. TODO (remove?)
     * 
     * @param boolean $overrideExists Override exist module OPTIONAL
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

    /**
     * Include module Main class
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function includeMainClass()
    {
        $class = $this->getMainClassName();

        if (
            !\XLite\Core\Operator::isClassExists($class)
            && file_exists(LC_CLASSES_DIR . str_replace('\\', LC_DS, $class) . '.php')
        ) {
            include_once LC_CLASSES_DIR . str_replace('\\', LC_DS, $class) . '.php';
        }

        return \XLite\Core\Operator::isClassExists($class);
    }

    /**
     * getDependencies 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDependencies()
    {
        return array_map(
            array('\Includes\Decorator\Utils\ModulesManager', 'composeDependency'),
            call_user_func(array($this->getMainClass(), __FUNCTION__))
        );
    }

    /**
     * Get module Main class name 
     * 
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMainClassName()
    {
        return '\XLite\Module\\' . $this->getActualName() . '\Main';
    }

    /**
     * Get inverted dependencies
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDependedModuleIds()
    {
        $dependencies = array();

        foreach ($this->getRepository()->getActiveModules() as $m) {

            $tmp = $m->getDependencies();
            if (
                !empty($tmp)
                && in_array($this->getActualName(), $tmp)
            ) {
                $dependencies[] = $m->getModuleId();
                $dependencies = array_merge($dependencies, $m->getDependedModuleIds());
            }
        }

        return array_unique($dependencies);
    }


    /**
     * Retrieve skins list from the temporary local repository of module
     * 
     * @return array List of skins in the format: {new skin path} => {skin path from temporary local repository of module}
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function fetchSkins()
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
            new \RecursiveDirectoryIterator(LC_SKINS_DIR),
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

        if (is_dir($this->constructSkinPath($subPath))) {

            $registry[] = $subPath;
        }
    }

    /** 
     * Construct relative path to module skin inside of LiteCommerce skin module repository
     * 
     * @param string $dir Catalog path
     *  
     * @return string Relative path to skin
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function constructSkinPath($dir)
    {   
        return LC_SKINS_DIR . $dir . LC_DS . 'modules' . LC_DS . $this->getPath();
    }   


    /**
     * Get module protected structures
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleProtectedStructures()
    {
        $tables = array();
        $columns = array();
        $tool = new \Doctrine\ORM\Tools\SchemaTool(\XLite\Core\Database::getEM());

        $tablePrefixLength = strlen(\XLite\Core\Database::getInstance()->getTablePrefix());

        $classPrefix = 'XLite\\Module\\' . $this->getActualName() . '\\Model\\';

        $pattern = $this->getRootDirectory() . 'Model' . LC_DS . '*.php';

        foreach (glob($pattern) as $path) {

            $class = $classPrefix . substr(basename($path), 0, -4);

            if (\XLite\Core\Operator::isClassExists($class)) {
                $reflection = new \ReflectionClass($class);

                if (
                    !in_array('XLite\Base\IDecorator', $reflection->getInterfaceNames())
                    && \XLite\Core\Database::getRepo($class)->canTableDisabled()
                ) {

                    // Protected tables                    
                    $table = substr(
                        \XLite\Core\Database::getEM()->getClassMetadata($class)->getTableName(),
                        $tablePrefixLength
                    );
                    $tables[] = $table;

                } elseif (in_array('XLite\Base\IDecorator', $reflection->getInterfaceNames())) {

                    // Protected columns
                    list($table, $cols) = $this->getModuleProtectedColumns($reflection, $path, $tool);
                    if ($table) {
                        $columns[$table] = $cols;
                    }
                }
            }
        }

        return array($tables, $columns);
    }

    /**
     * Get module protected columns 
     * 
     * @param \Reflection                    $reflection Class reflection data
     * @param string                         $path       Class repository path
     * @param \Doctrine\ORM\Tools\SchemaTool $tool       Doctrine schema tool
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getModuleProtectedColumns(\Reflection $reflection, $path, \Doctrine\ORM\Tools\SchemaTool $tool)
    {
        $cols = array();
        $table = null;

        foreach ($reflection->getProperties(\ReflectionProperty::IS_PROTECTED) as $col) {
            if ($col->getDeclaringClass() == $reflection && preg_match('/@Column/Ss', $col->getDocComment())) {
                $cols[$col->getName()] = false;
            }
        }

        if ($cols && preg_match('/class\s+\S+\s+extends\s+(\S+)\s/Ss', file_get_contents($path), $match)) {
            $original = ltrim($match[1], '\\');
            $cm = \XLite\Core\Database::getEM()->getClassMetadata($original);

            $schema = $tool->getCreateSchemaSql(array($cm));
                        
            foreach ($cols as $col => $tmp) {
                if (preg_match('/((?:, |\()' . $col . ' .+(?:, |\)))/USs', $schema[0], $match)) {
                    $cols[$col] = trim($match[1], ', ');

                } else {
                    unset($cols[$col]);
                }
            }

            if ($cols) {
                $table = substr(
                    $cm->getTableName(),
                    strlen(\XLite\Core\Database::getInstance()->getTablePrefix())
                );
            }
        }

        return array($table, $cols);

    }

}
