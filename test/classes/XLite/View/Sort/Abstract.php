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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

// FIXME - class should use the same approaches as the ProductsList one

/**
 * Abstract sort widget 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_View_Sort_Abstract extends XLite_View_Abstract
{
    /**
     * Widget parameter names
     */

    const PARAM_PARAMS          = 'params';
    const PARAM_SORT_CRITERIONS = 'sortCriterions';
    const PARAM_CELL            = 'cell';


    /**
     * Return widget default template
     *                               
     * @return string                
     * @access protected             
     * @since  3.0.0                 
     */                              
    protected function getDefaultTemplate()
    {                                      
        return 'common/sort.tpl';     
    } 

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PARAMS          => new XLite_Model_WidgetParam_Array('URL params', array()),
            self::PARAM_SORT_CRITERIONS => new XLite_Model_WidgetParam_Array('Sort criterions', array()),
            self::PARAM_CELL            => new XLite_Model_WidgetParam_Array('List conditions cell', array()),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access public
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getParam(self::PARAM_SORT_CRITERIONS);
    }

    /**
     * Get form parameters
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFormParams()
    {
        $params = $this->getParam(self::PARAM_PARAMS);

        $params['action'] = 'search';

        return $params;
    }

    /**
     * Check - specified sort criterion is selected or not
     * 
     * @param string $key Sort criterion code
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSortCriterionSelected($key)
    {
        $cell = $this->getParam(self::PARAM_CELL);

        return $key == $cell['sortCriterion'];
    }

    /**
     * Check - sort order is ascending or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isSortOrderAsc()
    {
        $cell = $this->getParam(self::PARAM_CELL);

        return 'asc' == $cell['sortOrder'];
    }

    /**
     * Build sort order link URL 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortOrderUrl()
    {
        $params = $this->getParam(self::PARAM_PARAMS);

        $target = XLite::TARGET_DEFAULT;
        $action = '';

        if (isset($params['target'])) {
            $target = $params['target'];
            unset($params['target']);
        }

        if (isset($params['action'])) {
            $action = $params['action'];
            unset($params['action']);
        }

        $action = 'search';

        $params['sortOrder'] = $this->isSortOrderAsc() ? 'desc' : 'asc';

        return $this->buildUrl($target, $action, $params);
    }

    /**
     * Get class name for sort order link
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSortOrderLinkClassName()
    {
        return $this->isSortOrderAsc() ? 'asc' : 'desc';
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'common/sort.js';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'common/sort.css';

        return $list;
    }

}
