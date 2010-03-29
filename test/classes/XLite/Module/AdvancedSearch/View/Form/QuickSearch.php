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
 * Advanced quick search form
 * 
 * @package    XLite
 * @subpackage View
 * @since      3.0.0
 */
class XLite_Module_AdvancedSearch_View_Form_QuickSearch extends XLite_Module_AdvancedSearch_View_Form_Search
{
    /**
     * Current form name
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return 'advanced_quick_search';
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

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(
            array(
                'search' => array(
                    'logic' => 1,
                    'title' => 1,
                    'brief_description' => 1,
                    'meta_tags' => 1,
                    'extra_fields' => 1,
                    'options' => 1,
                    'subcategories' => 1,
                )
            )
        );
    }
}

