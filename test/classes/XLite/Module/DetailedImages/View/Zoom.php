<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product image zoom widget
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
 * Product image zoom widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_DetailedImages_View_Zoom extends XLite_View_Abstract
{
    /**
     * JQZoom library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $jqZoomPath = 'modules/DetailedImages/js/jquery.jqzoom1.0.1.js';

    /**
     * JQZAoom CSS file path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $jqZoomCSSPath = 'modules/DetailedImages/css/jqzoom.css';

    /**
     * Widget template 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $template = 'modules/DetailedImages/zoom.tpl';

    /**
     * Check visibility
     * 
     * @return bolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->product->get('detailedImages');
    }

    /**
     * Register JS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->jqZoomPath;

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->jqZoomCSSPath;

        return $list;
    }

}

