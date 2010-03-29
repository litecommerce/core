<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * CMS connector
 *  
 * @category  Litecommerce
 * @package   Core
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Singleton to connect to a CMS
 *                         
 * @package    Core
 * @since      3.0                   
 */
abstract class XLite_Core_CMSConnector extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Current CMS name
     * 
     * @var    booln
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected static $currentCMS = null;

	/**
	 * List of widgets which can be exported
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $widgetsList = array(
		'XLite_View_TopCategories'    => 'Categories list',
        'XLite_View_Minicart'         => 'Minicart',
        'XLite_View_Subcategories'    => 'Subcategories',
        'XLite_View_CategoryProducts' => 'Category products list',
        'XLite_View_ProductBox'       => 'Product block',
	);

    /**
     * Page types 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pageTypes = array(
        'XLite_Controller_Customer_Category'  => 'Category page',
        'XLite_Controller_Customer_Product'   => 'Product page',
        'XLite_Controller_Customer_Cart'      => 'Shopping cart',
        'XLite_Controller_Customer_Checkout'  => 'Checkout',
        'XLite_Controller_Customer_OrderList' => 'Orders list',
    );

    /**
     * Top-level viewer 
     * 
     * @var    XLite_View_Controller
     * @access protected
     * @since  3.0.0
     */
    protected $viewer = null;


	/**
	 * It's not possible to instantiate this class using the "new" operator
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function __construct()
	{
	}

    /**
     * Return initialized instance of the XLite_View_Conroller viewer 
     * 
     * @param bool $runController execute or not "handleRequest()" function
     *  
     * @return XLite_View_Conroller
     * @access protected
     * @since  3.0.0
     */
    protected function getViewer($runController = false)
    {
        if (!isset($this->viewer)) {
            $this->viewer = XLite::getInstance()->run(false, $runController, true);
        }

        return $this->viewer;
    }

    /**
     * getFlags 
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function getFlags()
    {
        return array(XLite_View_Abstract::PARAM_IS_EXPORTED => true);
    }

	/**
	 * Prepare attributes before set them to a widget
	 * 
	 * @param array $attributes attributes to prepare
	 *  
	 * @return array
	 * @access protected
	 * @since  3.0.0
	 */
	protected function prepareAttributes(array $attributes)
	{
        return $attributes + $this->getFlags();
	}


	/**
	 * Method to access the singleton 
	 * 
	 * @return XLite_Core_CMSConnector
	 * @access public
	 * @since  3.0
	 */
	public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

	/**
     * Return currently used CMS name
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    abstract public function getCMSName();


    /**
     * Save passed params in the requester 
     * 
     * @param array $request params to map
     *  
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function mapRequest(array $request)
    {
        XLite_Core_Request::getInstance()->mapRequest($request);
    }

    /**
     * Handler should called this function first to prevent any possible conflicts
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        self::$currentCMS = $this->getCMSName();
    }

    /**
     * Determines if we export content into a CMS
     *
     * @return bool 
     * @access public
     * @since  3.0.0
     */
    public static function isCMSStarted()
    {
        return isset(self::$currentCMS);
    }

    /**
     * Check if a widget requested from certain CMS
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkCurrentCMS()
    {
        return $this->getCMSName() === self::$currentCMS;
    }

	/**
	 * Return list of widgets which can be exported 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0
	 */
	public function getWidgetsList()
	{
		return $this->widgetsList;
	}

    /**
     * Return object by class name 
     * 
     * @param string $class      widget class name
     * @param array  $attributes widget attributes
     *  
     * @return XLite_View_Abstract
     * @access public
     * @since  3.0.0
     */
	public function getWidgetObject($class, array $attributes = array(), $delta = '')
	{
        $result = null;

        if (class_exists($class)) {
            $result = XLite_Model_CachingFactory::getObjectFromCallback(
                __METHOD__ . $class . $delta, $this->getViewer(), 'getWidget', array($this->prepareAttributes($attributes), $class)
            );
        }

        return $result;
	}

	/**
	 * Validate widget arguments 
	 * 
	 * @param string $name       widget identifier
	 * @param array  $attributes widget attributes
	 *  
	 * @return array
	 * @access public
	 * @since  3.0.0
	 */
	public function validateWidgetArguments($name, array $attributes, $delta = '')
	{
        $widget = $this->getWidgetObject($name, $attributes, $delta);

		return $widget ? $widget->validateAttributes($attributes) : array();
	}

	/**
     * Check if widget is visible or not
     *
     * @param string $name       widget identifier
     * @param array  $attributes widget attributes
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
	public function isWidgetVisible($name, array $attributes, $delta = '')
	{
        $widget = $this->getWidgetObject($name, $attributes, $delta);

		return $widget ? $widget->isVisible() : false;
	}

	/**
	 * Return widget object 
	 * 
	 * @param string $name       widget name
	 * @param array  $attributes parameters list defined in CMS
	 * 
	 * @return 
	 * @access public
	 * @since  3.0
	 */
	public function getBlock($name, array $attributes = array(), $delta = '')
	{
        XLite_View_Abstract::cleanupResources();

        return new XLite_Core_WidgetDataTransport($this->getWidgetObject($name, $attributes, $delta));
	}

    /**
     * Run a controller
     *
     * @return XLite_Core_WidgetDataTransport
     * @access public
     * @since  3.0.0
     */
    public function runController()
    {
        return new XLite_Core_WidgetDataTransport($this->getViewer(true));
    }


    // -----> FIXME - to revise


	/**
	 * Set user data 
	 * 
	 * @param string  $email Email
	 * @param array   $data  User data
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function setUserData($email, array $data)
	{
        $result = false;

		// Translation profile field names
        $transTable = $this->getUserTranslationTable();

        $transData = array();
        foreach ($transTable as $k => $v) {
            if (isset($data[$k])) {
                $transData[$v] = $data[$k];
            }
        }

        $profile = new XLite_Model_Profile();

    	if ($profile->find('login = \'' . addslashes($email) . '\'')) {

			// Update
			if ($transData) {
	            $profile->modifyProperties($transData);
				$result = (bool)$profile->update();
			}

		} else {

			// Create
			$transData['login'] = $email;
			$profile->modifyProperties($transData);
			$result = (bool)$profile->create();
		}

        return $result;
	}

    /**
     * Remove user profile
     * 
     * @param string $email Email
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function removeUser($email)
    {
		$result = false;

        $profile = new XLite_Model_Profile();

        if ($profile->find('login = \'' . addslashes($email) . '\'')) {
			$profile->delete();
			$result = true;
		}

		return $result;
    }

	/**
	 * Log-in user in LC 
	 * 
	 * @param string $email Email
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function logInUser($email)
	{
		$profile = XLite_Model_Auth::getInstance()->loginSilent($email);
        
		return !is_int($profile) || ACCESS_DENIED !== $profile;
	}

	/**
	 * Log-out user in LC 
	 * 
	 * @param string $email User email
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function logOutUser($email = null)
	{
		XLite_Model_Auth::getInstance()->logoff();
	}

	/**
	 * Get session TTL (in seconds) 
	 * 
	 * @return integer
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getSessionTtl()
	{
		return $this->session->getTtl();
	}

	/**
	 * Get landing link 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getLandingLink()
	{
	}

    /**
     * Get page types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypes()
    {
        return $this->pageTypes;
    }

    /**
     * Check - valid page instance settings or not
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkPageInstanceSettings($type, array $settings)
    {
        $type = $this->getPageTypeObject($type);

        return $type ? $type->validatePageTypeAttributes($settings) : array();
    }

    /**
     * Check - visible page instance or not
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPageInstanceVisible($type, array $settings)
    {
        $result = false;

        $type = $this->getPageTypeObject($type);

        if ($type) {
            $type->setAttributes($this->prepareAttributes($settings));
            $result = $type->isPageInstanceVisible();
        }

        return $result;
    }

    /**
     * Get page instance data 
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceLink($type, array $settings)
    {
        $result = array(null, null);

        $type = $this->getPageTypeObject($type);

        if ($type) {
            $application = $this->runApplication($this->prepareAttributes($settings));
            $type->init();
            $result = $type->getPageInstanceData();
        }

        return $result;
    }

    /**
     * Get page type object 
     * 
     * @param string $type Class name
     *  
     * @return XLite_Controller_Abstract
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeObject($type)
    {
        return class_exists($type) ? new $type : null;
    }

	/**
	 * Get translation table for profile data
	 * 
	 * @return array
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function getUserTranslationTable()
	{
		return array();
	}
}

