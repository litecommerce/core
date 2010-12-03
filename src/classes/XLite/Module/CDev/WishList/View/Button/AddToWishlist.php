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
 * @subpackage ____sub_package____
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\CDev\WishList\View\Button;

/**
 * Add product to wishlist button widget
 *
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class AddToWishlist extends \XLite\View\Button\Regular
{
    /**
     * Widget parameter names
     */

    const PARAM_PRODUCT = 'product';


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
            self::PARAM_PRODUCT => new \XLite\Model\WidgetParam\Object('Product', null, false, '\XLite\Model\Product'),
        );

        $this->widgetParams[self::PARAM_ACTION]->setValue('add');
        $this->widgetParams[self::PARAM_LABEL]->setValue('Add to Wish List');
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

        $params = $this->getParam(self::PARAM_FORM_PARAMS);
        $params['target'] = 'wishlist';
        $params['product_id'] = $this->getParam(self::PARAM_PRODUCT)->get('product_id');

        $this->widgetParams[self::PARAM_FORM_PARAMS]->setValue($params);
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
            && !$this->config->General->add_on_mode
            && $this->getParam(self::PARAM_PRODUCT);
    }
}
