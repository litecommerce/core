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
 * Module
 * 
 * @see   ____class_see____
 * @since 3.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Module")
 * @Table  (name="modules",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="moduleVersion", columns={"author","name","majorVersion","minorVersion"}),
            @UniqueConstraint (name="moduleInstalled", columns={"author","name","installed"})
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
     * Module ID
     * 
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $moduleID;

    /**
     * Name 
     * 
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $name;

    /**
     * Author 
     * 
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $author;

    /**
     * Public identifier
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="32")
     */
    protected $marketplaceID = '';

    /**
     * Enabled 
     * 
     * @var    boolean
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
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $installed = false;

    /**
     * Module data dump (YAML or SQL) installed status
     *
     * :TODO: check if it's really needed
     *
     * @var    boolean
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
    protected $dataInstalled = false;

    /**
     * Order creation timestamp
     * 
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Rating
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $rating = 0;

    /**
     * Downloads
     *
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $downloads = 0;

    /**
     * Price
     *
     * @var    float
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $price = 0.00;

    /**
     * Currency code
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=3)
     */
    protected $currency = 'USD';

    /**
     * Major version
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=8)
     */
    protected $majorVersion;

    /**
     * Minor version
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=8)
     */
    protected $minorVersion;

    /**
     * Revision date
     *
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $revisionDate = 0;

    /**
     * Module name
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $moduleName;

    /**
     * Author name
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $authorName;

    /**
     * Description
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="text")
     */
    protected $description = '';

    /**
     * Icon URL
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $iconURL = '';

    /**
     * Icon URL
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $pageURL = '';

    /**
     * Icon URL
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $authorPageURL = '';

    /**
     * Module dependencies
     * 
     * @var   array
     * @see   ____var_see____
     * @since 3.0.0
     *
     * @Column (type="array")
     */
    protected $dependencies = array();


    // {{{ Routines to access methods of (non)installed modules

    /**
     * Getter
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMajorVersion()
    {
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
        return $this->callModuleMethod('getMajorVersion', $this->majorVersion);
    }

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMinorVersion()
	{
        // Do not replace the first argument by the 
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getMinorVersion', $this->minorVersion);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getModuleName()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getModuleName', $this->moduleName);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAuthorName()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getAuthorName', $this->authorName);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDescription()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getDescription', $this->description);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getIconURL()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getIconURL', $this->iconURL);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageURL()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getPageURL', $this->pageURL);
	}

    /**
     * Getter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAuthorPageURL()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getAuthorPageURL', $this->authorPageURL);
	}

    /**
     * Getter
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDependencies()
	{
        // Do not replace the first argument by the
        // magic constant "__FUNCTION__": their are the same "accidentally"
		return $this->callModuleMethod('getDependencies', $this->dependencies);
	}

    /**
     * Method to call functions from module main classes
     * 
     * @param string $method Method to call
     * @param mixed  $result Method return value for the current class (model) OPTIONAL
     * @param array  $args   Call arguments OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function callModuleMethod($method, $result = null, array $args = array())
    {
        return $this->getInstalled() ? call_user_func_array(array($this->getMainClass(), $method), $args) : $result;
    }

    /**
     * Return main class name for current module
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMainClass()
    {
        return '\XLite\Module\\' . $this->getActualName() . '\Main';
    }

    // }}}

    // {{{ Some common getters and setters

    /**
     * Compose module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getActualName()
    {
        return \Includes\Decorator\Utils\ModulesManager::getActualName($this->getAuthor(), $this->getName());
    }

    /**
     * Return module full version
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getVersion()
    {
        return \Includes\Utils\Converter::composeVersion($this->getMajorVersion(), $this->getMinorVersion());
    }

    /**
     * Check if module has a custom icon
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function hasIcon()
    {
        return (bool) $this->getIconURL();
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSettingsForm()
    {
        return $this->callModuleMethod('getSettingsForm')
            ?: \XLite\Core\Converter::buildURL('module', '', array('moduleId' => $this->getModuleId()), 'admin.php');
    }

    /**
     * Get list of dependent modules as Doctrine entities
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDependentModules()
    {
        $result = array();

        foreach ($this->getDependencies() as $class) {

            list($author, $name) = explode('\\', $class);

            $module = $this->getRepository()->findOneBy(
                array(
                    'author' => $author,
                    'name'   => $name,
                )
            );

            if ($module) {

                $result[$class] = $module;
            }
        }

        return $result;
    }

    /**
     * Check if the module is free
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isFree()
    {
        return 0 >= $this->getPrice();
    }

    /**
     * Check if module is already purchased
     *
     * :TODO: add code here
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPurchased()
    {
        return true;
    }

    /**
     * Get module root directory
     *
     * @return string
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
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPath()
    {
        return str_replace('\\', LC_DS, $this->getActualName());
    }

    // }}}










    /**
     * Remote status
     */
