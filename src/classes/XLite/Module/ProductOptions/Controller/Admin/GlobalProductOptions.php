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
class XLite_Module_ProductOptions_Controller_Admin_GlobalProductOptions extends XLite_Controller_Admin_AAdmin
{
    public $_categories = null;

    function getCategories() 
    {
        if (is_null($this->_categories)) {
            $c = new XLite_Model_Category();
            $this->_categories = $c->findAll();
            $names = array();
            $names_hash = array();
            for ($i = 0; $i < count($this->_categories); $i++) {
                $name = $this->_categories[$i]->get('stringPath');
                while (isset($names_hash[$name])) {
                    $name .= " ";
                }
                $names_hash[$name] = true;
                $names[] = $name;
            }
            array_multisort($names, $this->_categories);
        }
        return $this->_categories;
    }

    function action_add()
    {
        $option = new XLite_Module_ProductOptions_Model_ProductOption();
        $option->set('properties', $this->optdata);
        if (isset($this->opttype) && $this->opttype == "Text" && isset($this->text)) {
            $option->set('properties', $this->text);
        }
        if (isset($this->opttype) && $this->opttype == "Textarea" && isset($this->textarea)) {
            $option->set('properties', $this->textarea);
        }
        $option->create();

        $option->setCategoriesList($this->categories);
        $option->update();

        $this->params['option_id'] = $option->get('option_id');
        $this->option_id = $option->get('option_id');
    }

    function getAllParams($exeptions = null)
    {
        $result = parent::getAllParams();
        if (isset($this->action)) {
        	if (!isset($this->option_id) && isset($result['option_id'])) {
        		unset($result['option_id']);
        	}
        	if (isset($this->option_id)) {
        		$result['option_id'] = $this->option_id;
        	}
        }
       	return $result;
    }

    function action_delete()
    {
        if (isset($this->option_id) && isset($this->global_options) && is_array($this->global_options)) {
            $po = new XLite_Module_ProductOptions_Model_ProductOption();
            $child_po = $po->findAll("parent_option_id='".$this->option_id."'");
            if ($child_po) {
                foreach ($child_po as $option_) {
                    $option_->delete();
                }
            }
            $po->set('option_id', $this->option_id);
            $po->delete();

            if (isset($this->option_id)) {
                unset($this->option_id);
            }
        }
    }
    
    function action_update_product_option()
    {
        if (isset($this->option_id) && isset($this->global_options) && is_array($this->global_options)) {
            $po = new XLite_Module_ProductOptions_Model_ProductOption($this->option_id);
            $categories = "";
            if (isset($this->global_options['categories'])) {
                $categories = $this->global_options['categories'];
                unset($this->global_options['categories']);
            }
            if ($this->global_options['global_categories']) {
                $categories = "";
            }
            $po->set('properties', $this->global_options);
            $po->setCategoriesList($categories);
            $po->update();
        }
    }
    
    function getGlobalOptions()
    {
        if (is_null($this->globalOptions)) {
            $go = new XLite_Module_ProductOptions_Model_ProductOption();
            $this->globalOptions = $go->findAll("product_id=0");  // global options
        }
        return $this->globalOptions;
    }

}
