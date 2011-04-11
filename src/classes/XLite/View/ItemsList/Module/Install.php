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
     * Types of sort order 
     */
    const SORT_ORDER_ASC  = 'asc';
    const SORT_ORDER_DESC = 'desc';

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
        $result[] = 'addons_list';
    
        return $result;
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
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHead()
    {
        return '';
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
            self::PARAM_SUBSTRING    => new \XLite\Model\WidgetParam\String('Substring', ''),
            self::PARAM_TAG          => new \XLite\Model\WidgetParam\String('Tag', ''),
            self::PARAM_PRICE_FILTER => new \XLite\Model\WidgetParam\String('Price filter', ''),
        );
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
     * Return applied sortOption
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSortOption()
    {
        $sortOption = \XLite\Core\Request::getInstance()->sortBy;

        return in_array($sortOption, array_keys(static::getSortOptions())) ? $sortOption : static::SORT_OPT_ALPHA;
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
        $cnd->{\XLite\Model\Repo\Module::P_ORDER_BY} = array($this->getSortOption(), $this->getSortOrder());

        return $cnd;
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
        return $this->getSortOption() === self::SORT_OPT_ALPHA ? self::SORT_ORDER_ASC : self::SORT_ORDER_DESC;
    }














    /**
     * getSearchParams
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public static function getSearchParams()
    {
        return array(
            \XLite\Model\Repo\Module::P_SUBSTRING    => self::PARAM_SUBSTRING,
            \XLite\Model\Repo\Module::P_TAG          => self::PARAM_TAG,
            \XLite\Model\Repo\Module::P_PRICE_FILTER => self::PARAM_PRICE_FILTER,
        );
    }


    /**
     * Return list of dort options 
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected static function getSortOptions()
    {
        return array(
            static::SORT_OPT_POPULAR => 'Most Popular',
            static::SORT_OPT_RATED   => 'Most Rated',
            static::SORT_OPT_NEWEST  => 'Newest',
            static::SORT_OPT_ALPHA   => 'Alphabetically',
        );
    }

    /**
     * Return applied sortOption
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected static function getSortOption()
    {
        $sortOption = \XLite\Core\Request::getInstance()->sortBy;

        return in_array($sortOption, array_keys(static::getSortOptions())) ? $sortOption : static::SORT_OPT_POPULAR;
    }


    /**
     * Register files from common repository
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    public function getCommonFiles()
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
/*    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules_manager' . LC_DS . 'common.css';
        // TODO fix with enter-key license widget. It should be taken dynamically from AJAX
        $list[] = 'modules_manager' . LC_DS . 'enter_key' . LC_DS . 'css' . LC_DS . 'style.css';
        $list[] = $this->getDir() . LC_DS . 'style.css';

        return $list;
    }


    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        foreach (static::getSearchParams() as $modelParam => $requestParam) {
            $result->$modelParam = $this->getParam($requestParam);
        }

        // Remove substring and tag params for the Featured add-ons pages
        if (self::MODE_SEARCH !== \XLite\Core\Request::getInstance()->mode) {
            $result->{self::PARAM_SUBSTRING} = null;
            $result->{self::PARAM_TAG} = null;
            $result->{self::PARAM_PRICE_FILTER} = null;
        }

        return $result;
    }

    /**
     * Define so called "request" parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function defineRequestParams()
    {
        parent::defineRequestParams();

        $this->requestParams = array_merge(
            $this->requestParams,
            static::getSearchParams()
        );
    }

    /**
     * Get URL common parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getCommonParams()
    {
        $mode = static::MODE_SEARCH === \XLite\Core\Request::getInstance()->mode
            ? static::MODE_SEARCH
            : static::MODE_FEATURED;

        return parent::getCommonParams() + array('mode' => $mode);
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getListName()
    {
        return parent::getListName() . '.install';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getHead()
    {
        return '';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getPageBodyDir()
    {
        return 'install';
    }

    /**
     * Return list of the modes allowed by default
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getDefaultModes()
    {
        $list = parent::getDefaultModes();
        $list[] = static::MODE_SEARCH;
        $list[] = static::MODE_FEATURED;
        $list[] = '';

        return $list;
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getSortByModeDefault()
    {
        return parent::SORT_BY_MODE_POPULAR;
    }

    /**
     * getSortOrder
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getSortOrder()
    {
        return self::getSortOption() === self::SORT_OPT_ALPHA ? self::SORT_ORDER_ASC : self::SORT_ORDER_DESC;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Admin\Module\Install';
    }

    /**
     * Check if the module can be installed
     *
     * FIXME: actualize
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function canInstall(\XLite\Model\Module $module)
    {
        return !$module->getInstalled() && ($module->isPurchased() || $module->isFree());
    }

    /**
     * Check if the module can be installed
     *
     * FIXME: actualize
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
/*    protected function canPurchase(\XLite\Model\Module $module)
    {
        return !$module->getInstalled() && !$module->isPurchased() && !$module->isFree();
    }*/
}