/*    const NOT_EXIST = 0;
    const EXISTS    = 1;

    /**
     * Common params
     */
/*    const UPLOAD_CODE_LENGTH = 32;
    const MARKETPLACE_URL    = 'https://www.litecommerce.com/marketplace/';
    const MODULE_UPLOAD_PATH = '%1$s/upload?code=%2$s';
    const MODULE_PAGE_PATH   = 'module/%1$s';

    /**
     * Module id 
     * 
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
//    protected $moduleId;

    /**
     * Name 
     * 
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
//    protected $name;

    /**
     * Author 
     * 
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="string", length="64")
     */
//    protected $author;

    /**
     * Enabled 
     * 
     * @var    boolean
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
//    protected $enabled = false;

    /**
     * Installed status
     * 
     * @var    boolean
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
//    protected $installed = false;

    /**
     * Module data dump (YAML or SQL) installed status
     *
     * @var    boolean
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="boolean")
     */
//    protected $data_installed = false;

    /**
     * Status
     *
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
//    protected $status = self::NOT_EXIST;

    /**
     * Description
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="text")
     */
//    protected $description = '';

    /**
     * Module name
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
//    protected $moduleName = '';

    /**
     * Author name
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
//    protected $authorName = '';

    /**
     * Order creation timestamp
     * 
     * @var    integer
     * @see    ____var_see____
     * @since  3.0.0
     *
     * @Column (type="integer")
     */
//    protected $date = 0;

    /**
     * Version
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
//    protected $version = '';

    /**
     * Changelog
     *
     * @var    array
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="array", nullable=true)
     */
//    protected $changelog = array();

    /**
     * Hash
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
//    protected $hash = '';

    /**
     * Install pack hash
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=32)
     */
//    protected $packHash = '';

    /**
     * Price
     *
     * @var    float
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="decimal", precision=14, scale=2)
     */
//    protected $price = 0;

    /**
     * Price
     *
     * @var    float
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
//    protected $purchased = false;

    /**
     * Currency code
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=3)
     */
//    protected $currency = 'USD';

    /**
     * Upload code
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
//    protected $uploadCode = '';

    /**
     * Rating
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
//    protected $rating = 0;

    /**
     * Downloads
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
//    protected $downloads = 0;

    /**
     * Icon URL
     *
     * @var    string
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length=255)
     */
//    protected $iconURL = '';

    /**
     * Downloads
     *
     * @var    array
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="array", nullable=true)
     */
//    protected $dependencies = array();

    /**
     * Old-state of enabled column
     * 
     * @var   boolean
     * @see   ____var_see____
     * @since 3.0.0
     */
//    protected $oldEnabled;

    /**
     * Model (cache)
     *
     * @var   \XLite\Model\Module
     * @see   ____var_see____
     * @since 3.0.0
     */
//    protected $model = null;

    /**
     * Main class 
     * 
     * @var   \Xite\Module\AModule
     * @see   ____var_see____
     * @since 3.0.0
     */
