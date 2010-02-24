<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Product images gallery widget
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
 * Product images gallery widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_Module_DetailedImages_View_Gallery extends XLite_View_Abstract
{
    /**
     * Light box library images directory
     * 
     * @var    string
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $lightBoxImagesDir = null;

    /**
     * Light box library path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lightBoxPath = 'modules/DetailedImages/js/jquery.lightbox-0.5.min.js';

    /**
     * Light box CSS file path 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $lightBoxCSSPath = 'modules/DetailedImages/css/jquery.lightbox-0.5.css';

    /**
     * Define template
     *
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function __construct()
    {
        $this->template = 'modules/DetailedImages/gallery.tpl';
    }

    /**
     * Initialization 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function initView()
    {
        $this->lightBoxImagesDir = XLite::getInstance()->shopURL(
            XLite_Model_Layout::getInstance()->getPath() . 'modules/DetailedImages/images'
        );
    }

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

        $list[] = $this->lightBoxPath;

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

        $list[] = $this->lightBoxCSSPath;

        return $list;
    }

}

