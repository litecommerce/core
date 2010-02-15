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
class XLite_Module_Bestsellers_View_Bestsellers extends XLite_View_Dialog
{
    /**
     * Title
     * 
     * @var    string
     * @access protected
     * @since  1.0.0
     */
    protected $head = 'Bestsellers';

    /**
     * Dialog content template
     *
     * @var    string
     * @access protected
     * @since  3.0.0 EE
     */
    protected $body = 'modules/Bestsellers/bestsellers.tpl';

    /**
     * Bestsellers list (cache)
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $bestsellers = null;    

    /**
     * Category root id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0 EE
     */
    protected $rootid = 0;

    /**
     * Use current category 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $use_node = true;

    /**
     * Initilization 
     * 
     * @return void
     * @see    ____func_see____
     * @since  3.0.0
     */
    function initView()
    {
        parent::initView();

        $this->mode = '';

        $this->visible = in_array(XLite_Core_Request::getInstance()->target, array(null, 'main', 'category'))
            && !$this->config->Bestsellers->bestsellers_menu
            && $this->getBestsellers();
    }

    /**
     * Get bestsellers list
     * 
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getBestsellers()
    {
        if (is_null($this->bestsellers)) {
            $model = new XLite_Modules_Bestsellers_Model_Bestsellers();
            $this->bestsellers = $model->getBestsellers($this->getCategoryId());
        }

        return $this->bestsellers;
    }

    /**
     * Get category id
     * 
     * @return integer
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCategoryId()
    {
        $category_id = $this->use_node ? XLite_Core_Request::getInstance()->category_id : $this->rootid;

        return  max(intval($category_id), 0);
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
            new XLite_Model_WidgetParam_Checkbox('use_node', 1, 'Use current category id'),
            new XLite_Model_WidgetParam_String('rootid', 0, 'Category root Id'),
        );
    }

    /**
     * Check passed attributes 
     * 
     * @param array $attributes attributes to check
     *  
     * @return array errors list
     * @access public
     * @since  1.0.0
     */
    public function validateAttributes(array $attributes)
    {
        $errors = parent::validateAttributes($attributes);

        // Category root id
        if (!isset($attributes['rootid']) || !is_numeric($attributes['rootid'])) {
            $errors['rootid'] = 'Category Id is not numeric!';
        } else {
            $attributes['rootid'] = intval($attributes['rootid']);
        }

        if (!$errors && 0 > $attributes['rootid']) {
            $errors['rootid'] = 'Category Id must be positive integer!';
        }

        if (!$errors && !$attributes['use_node']) {
            $category = new XLite_Model_Category($attributes['rootid']);

            if (!$category->isPersistent) {
                $errors['rootid'] = 'Category with category Id #' . $attributes['rootid'] . ' can not found!';
            }
        }

        return $errors;
    }

}
