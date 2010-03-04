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
 * @since     3.0.0 EE
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
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0 EE
     */
    protected $allowedTargets = array('main', 'category');

    /**
     *  Display modes
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        'list'  => 'List',
        'icons' => 'Icons',
    );

    /**
     * Return title
     *
     * @return string
     * @access protected
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
     */
    protected function getDir()
    {
        return 'subcategories/' . $this->attributes['displayMode'];
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
        return $this->attributes[self::IS_EXPORTED] ? $this->attributes['displayMode'] : $this->config->General->subcategories_look;
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0 EE
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
     * @since  3.0.0 EE
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
            'displayMode' => new XLite_Model_WidgetParam_List('Display mode', 'icons', $this->displayModes),
        );
    }

}

