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

namespace XLite\Module\CDev\WishList\View\Form\Wishlist;

/**
 * Send wishlist to friend form
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Send extends \XLite\View\Form\AForm
{
    /**
     * Widget paramater names
     */
    const PARAM_WISHLIST = 'wishlist';


    /**
     * Current form name 
     * 
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getFormName()
    {
        return 'wl_send';
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

        $this->widgetParams[self::PARAM_WISHLIST] = new \XLite\Model\WidgetParam\Object('Wishlist', null, false, '\XLite\Module\CDev\WishList\Model\WishList');

        $this->widgetParams[self::PARAM_FORM_TARGET]->setValue('wishlist');
        $this->widgetParams[self::PARAM_FORM_ACTION]->setValue('send');
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @access protected
     * @since  3.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getParam(self::PARAM_WISHLIST);
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

        $this->widgetParams[self::PARAM_FORM_PARAMS]->appendValue(
            array(
                'wishlist_id' => $this->getParam(self::PARAM_WISHLIST)->get('wishlist_id'),
            )
        );
    }
}

