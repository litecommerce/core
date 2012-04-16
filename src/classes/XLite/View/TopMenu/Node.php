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

namespace XLite\View\TopMenu;

/**
 * Node
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Node extends \XLite\View\TopMenu
{
    /**
     * Widget param names
     */

    const PARAM_TITLE      = 'title';
    const PARAM_LINK       = 'link';
    const PARAM_LIST       = 'list';
    const PARAM_CLASS      = 'className';
    const PARAM_TARGET     = 'linkTarget';
    const PARAM_EXTRA      = 'extra';
    const PARAM_PERMISSION = 'permission';

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return parent::getDir() . '/node.tpl';
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
            self::PARAM_TITLE  => new \XLite\Model\WidgetParam\String('Name', ''),
            self::PARAM_LINK   => new \XLite\Model\WidgetParam\String('Link', ''),
            self::PARAM_LIST   => new \XLite\Model\WidgetParam\String('List', ''),
            self::PARAM_CLASS  => new \XLite\Model\WidgetParam\String('Class name', ''),
            self::PARAM_TARGET => new \XLite\Model\WidgetParam\String('Target', ''),
            self::PARAM_EXTRA  => new \XLite\Model\WidgetParam\Collection('Additional request params', array()),
            self::PARAM_PERMISSION => new \XLite\Model\WidgetParam\String('Permission', ''),
        );
    }

    /**
     * Check if submenu available for this item
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function hasChildren()
    {
        return '' !== $this->getParam(self::PARAM_LIST)
            && 0 < strlen(trim($this->getViewListContent($this->getListName())));
    }

    /**
     * Check - node is branch but has empty childs list
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function isEmptyChildsList()
    {
        return '' !== $this->getParam(self::PARAM_LIST)
            && 0 == strlen(trim($this->getViewListContent($this->getListName())));
    }

    /**
     * Return list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        return 'menu.' . $this->getParam(static::PARAM_LIST);
    }

    /**
     * Return list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLink()
    {
        $link = '#';

        if ('' !== $this->getParam(static::PARAM_LINK)) {
            $link = $this->getParam(static::PARAM_LINK);

        } elseif ('' !== $this->getNodeTarget()) {
            $link = $this->buildURL($this->getNodeTarget(), '', $this->getParam(static::PARAM_EXTRA));
        }

        return $link;
    }

    /**
     * Return if the the link should be active
     * (linked to a current page)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isCurrentPageLink()
    {
        return '' !== $this->getNodeTarget() 
            && in_array($this->getTarget(), $this->getRelatedTargets($this->getNodeTarget()));
    }

    /**
     * Return CSS class for the link item
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getCSSClass()
    {
        $class = $this->getParam(static::PARAM_CLASS);

        if ($this->isCurrentPageLink()) {
            $class .= ' active';
        }

        return trim($class);
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
        return parent::isVisible()
            && !$this->isEmptyChildsList();
    }

    /**
     * Check ACL permissions
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.17
     */
    protected function checkACL()
    {
        $auth = \XLite\Core\Auth::getInstance();

        $additionalPermission = $this->getParam(self::PARAM_PERMISSION);

        return parent::checkACL()
            && (
                $this->getParam(self::PARAM_LIST)
                || $auth->isPermissionAllowed(\XLite\Model\Role\Permission::ROOT_ACCESS)
                || ($additionalPermission && $auth->isPermissionAllowed($additionalPermission))
            );
    }

    /*
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getTitle()
    {
        return $this->getParam(static::PARAM_TITLE);
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getNodeTarget()
    {
        return $this->getParam(static::PARAM_TARGET);
    }
}
