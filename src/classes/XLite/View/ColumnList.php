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
 * ColumnList is a universal class to create columned lists.
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_View_ColumnList extends XLite_View_Abstract
{
    /*
     * Widget parameters names
     */
    const PARAM_COLUMN_COUNT = 'columnCount';


    /**
     * columns 
     * FIXME - must be protected
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $columns = array();


    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'common/column_list.tpl';
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
            self::PARAM_COLUMN_COUNT => new XLite_Model_WidgetParam_Int('Column count', 2)
        );
    }

    /**
     * getColumns 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getColumns()
    {
        return array_fill(0, $this->getParam(self::PARAM_COLUMN_COUNT), 0);
    }

    /**
     * getColumnsData 
     * 
     * @param mixed $column ____param_comment____
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getColumnsData($column)
    {
        $data = array();

        if ($this->getParam(self::PARAM_COLUMN_COUNT) > 1) {
            $data = $this->get('data');
            $columns = $this->getParam(self::PARAM_COLUMN_COUNT);
            $cnt = ceil(count($data) / $columns);
            $pages = array_chunk($data, $cnt);

            if (isset($pages[$column])) {
                $data = $pages[$column];
            }

        } else {
            $data = $this->get('data');
        }

        return $data;
    }
}

