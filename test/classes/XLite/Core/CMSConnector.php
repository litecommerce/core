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
 * @since     3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected static $currentCMS = null;

	/**
	 * Layout path 
	 * 
	 * @var    string
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0 EE
	 */
	protected $layoutPath = null;

	/**
	 * List of widgets which can be exported
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $widgetsList = array(
		'XLite_View_TopCategories'    => 'Categories list',
        'XLite_View_Minicart'         => 'Minicart (horizontal)',
        'XLite_View_MenuMinicart'     => 'Minicart (vertical)',
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
        'XLite_Controller_Customer_Category' => 'Category page',
        'XLite_Controller_Customer_Product'  => 'Product page',
        'XLite_Controller_Customer_Cart'     => 'Shopping cart',
        'XLite_Controller_Customer_Checkout' => 'Checkout',

    );

	/**
	 * List of CSS files to export 
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $cssFiles = null;

    /**
     * List of Javascript files to export 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $jsFiles = null;

    /**
     * Initialized instance of the XLite singleton 
     * 
     * @var    XLite
     * @access protected
     * @since  3.0.0 EE
     */
    protected $initializedApplication = null;


	/**
	 * Constructor
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	protected function __construct()
	{
		$this->layoutPath = XLite_Model_Layout::getInstance()->getPath();
	}

    /**
     * Return initialized instance of the XLite singleton 
     * 
     * @param array $request request data
     *  
     * @return XLite
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getApplicationInstance(array $request)
    {
        XLite_Core_Request::getInstance()->mapRequest($request);

        if (!isset($this->initializedApplication)) {
            $this->initializedApplication = XLite::getInstance();
            $this->initializedApplication->init();
        }

        return $this->initializedApplication;
    }

	/**
	 * Prepare attributes before set them to a widget
	 * 
	 * @param array $attributes attributes to prepare
	 *  
	 * @return array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function prepareAttributes(array $attributes)
	{
        return $attributes;
	}

	/**
     * Get widget content
     *
     * @param XLite_View_Abstract $object     controller/viewer object to use
     * @param array               $attributes widget attributes
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getContent(XLite_View_Abstract $object, array $attributes = array())
    {
        $this->prepareCall();

        ob_start();
        $this->getApplicationInstance($attributes)->runViewer($object);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
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
     * @since  3.0.0 EE
     */
    abstract public function getCMSName();

    /**
     * Prepare call
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    abstract protected function prepareCall();


    /**
     * Handler should called this function first to prevent any possible conflicts
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init()
    {
        self::$currentCMS = $this->getCMSName();
    }

    /**
     * Check if a widget requested from certain CMS
     *
     * @return bool
     * @access public
     * @since  3.0.0 EE
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
	 * @param string $name widget class name
	 *  
	 * @return XLite_View_Abstract
	 * @access public
	 * @since  3.0.0 EE
	 */
	public function getWidgetObject($name)
	{
		return class_exists($name) ? new $name() : null;
	}

	/**
	 * Validate widget arguments 
	 * 
	 * @param string $name       widget identifier
	 * @param array  $attributes widget attributes
	 *  
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function validateWidgetArguments($name, array $attributes)
	{
		return ($widget = $this->getWidgetObject($name)) ? $widget->validateAttributes($attributes) : array();
	}

	/**
     * Check if widget is visible or not
     *
     * @param string $name       widget identifier
     * @param array  $attributes widget attributes
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
	public function isWidgetVisible($name, array $attributes)
	{
		return ($widget = $this->getWidgetObject($name)) ? $widget->isVisible() : false;
	}

	/**
	 * Return HTML code of a widget 
	 * 
	 * @param string $name       widget name
	 * @param array  $attributes array of params defined in CMS
	 *  
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public function getWidgetHTML($name, array $attributes = array())
	{
        $result = null;
        $widget = $this->getWidgetObject($name);

        if ($widget) {
            $widget->setAttributes($attributes);
            $result = $this->getContent($widget, $attributes);
        }

		return $result;
	}

	/**
	 * Prepare and return list of CSS files to export 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0
	 */
	public function getCSSList()
	{
		if (!isset($this->cssFiles)) {

			$this->cssFiles = array('style.css');

			foreach ($this->cssFiles as &$cssFile) {
	            $cssFile = XLite::getInstance()->shopURL($this->layoutPath . $cssFile);
    	    }
		}

		return $this->cssFiles;
	}

    /**
     * Prepare and return list of Javascript files to export 
     * 
     * @return array
     * @access public
     * @since  3.0
     */
    public function getJSList()
    {
        if (!isset($this->jsFiles)) {

			$this->jsFiles = array();

            foreach ($this->jsFiles as &$jsFile) {
                $cssFile = XLite::getInstance()->shopURL($this->layoutPath . $jsFile);
            }
        }

        return $this->jsFiles;
    }

	/**
	 * Set user data 
	 * 
	 * @param string  $email Email
	 * @param array   $data  User data
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
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
     * @since  3.0.0 EE
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
	 * @since  3.0.0 EE
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
	 * @since  3.0.0 EE
	 */
	public function logOutUser($email = null)
	{
		XLite_Model_Auth::getInstance()->logoff();
	}

	/**
	 * Run a controller
	 *
	 * @param string $target controller target
	 * @param string $action controller action
	 * @param array  $args   controller arguments
	 *
	 * @return string
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function runFrontController($target, $action, array $args = array())
	{
        $args = array(
            'target'   => $target,
            'action'   => $action,
            'template' => 'center_top.tpl',
        ) + $args;

        $application = $this->getApplicationInstance($args);
        $content = $this->getContent($application->runController());

        $result = array(
            $application->getController()->get('locationPath'),
            $content,
            $application->getController()->getPageTypeName(),
        );

        return $result;
	}

	/**
	 * Get session TTL (in seconds) 
	 * 
	 * @return integer
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
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
	 * @since  3.0.0 EE
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
            $this->prepareCall();
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
            $this->prepareCall();
            $type->setAttributes($this->prepareAttributes($settings));
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
	 * @since  3.0.0 EE
	 */
	protected function getUserTranslationTable()
	{
		return array();
	}
}

