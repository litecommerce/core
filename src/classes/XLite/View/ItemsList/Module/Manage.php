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
 * @since     3.0.0
 */

namespace XLite\View\ItemsList\Module;

/**
 * Addons search and installation widget
 *
 * @see   ____class_see____
 * @since 3.0.0
 */
class Manage extends \XLite\View\ItemsList\Module\AModule
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'modules';
    
        return $result;
    }

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();
        $list[] = 'modules_manager' . LC_DS . 'common.css';

        return $list;
    }

    /**
     * Return name of the base widgets list
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return parent::getListName() . '.manage';
    }

    /**
     * Return title
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHead()
    {
        return null;
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
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
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();
        $result->{\XLite\Model\Repo\Module::P_INSTALLED} = true;

        return $result;
    }

    /**
     * Return filters array
     *
     * @return array
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFilters()
    {
        return array(
            ''                                     => 'All',
            \XLite\Model\Repo\Module::P_INACTIVE   => 'Inactive',
            \XLite\Model\Repo\Module::P_UPGRADABLE => 'Upgradable',
        );
    }

    /**
     * Get current filter
     * 
     * @return string
     * @see    ____func_see____
     * @since  3.0.0
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
     * Return number of modules with certain type
     * 
     * @param string $filter Filter criterion
     *  
     * @return integer
     * @see    ____func_see____
     * @since  3.0.0
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
     * @since  3.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false, $filter = null)
    {
        if (!empty($filter) || ($filter = $this->getFilter())) {
            $cnd->$filter = true;
        }

        return parent::getData($cnd, $countOnly);
    }
}
