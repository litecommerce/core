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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Module_AdvancedSearch_Controller_Admin_AdvancedSearch extends XLite_Controller_Admin_Abstract
{
    function getAllPrices()  
    {
        return unserialize($this->config->getComplex('AdvancedSearch.prices'));
    }

    function getAllWeights()  
    {
        return unserialize($this->config->getComplex('AdvancedSearch.weights'));
    }
    
    function action_update()  
    {
        $config = new XLite_Model_Config();
        $config->set('category','AdvancedSearch');
        if (isset($this->prices)) {
            $config->set('name','prices');
            $config->set('value',serialize($this->prices));
        }
 	
        if (isset($this->weights)) {
            $config->set('name','weights');
            $config->set('value',serialize($this->weights));
        }

        $config->update();
    }
    
    function action_delete()  
    {
        if (isset($this->deleted_prices)) {
            $prices = unserialize($this->config->getComplex('AdvancedSearch.prices'));
            foreach($this->deleted_prices as $key => $value) {
                unset($prices[$value]);
            }
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','prices');
            $config->set('value',serialize($prices));
            $config->update();
        }
        if (isset($this->deleted_weights)) {
            $weights = unserialize($this->config->getComplex('AdvancedSearch.weights'));
            foreach($this->deleted_weights as $key => $value) {
                unset($weights[$value]);
            }
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','weights');
            $config->set('value',serialize($weights));
            $config->update();
        }
    }

    function action_add()  
    {
        if (isset($this->new_price) && is_array($this->new_price) && strlen($this->new_price["start"]) > 0 && strlen($this->new_price["end"]) > 0) {
            $prices = unserialize($this->config->getComplex('AdvancedSearch.prices'));
            $prices[] = $this->new_price;
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','prices');
            $config->set('value',serialize($prices));
            $config->update();
        }
        if (isset($this->new_weight) && is_array($this->new_weight) && strlen($this->new_weight["start"]) > 0 && strlen($this->new_weight["end"]) > 0) {
            $weights = unserialize($this->config->getComplex('AdvancedSearch.weights'));
            $weights[] = $this->new_weight;
            $config = new XLite_Model_Config();
            $config->set('category','AdvancedSearch');
            $config->set('name','weights');
            $config->set('value',serialize($weights));
            $config->update();
        }
    }
}
