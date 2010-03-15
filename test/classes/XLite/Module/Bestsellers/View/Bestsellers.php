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
 * Bestsellers widget 
 * 
 * @package    XLite
 * @subpackage View
 * @see        ____class_see____
 * @since      3.0.0
 */
class XLite_Module_Bestsellers_View_Bestsellers extends XLite_View_ProductsList
{
    /**
     * Widget parameter names
     */

    const PARAM_ROOT_ID      = 'rootId';
    const PARAM_USE_NODE     = 'useNode';

    /**
     * Targets this widget is allowed for
     *
     * @var    array
     * @access protected
     * @since  3.0.0
     */
    protected $allowedTargets = array('main', 'category');

    /**
     * Get title
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return 'Bestsellers';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_USE_NODE => new XLite_Model_WidgetParam_Checkbox(
                'Use current category id', false, true
            ),
            self::PARAM_ROOT_ID => new XLite_Model_WidgetParam_ObjectId_Category(
                'Root category Id', 0, true, true
            ),
        );

        $this->widgetParams[self::PARAM_WIDGET_TYPE]->setValue($this->config->Bestsellers->bestsellers_menu ? self::WIDGET_TYPE_SIDEBAR : self::WIDGET_TYPE_CENTER);

        $this->widgetParams[self::PARAM_DISPLAY_MODE]->setValue(self::DISPLAY_MODE_LIST);
        $this->widgetParams[self::PARAM_GRID_COLUMNS]->setValue(3);
        $this->widgetParams[self::PARAM_SHOW_THUMBNAIL]->setValue('Y' == $this->config->Bestsellers->bestsellers_thumbnails);
        $this->widgetParams[self::PARAM_SHOW_DESCR]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_PRICE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_ADD2CART]->setValue(true);
        $this->widgetParams[self::PARAM_SIDEBAR_MAX_ITEMS]->setValue($this->config->Bestsellers->number_of_bestsellers);

        $this->widgetParams[self::PARAM_SHOW_DISPLAY_MODE_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SHOW_ALL_ITEMS_PER_PAGE]->setValue(true);
        $this->widgetParams[self::PARAM_SHOW_SORT_BY_SELECTOR]->setValue(false);
        $this->widgetParams[self::PARAM_SORT_BY]->setValue('Name');
        $this->widgetParams[self::PARAM_SORT_ORDER]->setValue('asc');

        foreach ($this->getHiddenParamsList() as $param) {
            $this->widgetParams[$param]->setVisibility(false);
        }

    }

    /**
     * Get the list of parameters that are hidden on the settings page 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHiddenParamsList()
    {
        return array(
            self::PARAM_SHOW_DISPLAY_MODE_SELECTOR,
            self::PARAM_SHOW_SORT_BY_SELECTOR,
            self::PARAM_SORT_BY,
            self::PARAM_SORT_ORDER,
            self::PARAM_SHOW_ALL_ITEMS_PER_PAGE
        );
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible() && $this->getBestsellers();
    }

    /**
     * Return products list
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getData()
    {
        return $this->getBestsellers();
    }

    /**
     * Return category Id to use
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getRootId()
    {
        return $this->getParam(self::PARAM_USE_NODE) 
            ? XLite_Core_Request::getInstance()->category_id 
            : $this->getParam(self::PARAM_ROOT_ID);
    }

    /**
     * Get the number of bestsellers to display
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNumberOfBestsellers()
    {
        return (int)(self::WIDGET_TYPE_SIDEBAR == $this->getParam(self::PARAM_WIDGET_TYPE) ? $this->getParam(self::PARAM_SIDEBAR_MAX_ITEMS) : $this->config->Bestsellers->number_of_bestsellers);
    }

    /**
     * Return subcategories list
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBestsellers()
    {
        return XLite_Model_CachingFactory::getObject('XLite_Module_Bestsellers_Model_Bestsellers')->getBestsellers($this->getNumberOfBestsellers(), $this->getRootId());
    }

}
