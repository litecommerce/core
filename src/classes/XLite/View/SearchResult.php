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

namespace XLite\View;

/**
 * Search result 
 * TODO REMOVE THIS CLASS!!!!! \XLite\View\ItemsList\Product\Customer\Search  is used instead
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class SearchResult extends \XLite\View\ItemsList\Product\AProduct
{
    /**
     * Widget parameter names 
     */
    const PARAM_SUBMODE = 'submode';


    /**
     * Return current display mode
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
        return null;
    }

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
     * Return dir which contains the page body template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPageBodyDir()
    {
        return null;
    }

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'search_result/body.tpl';
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

        $this->widgetParams[self::PARAM_SUBMODE] = new \XLite\Model\WidgetParam\String('Submode', 'found');
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_SUBMODE;
    }

    /**
     * Return products list
     * 
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size
     *  
     * @return array|int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        if (is_null($this->data)) {

            $this->data = array();

            $p = new \XLite\Model\Product();

            $this->data = $p->advancedSearch(\XLite\Core\Request::getInstance()->substring, '', 0, true, false, true);

            if (!isset(\XLite\Core\Request::getInstance()->pageID)) {
                $searchStat = new \XLite\Model\SearchStat();
                $searchStat->add(\XLite\Core\Request::getInstance()->substring, count($this->data));
            }
        }

        return $this->data;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPagerClass()
    {
        return null;
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


    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'search';
    
        return $result;
    }
}

