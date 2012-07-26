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
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\View;

/**
 * Minicart widget
 *
 *
 * @ListChild (list="layout.header.right", weight="100")
 */
class Minicart extends \XLite\View\SideBarBox
{
    /**
     * Widget parameter names
     */
    const PARAM_DISPLAY_MODE = 'displayMode';

    /**
     * Allowed display modes
     */
    const DISPLAY_MODE_HORIZONTAL = 'horizontal';

    /**
     * Number of cart items to display by default
     */
    const ITEMS_TO_DISPLAY = 3;


    /**
     * Widget directories
     *
     * @var array
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_HORIZONTAL => 'Horizontal',
    );


    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'mini_cart/minicart.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return void
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'mini_cart/minicart.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/jquery.blockUI.js';

        return $list;
    }

    /**
     * Get widget templates directory
     *
     * @return string
     */
    protected function getDir()
    {
        return 'mini_cart/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Return up to 3 items from cart
     *
     * @return array
     */
    protected function getItemsList()
    {
        return array_slice(
            $this->getCart()->getItems()->toArray(),
            0,
            min(self::ITEMS_TO_DISPLAY, $this->getCart()->countItems())
        );
    }

    /**
     * Check whether in cart there are more than 3 items
     *
     * @return boolean
     */
    protected function isTruncated()
    {
        return self::ITEMS_TO_DISPLAY < $this->getCart()->countItems();
    }

    /**
     * Return a CSS class depending on whether the minicart is empty or collapsed
     *
     * @return string
     */
    protected function getCollapsed()
    {
        return $this->getCart()->isEmpty() ? 'empty' : 'collapsed';
    }

    /**
     * Get cart total
     *
     * @return array
     */
    protected function getTotals()
    {
        return array('Total' => $this->getCart()->getTotal());
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', self::DISPLAY_MODE_HORIZONTAL, true, $this->displayModes
            ),
        );
    }
}
