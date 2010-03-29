<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Category navigation dialog
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0
 */

/**
 * Category navigation dialog 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0
 */
class XLite_Controller_Customer_Category extends XLite_Controller_Customer_Catalog
{
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

        $this->widgetParams[self::PARAM_CATEGORY_ID]->setVisibility(true);
    }

    /**
     * getModelObject
     *
     * @return XLite_Model_Abstract
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getCategory();
    }


    /**
     * handleRequest 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function handleRequest()
    {
        if ($this->isCategoryAvailable()) {
            parent::handleRequest();
        } else {
            $this->set('returnUrl', $this->buildURL());
        }
    }
}

