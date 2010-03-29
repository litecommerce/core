<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category products list widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Category products list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_ShowcaseOrganizer_View_CategoryProducts extends XLite_View_CategoryProducts implements XLite_Base_IDecorator
{
    /**
     * Get cell width in percents 
     * 
     * @param integer $columns Columns count
     *  
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
     */
    function getPercents($columns)
    {
        return ceil(100 / $columns);
    }
}

