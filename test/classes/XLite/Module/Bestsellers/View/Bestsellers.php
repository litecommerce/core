<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Bestsellers dialog
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
 * Bestsellers dialog 
 * 
 * @package    View
 * @subpackage Widget
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_Module_Bestsellers_View_Bestsellers extends XLite_View_SideBarBox
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
     * Display modes
     *
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $displayModes = array(
        'vertical'   => 'Vertical',
        'horizontal' => 'Horizontal',
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
        return 'Bestsellers';
    }

    /**
     * Get widget templates directory
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDir()
    {
        return 'modules/Bestsellers/bestsellers/' . $this->getDisplayMode();
    }

    /**
     * Return current display mode 
     * 
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getDisplayMode()
    {
        return isset($this->attributes['displayMode']) ?  $this->attributes['displayMode'] : $this->config->Bestsellers->bestsellers_menu;
    }

    /**
     * Return category Id to use
     * 
     * @return int
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getRootId()
    {
        return $this->attributes['useNode'] ? XLite_Core_Request::getInstance()->category_id : $this->attributes['rootId'];
    }

    /**
     * Return subcategories lis
     *
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getBestsellers()
    {
        return XLite_Model_CachingFactory::getObject('XLite_Module_Bestsellers_Model_Bestsellers')->getBestsellers($this->getRootId());
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
            'displayMode' => new XLite_Model_WidgetParam_List('Display mode', 'vertical', $this->displayModes),
            'useNode'     => new XLite_Model_WidgetParam_Checkbox('Use current category id', 0),
            'rootId'      => new XLite_Model_WidgetParam_ObjectId_Category('Root category Id', 0, true),
        );
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
        return parent::isVisible() && $this->getBestsellers();
    }

    /**
     * Return whether thumbnails are to be shown in the product list
     */
    public function thumbnailsEnabled()
    {
        return $this->getComplex('config.Bestsellers.bestsellers_thumbnails');
    }

    /**
     * Return CSS classes depending on the widget settings
     */ 
    public function widgetCSSClasses()
    {
        $class = 'promoted-products';

        $css = array($class);

        if ($this->thumbnailsEnabled())
            $css[] = "$class-with-thumbnails";

        $modeClasses = array(
            'vertical' => 'shortened',
            'horizontal' => 'full',
        );
        $css[] = "$class-" . (isset($modeClasses[$this->displayMode]) ? $modeClasses[$this->displayMode] : array_pop($modeClasses));

        return join(' ', $css);
    }



}

