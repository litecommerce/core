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
 * Abstract wishlist item form
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
abstract class XLite_Module_WishList_View_Form_Item_Abstract extends XLite_View_Form_Abstract
{
    /**
     * Widget paramater names
     */
    const PARAM_ITEM = 'item';


    /**
     * Current form name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return 'wl_item_' . $this->getParam(self::PARAM_ITEM)->get('wishlist_id');
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

        $this->widgetParams[self::PARAM_ITEM] = new XLite_Model_WidgetParam_Object('Wishlist item', null, false, 'XLite_Model_OrderItem');

        $this->widgetParams[self::PARAM_FORM_TARGET]->setValue('wishlist');
    }

    /**
     * Check if widget is visible
     *
     * @return bool
     * @access protected
     * @since  3.0.0
     */
    public function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_ITEM);
    }
    
    /**
     * Called before the includeCompiledFile()
     *
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function initView()
    {
        parent::initView();

        $item = $this->getParam(self::PARAM_ITEM);

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(
            array(
                'item_id'     => $item->get('item_id'),
                'wishlist_id' => $item->get('wishlist_id'),
                'product_id'  => $item->get('product_id'),
            )
        );
    }
}

