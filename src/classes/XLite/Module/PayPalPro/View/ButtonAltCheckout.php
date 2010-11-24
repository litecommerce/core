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

namespace XLite\Module\PayPalPro\View;

/**
 * PayPal Pro checkout button
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @ListChild (list="cart.buttons.checkout", weight="20")
 */
class ButtonAltCheckout extends \XLite\View\Button\Image
{
    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/PayPalPro/button.tpl';
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

        $this->widgetParams[self::PARAM_ACTION]->setValue('express_checkout');
    }

    /**
     * Check if widget is visible
     *
     * @return boolean 
     * @access public
     * @since  3.0.0
     */
    protected function isVisible()
    {
        $result = parent::isVisible();

        if ($result) {
            $pm = new \XLite\Model\PaymentMethod('paypalpro');

            $result = $pm->isExists()
                && 1 == $pm->get('enabled')
                && in_array($pm->getComplex('params.solution'), array('pro', 'express'));
        }

        return $result;
    }
}
