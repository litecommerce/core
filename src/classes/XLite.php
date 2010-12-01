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
 * @subpackage Core
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Application singleton
 * TODO[SINGLETON] - lowest priority
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
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
     * Current area flag
     *
     * @var    bool
     * @access protected
     * @since  3.0.0
     */
    protected static $adminZone = false;

    /**
     * Called controller 
     * 
     * @var    \XLite\Controller\AController
     * @access protected
     * @since  3.0.0
     */
    protected static $controller = null;

    /**
     * Flag; determines if we need to cleanup (and, as a result, to rebuild) classes and templates cache
     *
     * @var    bool
     * @access protected
     * @since  3.0
     */
    protected static $isNeedToCleanupCache = false;

    /**
     * TODO - check if it's realy needed 
     * 
     * @var    mixed
     * @access protected
     * @since  3.0.0
     */
    protected $_xlite_form_id = null;

    /**
     * Current currency 
     * 
     * @var    \XLite\Model\Currency
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentCurrency;

    /**
     * It's not possible to instantiate this class using the "new" operator 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function __construct()
    {
    }

    /**
     * Return current target 
     * 
     * @return string
     * @access protected
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
     * @access protected
     * @since  3.0.0
     */
    protected static function getControllerClass()
    {
        return \XLite\Core\Converter::getControllerClass(self::getTarget());
    }

    /**
     * Return current action 
     * 
     * @return mixed
     * @access protected
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function clearDataOnStartup()
    {
        self::$controller = null;
        \XLite\Model\CachingFactory::clearCache();
    }
    

    /**
     * Chec - is admin interface or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function isAdminZone()
    {
        return self::$adminZone;
    }

    /**
     * Return specified (or the whole list) options 
     * 
     * @param mixed $names List (or single value) of option names OPTIONAL
     *  
     * @return mixed
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getShopUrl($url = '', $isSecure = false, array $params = array())
    {
        return \Includes\Utils\URLManager::getShopURL($url, $isSecure, $params);
    }

    /**
     * Return instance of the abstract factory sigleton 
     * 
     * @return \XLite\Model\Factory
     * @access public
     * @since  3.0.0
     */
    public function getFactory()
    {
        return \XLite\Model\Factory::getInstance();
    }


    /**
     * Ability to provoke cache cleanup (or to prevent it)
     * 
     * @param boolean $flag If it's needed to cleanup cache or not
     *  
     * @return void
     * @access public
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
     * @access public
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
     * @access public
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
     * Call application die (general routine) 
     * 
     * @param string $message Error message
     *  
     * @return void
     * @access public
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
     * @access public
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
     * @access public
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
     * @access public
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
     * Run application
     * 
     * @param boolean $adminZone Admin interface flag OPTIONAL
     *  
     * @return \XLite\View\AView
     * @access public
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

        // Set skin for admin area
        if (true === self::$adminZone) {
            \XLite\Model\Layout::getInstance()->setAdminSkin();
        }

        return $this;
    }

    /**
     * Get curent currency 
     * TODO - rework this to config ... or not?
     * 
     * @return \XLite\Model\Currency
     * @access public
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
}
