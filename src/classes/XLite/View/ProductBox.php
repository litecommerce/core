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

namespace XLite\View;

/**
 * Product box widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProductBox extends \XLite\View\SideBarBox
{
    /**
     * Widget parameter names
     */
    const PARAM_PRODUCT_ID      = 'product_id';
    const PARAM_ICON_MAX_WIDTH  = 'iconWidth';
    const PARAM_ICON_MAX_HEIGHT = 'iconHeight';
    const PARAM_SHOW_BUY_NOW    = 'showBuyNow';


    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $result = parent::getCSSFiles();

        $result[] = 'products_list/products_list.css';

        return $result;
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return 'Product';
    }

    /**
     * Return templates directory name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDir()
    {
        return 'product_box';
    }

    /**
     * Get product
     *
     * @return \XLite\Model\Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getProduct()
    {
        return $this->widgetParams[self::PARAM_PRODUCT_ID]->getObject();
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

            self::PARAM_PRODUCT_ID => new \XLite\Model\WidgetParam\ObjectId\Product('Product Id', 0, true),

            self::PARAM_ICON_MAX_WIDTH => new \XLite\Model\WidgetParam\Int(
                'Maximal icon width', 180, true
            ),

            self::PARAM_ICON_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int(
                'Maximal icon height', 180, true
            ),

            self::PARAM_SHOW_BUY_NOW => new \XLite\Model\WidgetParam\Checkbox(
                'Show "Buy now" button', true, true
            ),
        );
    }

    /**
     * getIconWidth
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIconWidth()
    {
        return $this->getParam(self::PARAM_ICON_MAX_WIDTH);
    }

    /**
     * getIconHeight
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getIconHeight()
    {
        return $this->getParam(self::PARAM_ICON_MAX_HEIGHT);
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getProduct()->isAvailable();
    }

    /**
     * Flag to show "buy now" widget (buy now button)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isBuyNowVisible()
    {
        return $this->getParam(self::PARAM_SHOW_BUY_NOW);
    }

}
