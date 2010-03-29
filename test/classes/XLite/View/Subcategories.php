<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Subcategories list widget
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
 * Subcategories list widget
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
class XLite_View_Subcategories extends XLite_View_Dialog
{
    /**
     * Widget parameter names
     */

    const PARAM_DISPLAY_MODE = 'displayMode';

    /**
     * Allowed display modes
     */

    const DISPLAY_MODE_LIST  = 'list';
    const DISPLAY_MODE_ICONS = 'icons';


    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('main', 'category');

    /**
     *  Display modes
     * 
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_LIST  => 'List',
        self::DISPLAY_MODE_ICONS => 'Icons',
    );

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Catalog';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'subcategories/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Get widget display mode 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDisplayMode()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED) 
            ? $this->getParam(self::PARAM_DISPLAY_MODE) 
            : $this->config->General->subcategories_look;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getCategory()->getSubcategories();
    }

    /**
     * Return list of required CSS files
     * 
     * @return array
     * @access public
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        return array('subcategories/subcategories.css');
    }

    /**
     * Widget parameters
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
            self::PARAM_DISPLAY_MODE => new XLite_Model_WidgetParam_List(
                'Display mode', self::DISPLAY_MODE_ICONS, true, $this->displayModes
            ),
        );
    }
}

