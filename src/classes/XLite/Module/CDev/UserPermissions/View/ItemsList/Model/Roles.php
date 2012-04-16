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
 * @since     1.0.17
 */

namespace XLite\Module\CDev\UserPermissions\View\ItemsList\Model;

/**
 * Roles  items list
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Roles extends \XLite\View\ItemsList\Model\Table
{
    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/UserPermissions/roles/style.css';

        return $list;
    }

    /**
     * Define columns structure
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineColumns()
    {
        return array(
            'name' => array(
                static::COLUMN_LINK => 'role',
            ),
        );
    }

    /**
     * Define repository name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineRepositoryName()
    {
        return 'XLite\Model\Role';
    }

    /**
     * Get create entity URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateURL()
    {
        return \XLite\Core\Converter::buildUrl('role');
    }

    /**
     * Get create button label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateButtonLabel()
    {
        return 'New role';
    }

    /**
     * Creation button position
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isCreation()
    {
        return static::CREATE_INLINE_TOP;
    }

    /**
     * Mark list as switchyabvle (enable / disable)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isSwitchable()
    {
        return true;
    }

    /**
     * Mark list as removable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isRemoved()
    {
        return true;
    }

    /**
     * Get container class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return parent::getContainerClass() . ' roles';
    }

    /**
     * Get panel class
     *
     * @return \XLite\View\Base\FormStickyPanel
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getPanelClass()
    {
        return 'XLite\Module\CDev\UserPermissions\View\StickyPanel\Role\Admin\Roles';
    }

    /**
     * Define line class  as list of names
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line model
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineLineClass($index, \XLite\Model\AEntity $entity)
    {
        $classes = parent::defineLineClass($index, $entity);

        if ($this->isUnremovableRole($entity)) {
            $classes[] = 'unremovable';
        }

        return $classes;
    }

    /**
     * Check - role is unremovable or not
     * 
     * @param \XLite\Model\Role $role Role
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function isUnremovableRole(\XLite\Model\Role $role)
    {
        return $role->isPermanentRole();
    }

    /**
     * Preprocess value for Discount column
     *
     * @param mixed             $value  Value
     * @param array             $column Column data
     * @param \XLite\Model\Role $role   Entity
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function preprocessName($value, array $column, \XLite\Model\Role $role)
    {
        return $value ?: $role->getPublicName();
    }

    /**
     * Get left actions tempaltes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLeftActions()
    {
        $list = parent::getLeftActions();

        $key = array_search('items_list/model/table/parts/switcher.tpl', $list);
        if (false !== $key) {
            $list[$key] = 'modules/CDev/UserPermissions/roles/switcher.tpl';
        }

        return $list;
    }

    /**
     * Get right actions tempaltes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getRightActions()
    {
        $list = parent::getRightActions();

        $key = array_search('items_list/model/table/parts/remove.tpl', $list);
        if (false !== $key) {
            $list[$key] = 'modules/CDev/UserPermissions/roles/remove.tpl';
        }

        return $list;
    }

    /**
     * Remove entity
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function removeEntity(\XLite\Model\AEntity $entity)
    {
        return $entity->isPermanentRole() ? false : parent::removeEntity($entity);
    }
}