//    protected $mainClass = null; 


    /**
     * Check if module has icon
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function hasIcon()
    {
        return '' !== $this->getIconURL();
    }

    /**
     * Check if the module is free
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function isFree()
    {
        return 0 >= $this->getPrice();
    }

    /**
     * Get external page URL
     *
     * :TODO: [MARKETPLACE]
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getPageURL()
    {
        return \XLite\Core\Marketplace::getInstance()->getMarketplaceURL() . sprintf(static::MODULE_PAGE_PATH, $this->getPath());
    }

    /**
     * Get author page URL
     *
     * :TODO: [MARKETPLACE]
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getAuthorPageURL()
    {
        return \XLite\Core\Marketplace::getInstance()->getMarketplaceURL() . sprintf(static::MODULE_PAGE_PATH, $this->getAuthor());
    }

    /**
     * Set enabled status
     * 
     * @param boolean $enabled Enabled status
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function setEnabled($enabled)
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function callModuleStatic($method)
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
     * @see    ____func_see____
     * @since  3.0.0
     * @PreUpdate
     */
/*    public function prepareUpdate()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function prepareEnable()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function prepareDisable()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function saveBackup()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function restoreBackup()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getBackupPath()
    {
        return LC_VAR_DIR . 'backup' . LC_DS . $this->getAuthor() . '-' . $this->getName() . '.php';
    }

    /**
     * Install DB data 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function installDBData()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function uninstallDBData()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function installWakeUpDBData()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function installSleepDBData()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getDependedModules()
    {
        return $this->getRepository()->findAllByModuleIds($this->getDependedModuleIds());
    }

    /**
     * Disable module
     *
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function disableModule()
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
     * @since  1.0
     */
/*    public function getSettingsFormLink()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getMainClass()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getDependenciesModules()
    {
        return $this->getRepository()->findDependenciesByModule($this);
    }

    /**
     * Check - can module enable or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function canEnable()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getHash()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function create($name = null, $author = null)
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function uninstall()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function __call($method, array $args = array())
    {
        return method_exists($this->getMainClass(), $method)
            ? call_user_func_array(array($this, 'callModuleStatic'), func_get_args())
            : parent::__call($method, $args);

    }

    /**
     * Compose module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getActualName()
    {
        return \Includes\Decorator\Utils\ModulesManager::getActualName($this->getAuthor(), $this->getName());
    }

    /**
     * Get module root directory 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getRootDirectory()
    {
        return LC_MODULES_DIR . $this->getPath() . LC_DS;
    }

    /**
     * Return relative module path
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getPath()
    {
        return str_replace('\\', LC_DS, $this->getActualName());
    }

    /**
     * Get model 
     * 
     * @param boolean $overrideCache Ovveride internal cache OPTIONAL
     *  
     * @return \XLite\Model\Module
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getModel($overrideCache = false)
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function canUpload()
    {
        return self::UPLOAD_CODE_LENGTH == strlen($this->uploadCode);
    }

    /**
     * Depack install pack
     * 
     * @param string $path Install pack path
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function depack($path)
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function includeMainClass()
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
     * Get dependencies 
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function getCalculatedDependencies()
    {
        return call_user_func(array($this->getMainClass(), 'getDependencies'));
    }

    /**
     * Get module Main class name 
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getMainClassName()
    {
        return '\XLite\Module\\' . $this->getActualName() . '\Main';
    }

    /**
     * Get inverted dependencies
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getDependedModuleIds()
    {
        $dependencies = array();

        foreach ($this->getRepository()->getActiveModules() as $m) {

            $tmp = $m->getCalculatedDependencies();
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function fetchSkins()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getFetchSkinsIterator()
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function registerFetchedSkin(\RecursiveIteratorIterator $iterator, &$registry)
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
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    public function constructSkinPath($dir)
    {   
        return LC_SKINS_DIR . $dir . LC_DS . 'modules' . LC_DS . $this->getPath();
    }   


    /**
     * Get module protected structures
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getModuleProtectedStructures()
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

                if (!$reflection->isSubclassOf('\XLite\Model\AEntity')) {
                    // Do nothing - class is not Doctrine-based model

                } elseif (
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
     * @param \ReflectionClass               $reflection Class reflection data
     * @param string                         $path       Class repository path
     * @param \Doctrine\ORM\Tools\SchemaTool $tool       Doctrine schema tool
     *  
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
/*    protected function getModuleProtectedColumns(\ReflectionClass $reflection, $path, \Doctrine\ORM\Tools\SchemaTool $tool)
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

    }*/
}
