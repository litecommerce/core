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
 * Category selector
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_CategorySelect extends XLite_View_Abstract
{
    const PARAM_ALL_OPTION           = 'allOption';
    const PARAM_NONE_OPTION          = 'noneOption';
    const PARAM_ROOT_OPTION          = 'rootOption';
    const PARAM_FIELD_NAME           = 'fieldName';
    const PARAM_SELECTED_CATEGORY_ID = 'selectedCategoryId';
    const PARAM_CURRENT_CATEGORY_ID  = 'currentCategoryId';
    const PARAM_IGNORE_CURRENT_PATH  = 'ignoreCurrentPath';


    protected $categories = null;

    public $field;
    public $formName;
    public $selectedCategory = null;


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/select_category.tpl';
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
            self::PARAM_ALL_OPTION           => new XLite_Model_WidgetParam_Bool('Display All option', false),
            self::PARAM_NONE_OPTION          => new XLite_Model_WidgetParam_Bool('Display None option', false),
            self::PARAM_ROOT_OPTION          => new XLite_Model_WidgetParam_Bool('Display [Root level] option', false),
            self::PARAM_FIELD_NAME           => new XLite_Model_WidgetParam_String('Field name', ''),
            self::PARAM_SELECTED_CATEGORY_ID => new XLite_Model_WidgetParam_Int('Selected category id', 0),
            self::PARAM_CURRENT_CATEGORY_ID  => new XLite_Model_WidgetParam_Int('Current category id', 0),
            self::PARAM_IGNORE_CURRENT_PATH  => new XLite_Model_WidgetParam_Bool('Ignore current path', false)
        );
    }

    /**
     * Get categories condition 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoriesCondition()
    {
        return array(null, null, null, null);
    }

    /**
     * Get categories list
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories()
    {
        if (is_null($this->categories)) {
            $this->categories = array();

            $c = new XLite_Model_Category();
            $names_hash = array();

            list($where, $orderby, $groupby, $limit) = $this->getCategoriesCondition();

            $this->categories = $c->findAll($where, $orderby, $groupby, $limit);

            if ($this->getParam(self::PARAM_ROOT_OPTION) && $this->getParam(self::PARAM_CURRENT_CATEGORY_ID) > 0) {
                $currentCategory = new XLite_Model_Category($this->getParam(self::PARAM_CURRENT_CATEGORY_ID));
                $currentCategoryPath = $currentCategory->getStringPath() . '/';
                foreach ($this->categories as $i => $c) {
                    $name = $c->getStringPath();
                    if (!(strpos($name, $currentCategoryPath) === false) && strpos($name, $currentCategoryPath) == 0) {
                        unset($this->categories[$i]);
                    }
                }
            }

            foreach ($this->categories as $c) {
                $name = $c->getStringPath();

                while (isset($names_hash[$name])) {
                    $name .= ' ';
                }

                $names_hash[$name] = true;
            }

            array_multisort(array_keys($names_hash), $this->categories);
        }

        return $this->categories;
    }

    /**
     * Check - display 'No categories' option or not
     * 
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isDisplayNoCategories()
    {
        return !$this->getParam(self::PARAM_ALL_OPTION) && !$this->getCategories();
    }

    /**
     * Check - specified category selected or not
     * 
     * @param XLite_Model_Category $category Category
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isCategorySelected(XLite_Model_Category $category)
    {
        $categoryId = $this->getParam(self::PARAM_SELECTED_CATEGORY_ID);
        if (!is_numeric($categoryId) || 1 > $categoryId) {
            $categoryId = 0;
        }

        return $category->get('category_id') == $categoryId;
    }

    /**
     * getSelectedCategory
     * TODO: check if we need this function
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSelectedCategory()
    {
        if (is_null($this->selectedCategory) && !is_null($this->field)) {
            $this->selectedCategory = $this->get("component." . $this->field);
        }
        return $this->selectedCategory;
    }
    
    /**
     * setFieldName 
     * TODO: check if we need this function
     * 
     * @param string $name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function setFieldName($name)
    {
        $this->formField = $name;
        $pos = strpos($name, "[");
        if ($pos===false) {
            $this->field = $name;
        } else {
            $this->field = substr($name, $pos+1, -1);
        }
    }
}
