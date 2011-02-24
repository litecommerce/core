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

/**
 * Application singleton
 * TODO[SINGLETON] - lowest priority
 * 
 * @see   ____class_see____
 * @since 3.0.0
 */
class XLite extends \XLite\Base
{
    /**
     * Endpoints
     */

    const CART_SELF  = 'cart.php';
    const ADMIN_SELF = 'admin.php';

    /**
     * This target will be used if the "target" params is not passed in the request
     */

    const TARGET_DEFAULT = 'main';


    /**
     * Interfaces codes
     */

    const ADMIN_INTERFACE    = 'admin';
    const CUSTOMER_INTERFACE = 'customer';
    const CONSOLE_INTERFACE  = 'console';
    const MAIL_INTERFACE     = 'mail';
    const COMMON_INTERFACE   = 'common';


    /**
     * Current area flag
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $adminZone = false;

    /**
     * Called controller 
     * 
     * @var   \XLite\Controller\AController
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $controller = null;

    /**
     * Flag; determines if we need to cleanup (and, as a result, to rebuild) classes and templates cache
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected static $isNeedToCleanupCache = false;

    /**
     * TODO - check if it's realy needed 
     * 
     * @var   mixed
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $_xlite_form_id = null;

    /**
     * Current currency 
     * 
     * @var   \XLite\Model\Currency
     * @see   ____var_see____
     * @since 3.0.0
     */
    protected $currentCurrency;

    /**
     * Chec - is admin interface or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isAdminZone()
    {
        return self::$adminZone;
    }

    /**
     * Ability to provoke cache cleanup (or to prevent it)
     * 
     * @param boolean $flag If it's needed to cleanup cache or not
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function setCleanUpCacheFlag($flag)
    {
        static::$isNeedToCleanupCache = (true === $flag);
    }

    /**
     * Get controller
     *
     * @return \XLite\Controller\AController
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getController()
    {
        if (!isset(self::$controller)) {
            $class = self::getControllerClass();
            if (!\XLite\Core\Operator::isClassExists($class)) {
                \XLite\Core\Request::getInstance()->target = self::TARGET_DEFAULT;
                \XLite\Logger::getInstance()->log('Controller class ' . $class . ' not found!', LOG_ERR);
                \XLite\Core\Operator::getInstance()->display404();
            }

            self::$controller = new $class(\XLite\Core\Request::getInstance()->getData());
            self::$controller->init();
        }

        return self::$controller;
    }

    /**
     * Set controller 
     * FIXME - to delete
     * 
     * @param mixed $controller Controller OPTIONAL
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function setController($controller = null)
    {
        if (is_null($controller) || $controller instanceof \XLite\Controller\AController) {
            self::$controller = $controller;
        }
    }

    /**
     * Return current target
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getTarget()
    {
        $target = \XLite\Core\Request::getInstance()->target;

        if (empty($target)) {
            \XLite\Core\Request::getInstance()->target = $target = self::TARGET_DEFAULT;
        }

        return $target;
    }

    /**
     * Assemble and get controller class name
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected static function getControllerClass()
    {
        return \XLite\Core\Converter::getControllerClass(self::getTarget());
    }

    /**
     * Return specified (or the whole list) options 
     * 
     * @param mixed $names List (or single value) of option names OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOptions($names = null)
    {
        return \Includes\Utils\ConfigParser::getOptions($names);
    }

    /**
     * Clean up classes cache (if needed) 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __destruct()
    {
        if (static::$isNeedToCleanupCache) {
            \Includes\Decorator\Utils\CacheManager::cleanupCacheIndicators();
        }
    }

    /**
     * Return current endpoint script
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getScript()
    {
        return self::isAdminZone() ? self::ADMIN_SELF : self::CART_SELF;
    }

    /**
     * Return full URL for the resource
     * 
     * @param string  $url      Url part to add OPTIONAL
     * @param boolean $isSecure Use HTTP or HTTPS OPTIONAL
     * @param array   $params   Optional URL params OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShopURL($url = '', $isSecure = false, array $params = array())
    {
        return \Includes\Utils\URLManager::getShopURL($url, $isSecure, $params);
    }

    /**
     * Return instance of the abstract factory sigleton 
     * 
     * @return \XLite\Model\Factory
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFactory()
    {
        return \XLite\Model\Factory::getInstance();
    }

    /**
     * Call application die (general routine) 
     * 
     * @param string $message Error message
     *  
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function doGlobalDie($message)
    {
        $this->doDie($message);
    }

    /**
     * Initialize all active modules 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initModules()
    {
        \XLite\Core\Database::getRepo('XLite\Model\Module')->initialize();
    }

    /**
     * Perform an action and redirect
     * 
     * @return boolean 
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function runController()
    {
        return $this->getController()->handleRequest();
    }

    /**
     * Return viewer object
     * 
     * @return \XLite\View\Controller|void
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getViewer()
    {
        $this->runController();

        $viewer = $this->getController()->getViewer();
        $viewer->init();

        return $viewer;
    }

    /**
     * Process request 
     * 
     * @return \XLite
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processRequest()
    {
        $this->runController();

        $this->getController()->processRequest();

        return $this;
    }

    /**
     * Run application
     * 
     * @param boolean $adminZone Admin interface flag OPTIONAL
     *  
     * @return \XLite
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function run($adminZone = false)
    {
        // Set current area
        self::$adminZone = (bool)$adminZone;

        // Clear some data
        self::clearDataOnStartup();

        // Initialize logger
        \XLite\Logger::getInstance();

        // Initialize modules
        $this->initModules();

        if (\XLite\Core\Request::getInstance()->isCLI()) {

            // Set skin for console interface
            \XLite\Core\Layout::getInstance()->setConsoleSkin();

        } elseif (true === self::$adminZone) {

            // Set skin for admin interface
            \XLite\Core\Layout::getInstance()->setAdminSkin();
        }

        return $this;
    }

    /**
     * Get curent currency 
     * TODO - rework this to config ... or not?
     * 
     * @return \XLite\Model\Currency
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCurrency()
    {
        if (!isset($this->currentCurrency)) {
            $this->currentCurrency = \XLite\Core\Database::getRepo('XLite\Model\Currency')->findDetached(840);
        }

        return $this->currentCurrency;
    }

    /**
     * Return current action 
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAction()
    {
        return \XLite\Core\Request::getInstance()->action;
    }

    /**
     * Clear some data
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearDataOnStartup()
    {
        self::$controller = null;
        \XLite\Model\CachingFactory::clearCache();
    }
    
}
