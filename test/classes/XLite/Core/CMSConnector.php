<?php

/* $Id$ */

/**
 * Singleton to connect to a CMS
 *                         
 * @package    Lite Commerce
 * @subpackage Core        
 * @since      3.0                   
 */
class XLite_Core_CMSConnector extends XLite_Base implements XLite_Base_ISingleton
{
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
		'TopCategories' => array(
			'name' => 'Top categories side bar',
		),
		'Product' => array(
			'name' => 'Product side bar',
			'args' => array(
				'product_id' => array(
					'name' => 'Product Id',
					'type' => 'integer',
				),
			),
		),
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

	protected function getContent(XLite_View $viewer, array $params = array())
	{
		if (!empty($params)) {
            $attributes = $params;//array();
            /*foreach ($params as $param) {
                $attributes[$param->name] = $param->value;
            }*/
            $viewer->setAttributes($attributes);
        }

        $viewer->init();

        ob_start();
        $viewer->display();
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
	 * Validate widget arguments 
	 * 
	 * @param string $code Widget code
	 * @param array  $args Arguments hash-array
	 *  
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function validateWidgetArguments($code, array $args)
	{
		// TODO - add validation
		$result = array();

		if ('Product' == $code) {

			if (!isset($args['product_id']) || !is_numeric($args['product_id'])) {
				$result['product_id'] = array('Product Id is not numeric');
			}

		}

		return $result;
	}

	/**
	 * Check - widget is visible or not 
	 * 
	 * @param string $code Widget code
	 * @param array  $args Widget arguments
	 *  
	 * @return boolean
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function isWidgetVisible($code, array $args)
	{
		// TODO - add visibility checking
		return true;
	}

	/**
	 * Return HTML code of a widget 
	 * 
	 * @param string $name   widget name
	 * @param array  $params array of XLite_Model_WidgetParam objects
	 *  
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public function getWidgetHTML($name, array $params = array())
	{
		$name   = 'XLite_View_' . $name;
		$object = new $name();

		return $this->getContent($object, $params);

		/*if (!empty($params)) {
			$attributes = array();
			foreach ($params as $param) {
				$attributes[$param->name] = $param->value;
			}
			$object->setAttributes($attributes);
		}

		$object->init();

		ob_start();
		$object->display();
		$content = ob_get_contents();
		ob_end_clean();

		return $content;*/
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
	 * @param integer $userId Drupal user id
	 * @param array   $data   User data
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function setUserData($userId, array $data)
	{
	}

	/**
	 * Log-in user in LC 
	 * 
	 * @param integer $userId Drupal user id
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function logInUser($userId)
	{
	}

	/**
	 * Log-out user in LC 
	 * 
	 * @param integer $userId Drupal user id
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function logOutUser($userId)
	{
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
		$name = XLite_Core_Converter::getControllerClass($target);
		$object = new $name();

		$object->template = 'center.tpl';

		return $this->getContent($object, array('target' => $target, 'action' => $action) + $args);
	}
}

