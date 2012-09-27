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

namespace XLite\View\ItemsList\Module;

/**
 * Addons search and installation widget
 *
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
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/ui.selectmenu.js';
        // popup button is using several specific popup JS
        $list['js'][] = 'js/core.popup.js';
        $list['js'][] = 'js/core.popup_button.js';

        $list['css'][] = 'css/ui.selectmenu.css';

        return $list;
    }

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules_manager/css/common.css';

        // TODO fix with enter-key license widget. It should be taken dynamically from AJAX
        $list[] = 'modules_manager/enter_key/css/style.css';

        // TODO must be taken from LICENSE module widget
        $list[] = 'modules_manager/license/css/style.css';
        $list[] = 'modules_manager/installation_type/css/style.css';

        // TODO must be taken from SwitchButton widget
        $list[] = \XLite\View\Button\SwitchButton::SWITCH_CSS_FILE;

        return $list;
    }

    /**
     * Register JS files. TODO REWORK with Popup button widget
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        // TODO must be taken from Button/InstallAddon widget
        $list[] = 'button/js/install_addon.js';
        $list[] = 'button/js/select_installation_type.js';
        // TODO must be taken from SwitchButton widget
        $list[] = \XLite\View\Button\SwitchButton::JS_SCRIPT;
        // TODO must be taken from LICENSE module widget
        $list[] = 'modules_manager/license/js/switch-button.js';
        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/js/controller.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return $this->getDir() . '/' . $this->getPageBodyDir() . '/'
            . ($this->isMarketplaceAccessible() ? 'items_list' : 'marketplace_not_accessible') . '.tpl';
    }

    /**
     * Check if marketplace is accessible
     *
     * @return boolean
     */
    protected function isMarketplaceAccessible()
    {
        return $this->isPHARAvailable() && !is_null(\XLite\Core\Marketplace::getInstance()->checkForUpdates());
    }

    /**
     * Check if phar extension is loaded
     *
     * @return boolean
     */
    protected function isPHARAvailable()
    {
        return extension_loaded('phar');
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     */
    protected function getPagerClass()
    {
        return '\XLite\View\Pager\Admin\Module\Install';
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     */
    protected function getListName()
    {
        return parent::getListName() . '.install';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     */
    protected function getPageBodyDir()
    {
        return 'install';
    }

    /**
     * isHeaderVisible
     *
     * @return boolean
     */
    protected function isHeaderVisible()
    {
        return true;
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
     */
    protected function getPriceFilterOptions()
    {
        return array(
            self::PRICE_FILTER_OPT_ALL  => 'All add-ons',
            self::PRICE_FILTER_OPT_FREE => 'Free add-ons',
        );
    }

    /**
     * getSortByModeDefault
     *
     * @return string
     */
    protected function getSortByModeDefault()
    {
        return self::SORT_OPT_ALPHA;
    }

    /**
     * getSortOrder
     *
     * @return string
     */
    protected function getSortOrder()
    {
        return self::SORT_OPT_ALPHA === $this->getSortBy() ? self::SORT_ORDER_ASC : self::SORT_ORDER_DESC;
    }

    /**
     * Return params list to use for search
     *
     * @return \XLite\Core\CommonCell
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

    /**
     * Return warning message. Description of Marketplace unavailability
     *
     * @return string
     */
    protected function getWarningMessage()
    {
        return $this->isPHARAvailable()
            ? 'Can\'t connect to the Modules Marketplace server'
            : 'You need Phar extension for PHP on your server to download modules from Modules Marketplace';
    }

    // {{{ Helpers to use in templates

    /**
     * Check if the module is installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     */
    protected function isInstalled(\XLite\Model\Module $module)
    {
        return $module->isInstalled();
    }

    /**
     * Check if the module is free
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
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
     */
    protected function isPurchased(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) && !$this->isFree($module) && $module->isPurchased();
    }

    /**
     * Check if there are some errors for the current module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     */
    protected function hasErrors(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) && parent::hasErrors($module);
    }

    /**
     * Check if the module can be enabled
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     */
    protected function canEnable(\XLite\Model\Module $module)
    {
        return parent::canEnable($module) && !$this->isInstalled($module);
    }

    /**
     * Check if the module can be installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     */
    protected function canInstall(\XLite\Model\Module $module)
    {
        return $this->canEnable($module) && $this->canAccess($module) && $module->getFromMarketplace();
    }

    /**
     * Check if the module can be installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     */
    protected function canPurchase(\XLite\Model\Module $module)
    {
        return !$this->isInstalled($module) && !$this->canAccess($module);
    }

    /**
     * Check if module is accessible
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     */
    protected function canAccess(\XLite\Model\Module $module)
    {
        return $this->isPurchased($module) || $this->isFree($module);
    }

    // }}}

    // {{{ Methods to search modules of certain types

    /**
     * Check if core requires new (but the same as core major) version of module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     */
    protected function isModuleUpdateAvailable(\XLite\Model\Module $module)
    {
        $installed = $this->getModuleInstalled($module);

        return $installed
            && version_compare($installed->getMajorVersion(), $module->getMajorVersion(), '=')
            && version_compare($installed->getMinorVersion(), $module->getMinorVersion(), '<');
    }

    // }}}

    // {{{ Purchase form

    /**
     * Get purchase page URL
     *
     * :FIXME: is it really needed?
     *
     * @return string
     */
    protected function getPurchaseURL()
    {
        $apiURL = trim(
            \Includes\Utils\Converter::trimTrailingChars(
                \XLite\Core\Marketplace::getInstance()->getMarketplaceURL()
                , '/'
            )
        );

        // Remove 'api' directory
        $apiURL = preg_replace('/\?q=.+/Ss', '', $apiURL);
        $apiURL = preg_replace('/\/api$/Ss', '/', $apiURL);

        return $apiURL;
    }

    /**
     * Get return URL for Purchase operation
     *
     * @return string
     */
    protected function getReturnURL()
    {
        return \Includes\Utils\URLManager::getShopURL(\XLite\Core\Converter::buildURL());
    }

    // }}}
}
