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
 * PHP version 5.3.0
 *
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\View\FormField\Separator;

/**
 * \XLite\View\FormField\Separator\ShippingAddress 
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class ShippingAddress extends \XLite\View\FormField\Separator\ASeparator
{
    /**
     * Widget param names 
     */

    const PARAM_SHIP_AS_BILL_CHECKBOX = 'shipAsBillCheckbox';


    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = $this->getDir() . '/shipping_address.css';

        return $list;
    }


    /**
     * Return field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFieldTemplate()
    {
        return 'shipping_address.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_SHIP_AS_BILL_CHECKBOX => new \XLite\Model\WidgetParam\Object(
                '"Ship as bill" checkbox', null, false, '\XLite\View\FormField\Input\Checkbox\ShipAsBill'
            ),
        );
    }

    /**
     * Show the "Ship as bill" checkbox
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function showShipAsBillCheckbox()
    {
        return $this->getParam(self::PARAM_SHIP_AS_BILL_CHECKBOX)->getContent();
    }
}
