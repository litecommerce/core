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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View\Pager\Admin\Model;

/**
 * Table-based pager
 * 
 */
class Table extends \XLite\View\Pager\Admin\Model\AModel
{
    /**
     * isVisible: hide pager if table contains no data
     * 
     * @return boolean
     */
    public function isVisible()
    {
        return parent::isVisible() && 0 < $this->getItemsTotal();
    }

    /**
     * Return CSS classes for parent block of pager (list-pager by default)
     *
     * @return string
     */
    public function getCSSClasses()
    {
        return 'table-pager';
    }

    /**
     * Get items per page (default)
     *
     * @return integer
     */
    protected function getItemsPerPageDefault()
    {
        return 25;
    }

    /**
     * getDir
     *
     * @return string
     */
    protected function getDir()
    {
        return 'pager/model/table';
    }

    // {{{ Content helpers

    /**
     * Check - current page is first or not
     * 
     * @return boolean
     */
    protected function isFirstPage()
    {
        return $this->getPageId() == $this->getFirstPageId();
    }

    /**
     * Check - current page is last or not
     *
     * @return boolean
     */
    protected function isLastPage()
    {
        return $this->getPageId() == $this->getLastPageId();
    }

    /**
     * Get previous arrow class 
     * 
     * @return string
     */
    protected function getPrevClass()
    {
        return 'prev ' . ($this->isFirstPage() ? 'disabled' : 'enabled');
    }

    /**
     * Get next arrow class
     *
     * @return string
     */
    protected function getNextClass()
    {
        return 'next ' . ($this->isLastPage() ? 'disabled' : 'enabled');
    }

    /**
     * Get items per page ranges list
     * 
     * @return array
     */
    protected function getItemsPerPageRanges()
    {
        return array(10, 25, 50, 75, 100);
    }

    /**
     * Chec - items per page box visible or not
     *
     * @return boolean
     */
    protected function isItemsPerPageVisible()
    {
        $min = min($this->getItemsPerPageRanges());

        return $min < $this->getItemsTotal();
    }

    /**
     * Check - range is selected or not
     * 
     * @param integer $range Range
     *  
     * @return boolean
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
     */
    protected function preprocessPageId($id)
    {
        return $id + 1;
    }

    // }}}
}
