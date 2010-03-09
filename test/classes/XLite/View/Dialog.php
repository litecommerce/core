<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * Abstract dialog for the center region
 *  
 * @category  Litecommerce
 * @package   View
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0
 */

/**
 * Abstract dialog for the center region
 *
 * @package    View
 * @subpackage Widget
 * @since      3.0
 */
abstract class XLite_View_Dialog extends XLite_View_Container
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

        $this->widgetParams[self::PARAM_TEMPLATE]->setValue('common/dialog.tpl');
    }
}

