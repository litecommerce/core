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
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.15
 */

namespace XLite\View\Pager\Admin\Model;

/**
 * Table-based pager
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
class Table extends \XLite\View\Pager\Admin\Model\AModel
{
    /**
     * Get items per page (default)
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getItemsPerPageDefault()
    {
        return 25;
    }

    /**
     * getDir
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'pager/model/table';
    }

    /**
     * Return CSS classes for parent block of pager (list-pager by default)
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSClasses()
    {
        return 'table-pager';
    }

    // {{{ Content helpers

    /**
     * Check - current page is first or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isFirstPage()
    {
        return $this->getPageId() == $this->getFirstPageId();
    }

    /**
     * Check - current page is last or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isLastPage()
    {
        return $this->getPageId() == $this->getLastPageId();
    }

    /**
     * Get previous arrow class 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getPrevClass()
    {
        return 'prev ' . ($this->isFirstPage() ? 'disabled' : 'enabled');
    }

    /**
     * Get next arrow class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getNextClass()
    {
        return 'next ' . ($this->isLastPage() ? 'disabled' : 'enabled');
    }

    /**
     * Get items per page ranges list
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getItemsPerPageRanges()
    {
        return array(10, 25, 50, 75, 100);
    }

    /**
     * Check - range is selected or not
     * 
     * @param integer $range Range
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isRangeSelected($range)
    {
        return $range == $this->getItemsPerPage();
    }

    /**
     * Preprocess page id 
     * 
     * @param integer $id Page id
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function preprocessPageId($id)
    {
        return $id + 1;
    }

    // }}}
}
