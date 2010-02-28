<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category widget
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Category widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Category extends XLite_View_Abstract
{
    /**
     * Widget template 
     * 
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $template = 'category_description.tpl';


    /**
     * Check widget visibility 
     * 
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getCategory()->get('description');
    }
}

