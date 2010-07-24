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

namespace XLite\View\Pager;

/**
 * Abstract pager class
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
abstract class APager extends \XLite\View\AView
{
    /**
     * It's the defeult valueof items to display per page 
     */
    const ITEMS_PER_PAGE_DEFAULT = 10;


    /**
     * Return list of items to display on the current page 
     * 
     * @param int $start index of the first item on the page
     * @param int $count number of items per page
     *  
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    abstract protected function getDataFrame($start, $count);


    /**
     * Return current page ID 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getPageId()
    {
        return intval(\XLite\Core\Request::getInstance()->pageId);
    }

    /**
     * Return index of the first item on the current page
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getStartItem()
    {
        return $this->getPageId() * $this->getItemsPerPage();
    }

    /**
     * Return number of items per page 
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getItemsPerPage()
    {
        return self::ITEMS_PER_PAGE_DEFAULT;
    }


    /**
     * Return page data 
     * 
     * @return array|\Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getData()
    {
        return $this->getDataFrame($this->getStartItem(), $this->getItemsPerPage());
    }
}
