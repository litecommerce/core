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

namespace XLite\Controller\Customer;

/**
 * Products search
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Search extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Common method to determine current location 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */ 
    protected function getLocation()
    {
        return 'Search results';
    }

    /** 
     * doActionSearch TODO refactor with XLite\Controller\Admin\ProductList::doActionSearch() 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionSearch()
    {   
        $sessionCell    = \XLite\View\ItemsList\Product\Customer\Search::getSessionCellName();
        $searchParams   = \XLite\View\ItemsList\Product\Customer\Search::getSearchParams();

        $productsSearch = array();

        $cBoxFields     = array(
            \XLite\View\ItemsList\Product\Customer\Search::PARAM_SEARCH_IN_SUBCATS
        );  
    
        foreach ($searchParams as $modelParam => $requestParam) {
            if (isset(\XLite\Core\Request::getInstance()->$requestParam)) {
                $productsSearch[$requestParam] = \XLite\Core\Request::getInstance()->$requestParam;
            }   
        }   
 
        foreach ($cBoxFields as $requestParam) {
            $productsSearch[$requestParam] = isset(\XLite\Core\Request::getInstance()->$requestParam)
                ? 1 
                : 0;
        }   
    
        $this->session->set($sessionCell, $productsSearch);
        $this->set('returnUrl', $this->buildUrl('search', '', array('mode' => 'search')));

    }

    /** 
     * Get search conditions TODO refactor with XLite\Controller\Admin\ProductList::getConditions()
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParams = $this->session->get(\XLite\View\ItemsList\Product\Customer\Search::getSessionCellName());

        if (!is_array($searchParams)) {
            $searchParams = array();
        }   

        return $searchParams;
    }

    /** 
     * Get search condition parameter by name TODO refactor with XLite\Controller\Admin\ProductList::getCondition()
     * 
     * @param string $paramName Name of parameter 
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {
            $return = $searchParams[$paramName];
        }   

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /** 
     * Return 'checked' attribute for parameter.
     * 
     * @param string $paramName Name of parameter
     * @param mixed  $value     Value to check with
     *  
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getChecked($paramName, $value = 'Y')
    {   
        return $value === $this->getCondition($paramName) ? 'checked' : ''; 
    }   

}

