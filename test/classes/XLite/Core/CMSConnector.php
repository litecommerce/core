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
     * Return currently used CMS name
     *
     * @return string
     * @access public
     * @since  3.0.0
     */
    abstract public function getCMSName();

    /**
     * Get landing link
     *
     * @return string
     * @access public
     * @since  3.0.0 EE
     */
    abstract public function getLandingLink();


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
     * @since  3.0.0
     */
    public function getWidgetsList()
    {
        return $this->widgetsList;
    }

    /**
     * Get page types
     *
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getPageTypes()
    {
        return $this->pageTypes;
    }

    /**
     * getApplication 
     * 
     * @return XLite
     * @access public
     * @since  3.0.0
     */
    public function getApplication()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(
            __METHOD__, XLite::getInstance(), 'run'
        );
    }

     /**
     * Return viewer for current page
     *
     * @return XLite_View_Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer()
    {
        return XLite_Model_CachingFactory::getObjectFromCallback(
            __METHOD__, $this->getApplication(), 'getViewer'
        );
    }
    
    /**
     * Return widget
     * 
     * @param string $class  widget class name
     * @param array  $params widget params
     * @param int    $delta  drupal-specific param - so called "delta"
     *  
     * @return XLite_View_Abstract
     * @access public
     * @since  3.0.0
     */
    public function getWidget($class, array $params = array(), $delta = 0)
    {
        return new XLite_Core_WidgetDataTransport(
            class_exists($class) ? $this->getViewer()->getWidget($params, $class) : null
        );
    }

    /**
     * Return controller for current page
     *
     * @param string $class  widget class name
     * @param array  $params widget params
     *
     * @return XLite_Controller_Customer_Abstract
     * @access public
     * @since  3.0.0
     */
    public function getPageInstance($class, array $params = array())
    {
        return new XLite_Core_WidgetDataTransport(
            class_exists($class) ? new $class($params) : null
        );
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
}

