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

namespace XLite\View\ItemsList\Module;

/**
 * Addons search and installation widget
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Manage extends \XLite\View\ItemsList\Module\AModule
{
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
        $result[] = 'addons_list_installed';

        return $result;
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = $this->getDir() . '/manage/js/script.js';

        return $list;
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
        return parent::getListName() . '.manage';
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
        return 'manage';
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
        return '\XLite\View\Pager\Admin\Module\Manage';
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
        $result = parent::getSearchCondition();
        $result->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        return $result;
    }

    /**
     * Return tags array
     *
     * :TODO: actualize keys
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTags()
    {
        return array(
            ''                 => 'All',
            '____PAYMENT____'  => 'Payment',
            '____LAYOUT____'   => 'Layout',
            '____DELIVERY____' => 'Delivery',
            '____CMS____'      => 'CMS',
        );
    }

    /**
     * Return filters array
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFilters()
    {
        return array(
            ''                                   => 'All',
            \XLite\Model\Repo\Module::P_INACTIVE => 'Inactive',
        );
    }

    /**
     * Get current filter
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFilter()
    {
        $filter = \XLite\Core\Request::getInstance()->filter;

        if (empty($filter) || !in_array($filter, array_keys($this->getFilters()))) {
            $filter = '';
        }

        return $filter;
    }

    /**
     * Get current tag
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTag()
    {
        $tag = \XLite\Core\Request::getInstance()->tag;

        if (empty($tag) || !in_array($tag, array_keys($this->getTags()))) {
            $tag = '';
        }

        return $tag;
    }

    /**
     *  Get classes names for filter item
     *
     * @param string $filter Name of filter
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getFilterClasses($filter)
    {
        return $filter === $this->getFilter() ? 'current' : '';
    }

    /**
     * Get classes names for tag item
     *
     * @param string $tag Name of tag
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getTagClasses($tag)
    {
        return $tag === $this->getTag() ? 'current' : '';
    }

    /**
     * Return number of modules with certain type
     *
     * @param string $filter Filter criterion
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getModulesCount($filter)
    {
        return $this->getData($this->getSearchCondition(), true, $filter);
    }

    /**
     * Return modules list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     * @param string                 $filter    Filter criterion OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false, $filter = null)
    {
        if (!isset($filter)) {
            $filter = $this->getFilter();
        }

        if (!empty($filter)) {
            $cnd->$filter = true;
        }

        // TODO Add tags
        $cnd->tag = $this->getTag();

        return parent::getData($cnd, $countOnly);
    }

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
        return $module->isInstalled() && $this->isModuleCompatible($module) && $this->getModuleForUpdate($module);
    }

    /**
     * Check if the module is installed
     *
     * @param \XLite\Model\Module $module Module
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isInstalled(\XLite\Model\Module $module)
    {
        return true;
    }

    /**
     * Check if there are some errors for the current module
     *
     * @param \XLite\Model\Module $module Module to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasErrors(\XLite\Model\Module $module)
    {
        return !$this->isEnabled($module) && parent::hasErrors($module);
    }

    // }}}
}
