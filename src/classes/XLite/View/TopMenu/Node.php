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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    GIT: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\View\TopMenu;

/**
 * Node 
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Node extends \XLite\View\TopMenu
{
    /**
     * Widget param names
     */

    const PARAM_TITLE    = 'title';
    const PARAM_LINK     = 'link';
    const PARAM_LIST     = 'list';
    const PARAM_CLASS    = 'className';
    const PARAM_TARGET   = 'linkTarget';
    const PARAM_EXTRA    = 'extra';

    /**
     * Return widget default template
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultTemplate()
    {
        return parent::getDir() . LC_DS . 'node.tpl';
    }

    /**
     * Define widget parameters
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_TITLE => new \XLite\Model\WidgetParam\String(
                'Name', ''
            ),
            self::PARAM_LINK => new \XLite\Model\WidgetParam\String(
                'Link', ''
            ),
            self::PARAM_LIST => new \XLite\Model\WidgetParam\String(
                'List', ''
            ),
            self::PARAM_CLASS => new \XLite\Model\WidgetParam\String(
                'Class name', ''
            ),
            self::PARAM_TARGET => new \XLite\Model\WidgetParam\String(
                'Target', ''
            ),
            self::PARAM_EXTRA => new \XLite\Model\WidgetParam\Collection(
                'Additional request params', array()
            ),
        );
    }

    /**
     * Check if submenu available for this item
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function hasChildren()
    {
        return '' !== $this->getParam(self::PARAM_LIST);
    }

    /**
     * Return list name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getListName()
    {
        return 'menu.' . $this->getParam(self::PARAM_LIST);
    }

    /**
     * Return list name
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLink()
    {
        $link = '#';

        if ('' !== $this->getParam(self::PARAM_LINK)) {
            $link = $this->getParam(self::PARAM_LINK);
        } elseif ('' !== $this->getParam(self::PARAM_TARGET)) {
            $link = $this->buildURL($this->getParam(self::PARAM_TARGET), '', $this->getParam(self::PARAM_EXTRA));
        }

        return $link;
    }

    /**
     * Return if the the link should be active
     * (linked to a current page)
     *
     * @return boolean
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isCurrentPageLink()
    {
        return '' !== $this->getParam(self::PARAM_TARGET)
            && \XLite\Core\Request::getInstance()->target === $this->getParam(self::PARAM_TARGET);
    }

    /**
     * Return CSS class for the link item
     *
     * @return string
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCSSClass()
    {
        $class = $this->getParam(self::PARAM_CLASS);

        if ($this->isCurrentPageLink()) {
            $class .= ' active';
        }

        return trim($class);
    }
}
