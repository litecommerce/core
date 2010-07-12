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

namespace XLite\Module\ProductOptions\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Controller\Admin\Product implements \XLite\Base\IDecorator
{
    public function __construct(array $params)
    {
        parent::__construct($params);
        $this->pages['product_options'] = "Product options";
        $this->pageTemplates['product_options'] = "modules/ProductOptions/product_options.tpl";
    }

    function action_update_limit()
    {
        $product = new \XLite\Model\Product($this->product_id);
        $limit = isset($this->expansion_limit) ? 1 : 0;
        $product->set('expansion_limit', $limit);
        $product->update();
    }

    // PRODUCT OPTION METHODS {{{

    function getProductOption() 
    {
        if (is_null($this->option)) {
            $this->option = new \XLite\Module\ProductOptions\Model\ProductOption();
            if (isset($this->option_id)) {
                $this->option->set('option_id', $this->option_id);
            }
            if (isset($this->product_id)) {
                $this->option->set('product_id', $this->product_id);
            }
            if (isset($this->optdata)) {
                foreach ((array)$this->optdata as $key=>$value) {
                    $this->optdata[$key] = preg_replace("/\|/", "-", $value);
                }
                $this->option->set('properties', $this->optdata);
            }
            if (isset($this->opttype) && $this->opttype == "Text" && isset($this->text)) {
                $this->option->set('properties', $this->text);
            }
            if (isset($this->opttype) && $this->opttype == "Textarea" && isset($this->textarea)) {
                $this->option->set('properties', $this->textarea);
            }
        }
        return $this->option;
    }
    
    function action_update_product_option() 
    {
        $option = $this->get('productOption');
        $option->update();

        $this->params['option_id'] = $option->get('option_id');
        $this->option_id = $option->get('option_id');
    }

    function action_delete_product_option() 
    {
        $option = $this->get('productOption');
        $option->delete();

        if (isset($this->option_id)) {
            unset($this->option_id);
        }
    }

    function action_add_product_option() 
    {
    	if (isset($this->option_id)) {
    		unset($this->option_id);
    	}

        $option = $this->get('productOption');
        $option->create();

        $this->params['option_id'] = $option->get('option_id');
        $this->option_id = $option->get('option_id');
    }
    
    

    // OPTION EXCEPTION METHODS {{{

    function getOptionException() 
    {
        if (is_null($this->optionException)) {
            $this->optionException = new \XLite\Module\ProductOptions\Model\OptionException();
            if (isset($this->option_id)) {
                $this->optionException->set('option_id', $this->option_id);
            }
            if (isset($this->product_id)) {
                $this->optionException->set('product_id', $this->product_id);
            }
            if (isset($this->exception) && strlen(trim($this->exception))) {
                $this->optionException->set('exception', $this->exception);
            }
        }
        return $this->optionException;
    }

    function action_update_option_exception() 
    {
        $exception = $this->get('optionException');
        $exception->update();
    }

    function action_delete_option_exception() 
    {
        $exception = $this->get('optionException');
        $exception->delete();
    }

    function action_add_option_exception() 
    {
    	if (isset($this->option_id)) {
    		unset($this->option_id);
    	}

        $exception = $this->get('optionException');
        if (!$exception->find("product_id='".$exception->get('product_id')."' AND exception='".addslashes($exception->get('exception'))."'")) {
        	$exception->create();
        }
    }
    
    

    // OPTION VALIDATOR METHOD {{{
    
    function action_product_option_validator()
    {
        $validator = new \XLite\Module\ProductOptions\Model\OptionValidator();
        $validator->set('product_id', $this->product_id);
        if (isset($this->javascript_code) && strlen(trim($this->javascript_code))) {
            $validator->set('javascript_code', $this->javascript_code);
        }

        // add / update / delete
        if ($validator->read()) {
            if (!strlen(trim($this->javascript_code))) {
                $validator->delete();
            } else {
                $validator->update();
            }
        } elseif (strlen(trim($validator->get('javascript_code')))) {
            $validator->create();
        }
    }

    

    function isOddRow($row)
    {
        return (($row % 2) == 0) ? true : false;
    }

    function getRowClass($row,$odd_css_class,$even_css_class = null)
    {
        return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
    }

    function action_info()
    {
        $product = new \XLite\Model\Product($this->product_id);
        $oldCategories = array();
        $categories = $product->get('categories');
        if (is_array($categories)) {
        	foreach ($categories as $cat) {
        		$oldCategories[] = $cat->get('category_id');
        	}
        }

    	parent::action_info();

        $product->updateGlobalProductOptions($oldCategories);
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
}
