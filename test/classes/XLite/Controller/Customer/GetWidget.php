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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * Get widget (AJAX)
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Controller_Customer_GetWidget extends XLite_Controller_Customer_Abstract
{	
    /**
     * Controller parameters 
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target');

    /**
     * Current page template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'get_widget.tpl';

	/**
	 * Class name
	 * 
	 * @var    string
	 * @access protected
	 * @see    ____var_see____
	 * @since  3.0.0
	 */
	protected $class = null;

    /**
     * Widget parameters
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $widgetParams = array();

	/**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        $this->locationPath->addNode(new XLite_Model_Location('AJAX getter'));
    }

    /**
     * Handles the request. Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function handleRequest()
	{
		$request = XLite_Core_Request::getInstance();

		if (isset($request->widget_target) && $request->widget_target) {
			$request->target = $request->widget_target;
		}

        if (isset($request->widget_action) && $request->widget_action) {
            $request->action = $request->widget_action;
        }

        if (
			isset($request->class)
			&& $request->class
			&& is_string($request->class)
			&& preg_match('/^[a-z0-9_]+$/Ssi', $request->class)
		) {
            $this->class = $request->class;
        }

        $data = $request->getData();
        $keys = array(
            'widget_target',
            'widget_action',
            'class',
            XLite_View_ProductsList::SORT_CRITERION_ARG,
            XLite_View_ProductsList::SORT_ORDER_ARG,
            XLite_View_ProductsList::DISPLAY_MODE_ARG,
            XLite_View_ProductsList::ITEMS_PER_PAGE_ARG,
            XLite_View_Pager::PAGE_ID_ARG,
        );
        
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                unset($data[$key]);
            }
        }

        $this->widgetParams = $data;

		parent::handleRequest();
	}

    /**
     * Get controlelr parameters
     * 
     * @param string $exeptions Parameter keys string
     *  
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getAllParams($exeptions = null)
    {
        $exeptions = isset($exeptions) ? explode(",", $exeptions) : array();

        $result = $this->widgetParams;

        foreach ($exeptions as $key) {
            if (isset($result[$key])) {
                unset($result[$key]);
            }
        }

        return $result;
    }
    /**
     * Check if current page is accessible
     * 
     * @return bool
     * @access protected
     * @since  3.0.0 EE
     */
    protected function checkAccess()
    {
        return parent::checkAccess()
			&& isset($_SERVER['HTTP_X_REQUESTED_WITH'])
			&& $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'
			&& $this->class
			&& class_exists($this->class);
    }

	/**
	 * Get class name
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getClass()
	{
		return $this->class;
	}

    /**
     * Return Viewer object
     * 
     * @return XLite_View_Controller
     * @access public
     * @since  3.0.0 EE
     */
    public function getViewer()
    {
		$widget = parent::getViewer();

		$widget->setAttributes(array(XLite_View_Abstract::IS_EXPORTED => true));

        return $widget;
    }
}
