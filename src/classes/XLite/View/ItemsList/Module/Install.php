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

namespace XLite\View\ItemsList\Module;

/**
 * Addons search and installation widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Install extends \XLite\View\ItemsList\Module\AModule
{
    /**
     * Sort option name definitions
     */
    const SORT_OPT_POPULAR    = 'm.downloads';
    const SORT_OPT_RATED      = 'm.rating';
    const SORT_OPT_NEWEST     = 'm.revisionDate';
    const SORT_OPT_ALPHA      = 'm.moduleName';

    /**
     * Price filter options
     */
    const PRICE_FILTER_OPT_ALL  = 'all';
    const PRICE_FILTER_OPT_FREE = \XLite\Model\Repo\Module::PRICE_FREE;
    const PRICE_FILTER_OPT_PAID = \XLite\Model\Repo\Module::PRICE_PAID;

    /**
     * Widget param names
     */
    const PARAM_SUBSTRING    = 'substring';
    const PARAM_TAG          = 'tag';
    const PARAM_PRICE_FILTER = 'priceFilter';


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
        $result[] = 'addons_list_marketplace';
    
        return $result;
    }

    /**
     * Define and set widget attributes; initialize widget
     *
     * @param array $params Widget params OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct(array $params = array())
    {
        $this->sortByModes += $this->getSortOptions();

        parent::__construct($params);
    }

    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/ui.selectmenu.js';
        $list['css'][] = 'css/ui.selectmenu.css';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules_manager' . LC_DS . 'common.css';
        // TODO fix with enter-key license widget. It should be taken dynamically from AJAX
        $list[] = 'modules_manager' . LC_DS . 'enter_key' . LC_DS . 'css' . LC_DS . 'style.css';
        $list[] = $this->getDir() . LC_DS . 'style.css';

        return $list;
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
        return $this->getDir() . LC_DS . $this->getPageBodyDir() . LC_DS . 'items_list.tpl';
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Admin\Module\Install';
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.install';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageBodyDir()
    {
        return 'install';
    }

    /**
     * isHeaderVisible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHeaderVisible()
    {
        return true;
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
            self::PARAM_SUBSTRING => new \XLite\Model\WidgetParam\String(
                'Substring', ''
            ),
            self::PARAM_TAG => new \XLite\Model\WidgetParam\String(
                'Tag', ''
            ),
            self::PARAM_PRICE_FILTER => new \XLite\Model\WidgetParam\Set(
                'Price filter', self::PRICE_FILTER_OPT_ALL, false, $this->getPriceFilterOptions()
            ),
        );
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams[] = self::PARAM_PRICE_FILTER;
        $this->requestParams[] = self::PARAM_SUBSTRING;
    }

    /**
     * Return list of dort options
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortOptions()
    {
        return array(
            static::SORT_OPT_POPULAR => 'Most Popular',
            static::SORT_OPT_RATED   => 'Most Rated',
            static::SORT_OPT_NEWEST  => 'Newest',
            static::SORT_OPT_ALPHA   => 'Alphabetically',
        );
    }

    /**
     * Return list of price filter options
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPriceFilterOptions()
    {
        return array(
            self::PRICE_FILTER_OPT_ALL  => 'No price filtering',
            self::PRICE_FILTER_OPT_FREE => 'Free add-ons',
            self::PRICE_FILTER_OPT_PAID => 'Commercial add-ons',
        );
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortByModeDefault()
    {
        return self::SORT_OPT_ALPHA;
    }

    /**
     * getSortOrder
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortOrder()
    {
        return self::SORT_OPT_ALPHA === $this->getSortBy() ? self::SORT_ORDER_ASC : self::SORT_ORDER_DESC;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchCondition()
    {
        $cnd = parent::getSearchCondition();
        $cnd->{\XLite\Model\Repo\Module::P_ORDER_BY}         = array($this->getSortBy(), $this->getSortOrder());
        $cnd->{\XLite\Model\Repo\Module::P_PRICE_FILTER}     = $this->getParam(self::PARAM_PRICE_FILTER);
        $cnd->{\XLite\Model\Repo\Module::P_SUBSTRING}        = $this->getParam(self::PARAM_SUBSTRING);
        $cnd->{\XLite\Model\Repo\Module::P_FROM_MARKETPLACE} = true;

        return $cnd;
    }

    // {{{ Helpers to use in templates

    /**
     * Check if the module is purchased
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isInstalled(\XLite\Model\Module $module)
    {
        return $module->getInstalled();
    }

    /**
     * Check if the module is free
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isFree(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) && $module->isFree();
    }

    /**
     * Check if the module is purchased
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isPurchased(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) && !$this->isFree($module) && $module->isPurchased();
    }

    /**
     * Check if the module can be installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function canInstall(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) 
            && ($this->isPurchased($module) || $this->isFree($module)) 
            && $this->canEnable($module)
            && $module->getMarketplaceID();
    }

    /**
     * Check if the module can be installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function canPurchase(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) 
            && !$this->isPurchased($module) 
            && !$this->isFree($module)
            && $this->canEnable($module);
    }

    // }}}

    // {{{ Methods to search modules of certain types

    /**
     * Check if core requires new (but the same as core major) version of module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isModuleUpdateAvailable(\XLite\Model\Module $module)
    {
        $installed = $this->getModuleInstalled($module);

        return $installed 
            && version_compare($installed->getMajorVersion(), $module->getMajorVersion(), '=') 
            && version_compare($installed->getMinorVersion(), $module->getMinorVersion(), '<');
    }

    // }}}
}
