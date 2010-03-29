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

/**
 * Search result
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_SearchResult extends XLite_View_ProductsList
{
    /**
     * Widget parameter names 
     */
    const PARAM_SUBMODE = 'submode';


    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('search');

    /**
     * Return title 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Search result';
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

        $this->widgetParams[self::PARAM_SUBMODE] = new XLite_Model_WidgetParam_String('Submode', 'found');

        $this->requestParams[] = self::PARAM_SUBMODE;

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('search_result/body.tpl');
    }

    /**
     * Get products list
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getData()
    {
        if (is_null($this->data)) {
            $this->data = array();

            $p = new XLite_Model_Product();

            $this->data = $p->advancedSearch(XLite_Core_Request::getInstance()->substring, '', 0, true, false, true);
            if (!isset(XLite_Core_Request::getInstance()->pageID)) {
                $searchStat = new XLite_Model_SearchStat();
                $searchStat->add(XLite_Core_Request::getInstance()->substring, count($this->data));
            }
        }

        return $this->data;
    }

    /**
     * Get data count 
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCount()
    {
        return count($this->getData());
    }

    /**
     * Get list title class name (CSS)
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getListTitleClass()
    {
        $classes[] = 'search-result-title';

        if ($this->getCount()) {
            $classes[] = 'search-result-full';

        } else {
            $classes[] = 'search-result-empty';
        }

        return implode(' ', $classes);
    }
}

