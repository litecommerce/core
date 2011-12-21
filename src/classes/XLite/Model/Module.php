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

namespace XLite\Model;

/**
 * Module
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @Entity (repositoryClass="\XLite\Model\Repo\Module")
 * @Table  (name="modules",
 *      uniqueConstraints={
 *          @UniqueConstraint (name="moduleVersion", columns={"author","name","majorVersion","minorVersion","fromMarketplace"})
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
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column         (type="integer")
     */
    protected $moduleID;

    /**
     * Name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $name;

    /**
     * Author
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length="64")
     */
    protected $author;

    /**
     * Enabled
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Installed status
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $installed = false;

    /**
     * Order creation timestamp
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $date = 0;

    /**
     * Rating
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=4)
     */
    protected $rating = 0;

    /**
     * Votes
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $votes = 0;

    /**
     * Downloads
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $downloads = 0;

    /**
     * Price
     *
     * @var   float
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="decimal", precision=14, scale=2)
     */
    protected $price = 0.00;

    /**
     * Currency code
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=3)
     */
    protected $currency = 'USD';

    /**
     * Major version
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=8)
     */
    protected $majorVersion;

    /**
     * Minor version
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=8)
     */
    protected $minorVersion;

    /**
     * Revision date
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="integer")
     */
    protected $revisionDate = 0;

    /**
     * Module pack size (received from marketplace)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="bigint")
     */
    protected $packSize = 0;

    /**
     * Module name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $moduleName;

    /**
     * Author name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $authorName;

    /**
     * Description
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="text")
     */
    protected $description = '';

    /**
     * Icon URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $iconURL = '';

    /**
     * Icon URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $pageURL = '';

    /**
     * Icon URL
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="string", length=255)
     */
    protected $authorPageURL = '';

    /**
     * Module dependencies
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="array")
     */
    protected $dependencies = array();

    /**
     * Flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     *
     * @Column (type="boolean")
     */
    protected $fromMarketplace = false;

    /**
     * Public identifier (cache)
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $marketplaceID;


    // {{{ Routines to access methods of (non)installed modules

    /**
     * Return main class name for current module
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMainClass()
    {
        return '\XLite\Module\\' . $this->getActualName() . '\Main';
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
     * @since  1.0.0
     */
    public function callModuleMethod($method, $result = null, array $args = array())
    {
        return $this->checkModuleMainClass()
            ? call_user_func_array(array($this->getMainClass(), $method), $args)
            : $result;
    }

    /**
     * Check if we can call method from the module main class
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function checkModuleMainClass()
    {
        return $this->isInstalled() && \Includes\Utils\Operator::checkIfClassExists($this->getMainClass());
    }

    // }}}

    // {{{ Some common getters and setters

    /**
     * Compose module actual name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getActualName()
    {
        return \Includes\Utils\ModulesManager::getActualName($this->getAuthor(), $this->getName());
    }

    /**
     * Return module full version
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
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
     * @since  1.0.0
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
     * @since  1.0.0
     */
    public function getSettingsForm()
    {
        return $this->callModuleMethod('getSettingsForm')
            ?: \XLite\Core\Converter::buildURL('module', '', array('moduleId' => $this->getModuleId()), 'admin.php');
    }

    /**
     * Get list of dependency modules as Doctrine entities
     *
     * @param mixed $onlyDisabled Flag OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getDependencyModules($onlyDisabled = false)
    {
        $result  = array();
        $classes = array_fill_keys($this->getDependencies(), true);

        if (!empty($classes)) {
            foreach ($this->getRepository()->getDependencyModules($classes) as $module) {
                unset($classes[$module->getActualName()]);

                if (!($onlyDisabled && $module->getEnabled())) {
                    $result[] = $module;
                }
            }

            foreach ($classes as $class => $tmp) {
                list($author, $name) = explode('\\', $class);

                $module = new \XLite\Model\Module();
                $module->setName($name);
                $module->setAuthor($author);
                $module->setModuleName($name);
                $module->setAuthorName($author);
                $module->setEnabled(false);
                $module->setInstalled(false);

                $result[] = $module;
            }
        }

        return $result;
    }

    /**
     * Get list of dependent modules as Doctrine entities
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getDependentModules()
    {
        $result  = array();
        $current = \Includes\Decorator\ADecorator::getModulesGraph()->find($this->getActualName());

        if ($current) {
            foreach ($current->getChildren() as $node) {
                $class = $node->getActualName();
                $result[$class] = $this->getRepository()
                    ->findOneBy(array_combine(array('author', 'name'), explode('\\', $class)));
            }
        }

        return array_filter($result);
    }

    /**
     * Check if the module is free
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isFree()
    {
        return 0 >= $this->getPrice();
    }

    /**
     * Check if module is already purchased
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isPurchased()
    {
        return (bool) $this->getLicenseKey();
    }

    /**
     * Check for custom module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isCustom()
    {
        $module = $this->getRepository()->getModuleFromMarketplace($this);

        return !isset($module) || $module->getModuleID() === $this->getModuleID();
    }

    /**
     * Search for license key
     *
     * @return \XLite\Model\ModuleKey
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getLicenseKey()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\ModuleKey')->findKey($this->getAuthor(), $this->getName());
    }

    /**
     * Return currency for paid modules
     *
     * @return \XLite\Model\Currency
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCurrency()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Currency')->findOneByCode($this->currency)
            ?: $this->currency;
    }

    /**
     * Check if module is installed in LC
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function isInstalled()
    {
        return $this->installed ?: (bool) $this->getRepository()->getModuleInstalled($this);
    }

    /**
     * Return some data to identify module
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.6
     */
    public function getIdentityData()
    {
        return array(
            'author'       => $this->getAuthor(),
            'name'         => $this->getName(),
            'majorVersion' => $this->getMajorVersion(),
            'minorVersion' => $this->getMinorVersion(),
        );
    }

    /**
     * Generate marketplace ID
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getMarketplaceID()
    {
        if (!isset($this->marketplaceID)) {
            $this->marketplaceID = md5(implode('', $this->getIdentityData()));
        }

        return $this->marketplaceID;
    }

    // }}}

    // {{{ Change module state routines

    /**
     * Lifecycle callback
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.12
     *
     * @PreUpdate
     */
    public function prepareBeforeUpdate()
    {
        $changeSet = \XLite\Core\Database::getEM()->getUnitOfWork()->getEntityChangeSet($this);

        if (!empty($changeSet['enabled'])) {
            \XLite\Core\Database::getInstance()->setDisabledStructures(
                $this->getActualName(),
                $this->getEnabled()
                    ? array()
                    : \Includes\Utils\ModulesManager::getModuleProtectedStructures($this->getAuthor(), $this->getName())
            );
        }
    }

    // }}}
}
