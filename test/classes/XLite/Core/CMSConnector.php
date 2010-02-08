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
	 * List of widgets which can be exported
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $widgetsList = array(
		'TopCategories' => 'Top categories side bar (menu)',
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
	 * Method to access the singleton 
	 * 
	 * @return XLite_Model_CMSConnector
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

		if (!empty($params)) {
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

		return $content;
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
			$path = XLite_Model_Layout::getInstance()->getPath();

			foreach ($this->cssFiles as &$cssFile) {
	            $cssFile = XLite::getInstance()->shopURL($path . $cssFile);
    	    }
		}

		return $this->cssFiles;
	}
}

