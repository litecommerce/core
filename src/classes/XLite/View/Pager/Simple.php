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

/**
 * Simple pager
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_Pager_Simple extends XLite_View_AView
{

    /**
     * Widget parameters 
     */
    const PARAM_PAGES = 'pages';
    const PARAM_PAGE  = 'page';
    const PARAM_URL   = 'url';


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/simple_pager.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_PAGES => new XLite_Model_WidgetParam_Int('Pages count', 0),
            self::PARAM_PAGE  => new XLite_Model_WidgetParam_Int('Current page', 1),
            self::PARAM_URL   => new XLite_Model_WidgetParam_String('Link URL', null),
        );
    }

    /**
     * Check widget visibility
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && 1 < $this->getParam(self::PARAM_PAGES)
            && $this->getParam(self::PARAM_URL);
    }

    /**
     * Check - link to previous page exists or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPrevPage()
    {
        return 1 < $this->getParam(self::PARAM_PAGE);
    }

    /**
     * Get URL to previous page
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPrevURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . ($this->getParam(self::PARAM_PAGE) - 1);
    }

    /**
     * Check - link to next page is exists or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isNextPage()
    {
        return $this->getParam(self::PARAM_PAGES) > $this->getParam(self::PARAM_PAGE);
    }

    /**
     * Get URL to next page
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getNextURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . ($this->getParam(self::PARAM_PAGE) + 1);
    }

    /**
     * Get URL to last page
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLastURL()
    {
        return $this->getParam(self::PARAM_URL) . '&page=' . $this->getParam(self::PARAM_PAGES);
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

        $list[] = 'common/simple_pager.css';

        return $list;
    }
}

