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

namespace XLite\View\Pager\Customer;

/**
 * ACustomer 
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class ACustomer extends \XLite\View\Pager\APager
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEMS_PER_PAGE               = 'itemsPerPage';
    const PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR = 'showItemsPerPageSelector';


    /**
     * Number of items per page (cached value)
     *
     * @var    int
     * @access protected
     * @since  3.0.0
     */
    protected $itemsPerPage;


    /**
     * Return minimal possible items number per page 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageMin()
    {
        return 1;
    }

    /**
     * Return maximal possible items number per page
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageMax()
    {
        return 100;
    }

    /**
     * getItemsPerPageDefault
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPageDefault()
    {
        return 10;
    }

    /**
     * getItemsPerPage
     *
     * @return int
     * @access protected
     * @since  3.0.0
     */
    protected function getItemsPerPage()
    {
        if (!isset($this->itemsPerPage)) {
            $current = $this->getParam(self::PARAM_ITEMS_PER_PAGE);
            $this->itemsPerPage = max(
                min($this->getItemsPerPageMax(), $current),
                max($this->getItemsPerPageMin(), $current)
            );
        }

        return $this->itemsPerPage;
    }

    /**
     * Return number of pages to display
     *
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPagesPerFrame()
    {
        return 5;
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ITEMS_PER_PAGE => new \XLite\Model\WidgetParam\Int(
                'Items per page', $this->getItemsPerPageDefault(), true
            ),
            self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Items per page" selector', true, true
            ),
        );
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

        $this->requestParams[] = self::PARAM_ITEMS_PER_PAGE;
    }

    /**
     * isItemsPerPageSelectorVisible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    protected function isItemsPerPageSelectorVisible()
    {
        return parent::isItemsPerPageSelectorVisible()
            && $this->getParam(self::PARAM_SHOW_ITEMS_PER_PAGE_SELECTOR);
    }
}
