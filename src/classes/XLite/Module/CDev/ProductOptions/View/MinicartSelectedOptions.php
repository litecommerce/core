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
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Module\CDev\ProductOptions\View;

/**
 * Selected product options widget (minicart)
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="minicart.horizontal.item", weight="25")
 * @ListChild (list="minicart.vertical.item", weight="25")
 */
class MinicartSelectedOptions extends \XLite\View\AView
{
    /**
     * Widget parameter names
     */

    const PARAM_ITEM    = 'item';


    /**
     * Get options list
     *
     * @return \Doctrine\Common\Collection\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getOptions()
    {
        return $this->getItem()->getOptions();
    }


    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'modules/CDev/ProductOptions/minicart.tpl';
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
            self::PARAM_ITEM    => new \XLite\Model\WidgetParam\Object('Item', null, false, '\XLite\Model\OrderItem'),
        );
    }

    /**
     * Get order item
     *
     * @return \XLite\Model\OrderItem
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getItem()
    {
        return $this->getParam(self::PARAM_ITEM);
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getItem()->hasOptions();
    }
}
