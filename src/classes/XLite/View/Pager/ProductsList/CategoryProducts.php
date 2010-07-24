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

namespace XLite\View\Pager\ProductsList;

/**
 * Pager for the category products page
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class CategoryProduct extends AProductsList
{
    /**
     * Return current category model object
     *
     * TODO - check if it's needed to use this widget outside the Catalog controller.
     * If yes, add the "category" widget  parameter
     * 
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategory()
    {
        return $this->__call(__FUNCTION__);
    }

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
    protected function getDataFrame($start, $count)
    {
    }
}
