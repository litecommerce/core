<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * ____file_title____
 *  
 * @category   Lite Commerce
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version    SVN: $Id$
 * @link       http://www.qtmsoft.com/
 * @since      3.0.0 EE
 */

/**
 * XLite_Controller_Customer_Catalog 
 * 
 * @package    Lite Commerce
 * @subpackage ____sub_package____
 * @since      3.0.0 EE
 */
abstract class XLite_Controller_Customer_Catalog extends XLite_Controller_Customer_Abstract
{
    /**
     * Return link to category page 
     * 
     * @param XLite_Model_Category $category category model object to use
     *  
     * @return string
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCategoryURL(XLite_Model_Category $category)
    {
        return $this->buildURL('category', '', array('category_id' => $category->get('category_id')));
    }

    /**
     * Return category name and link
     *
     * @param XLite_Model_Category $category category model object to use
     *
     * @return array
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getCategoryLocation(XLite_Model_Category $category)
    {
        return new XLite_Model_Location($category->get('name'), $this->getCategoryURL($category));
    }

    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        foreach ($this->getCategory()->getPath() as $category) {
            if (0 < $category->get('category_id')) {
                $this->locationPath->addNode($this->getCategoryLocation($category));
            }
        }
    }
}

