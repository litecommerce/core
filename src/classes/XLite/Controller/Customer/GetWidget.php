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
     * Current page template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0
     */
    protected $template = 'get_widget.tpl';


    /**
     * These params from AJAX request will be translated into the corresponding ones  
     * 
     * @return array
     * @access protected
     * @since  3.0.0
     */
    protected function getAJAXParamsTranslationTable()
    {
        return array(
            self::PARAM_AJAX_TARGET => 'target',
            self::PARAM_AJAX_ACTION => 'action',
        );
    }

    /**
     * Handles the request. Parses the request variables if necessary. Attempts to call the specified action function 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        $request = XLite_Core_Request::getInstance();

        foreach ($this->getAJAXParamsTranslationTable() as $ajaxParam => $requestParam) {
            if (!empty($request->$ajaxParam)) {
                $request->$requestParam = $request->$ajaxParam;
                $this->set($requestParam, $request->$ajaxParam);
            }
        }

        parent::handleRequest();
    }

    /**
     * checkRequest 
     * 
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function checkRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'XMLHttpRequest' == $_SERVER['HTTP_X_REQUESTED_WITH'];
    }
    
    /**
     * Check if current page is accessible
     * 
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function checkAccess()
    {
        return parent::checkAccess()
            && $this->checkRequest()
            && XLite_Core_Operator::isClassExists($this->getClass());
    }

    /**
     * getCMSTemplate
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getCMSTemplate()
    {
        return $this->template;
    }

    /**
     * Return Viewer object
     * 
     * @return XLite_View_Controller
     * @access public
     * @since  3.0.0
     */
    public function getViewer($isExported = false)
    {
        return parent::getViewer(true);
    }

    /**
     * Get class name
     * 
     * @return string
     * @access public
     * @since  3.0.0
     */
    public function getClass()
    {
        $param = self::PARAM_AJAX_CLASS;

        return XLite_Core_Request::getInstance()->$param;
    }
}
