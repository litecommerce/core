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

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class ExtraFields extends \XLite\Controller\Admin\AAdmin
{
    public $_categories = null;

    function fillForm()
    {
        if (!isset($this->name)) {
            $ef = new \XLite\Model\ExtraField();
            $this->set('properties', $ef->fields);
        }
        parent::fillForm();
    }
    
    function isCategorySelected($name, $categoryID)
    {
        return (isset(\XLite\Core\Request::getInstance()->$name) && is_array(\XLite\Core\Request::getInstance()->$name)) ? in_array($categoryID, \XLite\Core\Request::getInstance()->$name) : false;
    }
    
    function getCategories() 
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategories(!is_null($categoryId) ? $categoryId : $this->getCategoryId());
    }

    function getExtraFields()
    {
        if (is_null($this->extraFields)) {
            $ef = new \XLite\Model\ExtraField();
            $this->extraFields = $ef->findAll("product_id=0");  // global fields
        }
        return $this->extraFields;
    }
    
    function action_update_fields()
    {
        if (!is_null($this->get('delete')) && !is_null($this->get('delete_fields')) && $this->get('delete') == "delete") {
            foreach ((array)$this->get('delete_fields') as $id) {
                $ef = new \XLite\Model\ExtraField($id);
                $ef->delete();
            }
        } elseif (!is_null($this->get('update'))) {
            foreach ((array)$this->get('extra_fields') as $id => $data) 
            {
                $rewrite = !(isset($data['rewrite']) && $data['rewrite'] == "yes");
                $ef = new \XLite\Model\ExtraField($id);
                $ef->set('categories_old', $ef->get('categories'));

                if ($data['global'] == 0){
                    $data['categories'] = '';
                    if ($ef->get('categories') != ""){
                        if ($rewrite){
                            
                        } else {
                            $ef->set('categories_old', "");
                        }
                    } else {
                        if ($rewrite){
                            $old = array();
                            $category = new \XLite\Model\Category();
                            $categories = $category->findAll();
                            foreach ($categories as $category) {
                                $old[] = $category->get('category_id');
                            }
                            $old = implode("|", $old);
                            $ef->set('categories_old', $old);
                        } else {

                        }
                    }
                } else {
                    if ($ef->get('categories') != ""){
                        if ($rewrite){
                            $ef->set('categories_old', "");
                        } else {
                        }
                    } else {
                        if ($rewrite){
                            
                        } else {
                            $old = array();
                            $category = new \XLite\Model\Category();
                            $categories = $category->findAll();
                            foreach ($categories as $category) {
                                $old[] = $category->get('category_id');
                            }
                            $old = implode("|", $old);
                            $ef->set('categories_old', $old);
                        }
                    }
                }

                $ef->set('properties', $data);
                $ef->update();
            }
       }
    }
    
    function action_add_field()
    {
        if (!is_null($this->get('add_field'))) {
            $categories = (array)$this->get('add_categories');

            $ef = new \XLite\Model\ExtraField();
            $ef->set('properties', \XLite\Core\Request::getInstance()->getData());
            if (!empty($categories)) {
                $ef->setCategoriesList($categories);
            }
            $ef->create();
        }
    }
}
