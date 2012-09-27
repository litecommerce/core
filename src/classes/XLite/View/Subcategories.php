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
 * Subcategories list
 *
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
     * @var array
     */
    protected $displayModes = array(
        self::DISPLAY_MODE_LIST  => 'List',
        self::DISPLAY_MODE_ICONS => 'Icons',
    );

    /**
     * Return list of targets allowed for this widget
     *
     * @return array
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
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        if (!\XLite::isAdminZone()) {
            $list[] = 'common/grid-list.css';
        }

        return $list;
    }

    /**
     * Return title
     *
     * @return string
     */
    protected function getHead()
    {
        return null;
    }

    /**
     * Return templates directory name
     *
     * @return string
     */
    protected function getDir()
    {
        return 'subcategories/' . $this->getParam(self::PARAM_DISPLAY_MODE);
    }

    /**
     * Get widget display mode
     *
     * @return void
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
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->isCategoryVisible() && $this->hasSubcategories();
    }

    /**
     * Widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_DISPLAY_MODE => new \XLite\Model\WidgetParam\Set(
                'Display mode', $this->getDisplayMode(), true, $this->displayModes
            ),
            self::PARAM_ICON_MAX_WIDTH => new \XLite\Model\WidgetParam\Int(
                'Maximal icon width', 160, true
            ),
            self::PARAM_ICON_MAX_HEIGHT => new \XLite\Model\WidgetParam\Int(
                'Maximal icon height', 160, true
            ),
        );
    }

    /**
     * Return the maximal icon width
     *
     * @return integer
     */
    protected function getIconWidth()
    {
        return $this->getParam(self::PARAM_ICON_MAX_WIDTH);
    }

    /**
     * Return the maximal icon height
     *
     * @return integer
     */
    protected function getIconHeight()
    {
        return $this->getParam(self::PARAM_ICON_MAX_HEIGHT);
    }

    /**
     * getColumnsCount
     *
     * @return integer
     */
    protected function getColumnsCount()
    {
        return 3;
    }

    /**
     * Return subcategories split into rows
     *
     * @return array
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
     */
    protected function hasSubcategories()
    {
        return $this->getCategory() ? $this->getCategory()->hasSubcategories() : false;
    }

    /**
     * Return subcategories
     *
     * @return array
     */
    protected function getSubcategories()
    {
        return $this->getCategory() ? $this->getCategory()->getSubcategories() : array();
    }

    /**
     * Check if the category is visible
     *
     * @return boolean
     */
    protected function isCategoryVisible()
    {
        return $this->getCategory() ? $this->getCategory()->isVisible() : false;
    }
}
