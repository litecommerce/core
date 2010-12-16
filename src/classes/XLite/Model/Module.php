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
 *          @Index (name="enabled", columns={"enabled"})
 *      }
 * )
 */
class Module extends \XLite\Model\AEntity
{
    /**
     * Installed statuses
     * TODO: to revise
     */

    const NOT_INSTALLED     = 0;
    const INSTALLED         = 1;
    const INSTALLED_WO_SQL  = 2;
    const INSTALLED_WO_PHP  = 3;
    const INSTALLED_WO_CTRL = 4;

    /**
     * Remote status
     */
    const NOT_EXIST = 0;
    const EXISTS    = 1;
    const OBSOLETE  = 2;

    const UPLOAD_CODE_LENGTH = 32;

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
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $installed = self::NOT_INSTALLED;

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
     * @Column (type="text")
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
     * Main class 
     * 
     * @var    \Xite\Module\AModule
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $mainClass = null; 


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
            $this->enabled = $enabled;
            $result = true;
        }

        return $result;
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
        $link = $this->__call('getSettingsForm');

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
        if (self::INSTALLED != $this->getInstalled()) {
            $status = false;
        }

        // Check dependencies
        if ($status && $this->__call('getDependencies')) {

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
        $class = $this->getMainClassName();

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
     * TODO: to test this, when author code changes are implemented
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
        // Seet common properties
        $this->setName($name);
        $this->setAuthor($author);
        $this->setInstalled(self::NOT_INSTALLED);
        $this->setEnabled(false);

        $status = self::INSTALLED;

        $mainClass = $this->getMainClass();

        if ($mainClass) {

            // Install YAML fixtures
            $installYAMLPath = LC_MODULES_DIR . $name . LC_DS . 'install.yaml';
            if (file_exists($installYAMLPath)) {
                try {
                    $loadedLines = \XLite\Core\Database::getInstance()
                        ->loadFixturesFromYaml($installYAMLPath);
                    if (false === $loadedLines) {
                        $status = self::INSTALLED_WO_SQL;
                    }
    
                } catch (\Exception $e) {
                    \XLite\Logger::getInstance()->log($e->getMessage(), LOG_ERR);
                    $status = self::INSTALLED_WO_SQL;
                }

            }

            // Install SQL dump
            $installSQLPath = LC_MODULES_DIR . $name . LC_DS . 'install.sql';

            if (file_exists($installSQLPath)) {
                try {
                    \XLite\Core\Database::getInstance()->importSQLFromFile($installSQLPath);

                } catch (\InvalidArgumentException $exception) {

                    \XLite\Logger::getInstance()->log($exception->getMessage(), PEAR_LOG_ERR);
                    $status = self::INSTALLED_WO_SQL;

                } catch (\PDOException $exception) {

                    \XLite\Logger::getInstance()->log($exception->getMessage(), PEAR_LOG_ERR);
                    $status = self::INSTALLED_WO_SQL;
                }
            }

            // Run custom install code
            /* FIXME - obsolete code 
            if (false === $mainClass->installModule($this)) {
                \XLite\Logger::getInstance()->log(
                    sprintf('\'%s\' module custom installation error', $name),
                    PEAR_LOG_ERR
                );
                $status = self::INSTALLED_WO_PHP;
            }
            */

        } else {
            $status = self::INSTALLED_WO_CTRL;
        }

        $errorMessage = null;

        switch ($status) {
            case self::INSTALLED:
                $errorMessage = 'The X module has been installed successfully';
                break;

            case self::INSTALLED_WO_SQL:
                $errorMessage = 'The X module has been installed with errors: the DB has not been modified correctly';
                break;

            case self::INSTALLED_WO_PHP:
                $errorMessage = 'The X module has been installed incorrectly. Please see the logs for more information';
                break;

            case self::INSTALLED_WO_CTRL:
                $errorMessage = 'The X module has been installed, but the module has a wrong module control class';
                break;

            default:

        }

        if ($errorMessage) {
            \XLite\Logger::getInstance()->log(
                \XLite\Core\Translation::lbl($errorMessage, array('module' => $name)),
                PEAR_LOG_ERR
            );
        }

        $this->setInstalled($status);
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

        // Uninstall SQL
        $installSQLPath = LC_MODULES_DIR . $this->getPath() . LC_DS . 'uninstall.sql';
        if (file_exists($installSQLPath)) {
            try {
                \XLite\Core\Database::getInstance()->importSQLFromFile($installSQLPath);

            } catch (\InvalidArgumentException $exception) {

                \XLite\Logger::getInstance()->log($exception->getMessage(), PEAR_LOG_ERR);
                $status = false;

            } catch (\PDOException $exception) {

                \XLite\Logger::getInstance()->log($exception->getMessage(), PEAR_LOG_ERR);
                $status = false;
            }
        }

        // Run custom uninstall code
        if (false === $this->getMainClass()->uninstallModule($this)) {
            \XLite\Logger::getInstance()->log(
                sprintf('\'%s\' module custom deinstallation error', $this->getActualName()),
                PEAR_LOG_ERR
            );
            $status = false;
        }

        // Remove repository (if needed)
        \Includes\Utils\FileManager::unlinkRecursive(LC_MODULES_DIR . $this->getPath());

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
            ? call_user_func_array(array($this->getMainClass(), $method), $args)
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
        return $this->__call('getVersion');
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

}
