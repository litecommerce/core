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
 * Subcategories list
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @ListChild (list="center.bottom", zone="customer", weight="100")
 */
class Subcategories extends \XLite\View\Dialog
{
    /**
     * Widget parameter names
     */
    const PARAM_DISPLAY_MODE = 'displayMode';
    const PARAM_ICON_MAX_WIDTH = 'iconWidth';
    const PARAM_ICON_MAX_HEIGHT = 'iconHeight';

    /**
     * Allowed display modes
     */
    const DISPLAY_MODE_LIST  = 'list';
    const DISPLAY_MODE_ICONS = 'icons';


    /**
     * Display modes
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_LIST  => 'List',
        self::DISPLAY_MODE_ICONS => 'Icons',
    );

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'main';
        $result[] = 'category';

        return $result;
    }

    /**
     * Return list of required CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'common/grid-list.css';

        return $list;
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
        return null;
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
        return 'subcategories' . LC_DS . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Get widget display mode
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDisplayMode()
    {
        return $this->getParam(self::PARAM_IS_EXPORTED)
            ? $this->getParam(self::PARAM_DISPLAY_MODE)
            : \XLite\Core\Config::getInstance()->General->subcategories_look;
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
        return parent::isVisible() && $this->hasSubcategories();
    }

    /**
     * Widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', self::DISPLAY_MODE_ICONS, true, $this->displayModes
            ),
            self::PARAM_ICON_MAX_WIDTH => new \XLite\Model\WidgetParam\Int(
                'Maximal icon width', 170, true
            ),
            self::PARAM_ICON_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int(
                'Maximal icon height', 170, true
            ),
        );
    }

    /**
     * Return the maximal icon width
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
     * Return the maximal icon height
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
     * getColumnsCount
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getColumnsCount()
    {
        return 3;
    }

    /**
     * Return subcategories split into rows
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCategoryRows()
    {
        $rows = array_chunk($this->getSubcategories(), $this->getColumnsCount());
        $last = count($rows) - 1;
        $rows[$last] = array_pad($rows[$last], $this->getColumnsCount(), false);

        return $rows;
    }

    /**
     * Check for subcategories
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function hasSubcategories()
    {
        return $this->getCategory() ? $this->getCategory()->hasSubcategories() : false;
    }

    /**
     * Return subcategories
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSubcategories()
    {
        return $this->getCategory() ? $this->getCategory()->getSubcategories() : array();
    }
}
