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

namespace XLite\View\ItemsList\Model;

/**
 * Attributes items list
 *
 */
class Attribute extends \XLite\View\ItemsList\Model\Table
{
    /**
     * Widget param names
     */

    const PARAM_GROUP = 'group';

    /**
     * Define columns structure
     *
     * @return array
     */
    protected function defineColumns()
    {
        return array(
            'name' => array(    
                static::COLUMN_NAME     => $this->getAttributeGroup() 
                    ? $this->getAttributeGroup()->getName() 
                    : \XLite\Core\Translation::lbl('No group'),
                static::COLUMN_CLASS    => 'XLite\View\FormField\Inline\Input\Text',
                static::COLUMN_PARAMS   => array('required' => true),
            ),
            'type' => array(
                static::COLUMN_NAME     => $this->getAttributeGroup() 
                    ? static::t(
                        'X attributes in group',
                        array(
                            'count' => $this->getAttributeGroup()->getAttributesCount() 
                        )
                    )
                    : null,
                static::COLUMN_TEMPLATE => 'attributes/parts/type.tpl',
            ),
        );
    }

    /**
     * Define repository name
     *
     * @return string
     */
    protected function defineRepositoryName()
    {
        return 'XLite\Model\Attribute';
    }

    /**
     * Get create entity URL
     *
     * @return string
     */
    protected function getCreateURL()
    {
        return \XLite\Core\Converter::buildUrl('attribute');
    }

    /**
     * Get create button label
     *
     * @return string
     */
    protected function getCreateButtonLabel()
    {
        return 'New attribute';
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_GROUP => new \XLite\Model\WidgetParam\Object(
                'Group', null, false,  '\XLite\Model\AttributeGroup'
            ),
        );
    }

    /**
     * Get attribute group 
     *
     * @return \XLite\Model\AttributeGroup
     */
    protected function getAttributeGroup()
    {
        return $this->getParam(static::PARAM_GROUP);
    }

    // {{{ Behaviors

    /**
     * Mark list as removable
     *
     * @return boolean
     */
    protected function isRemoved()
    {
        return true;
    }

    /**
     * Mark list as sortable
     *
     * @return integer
     */
    protected function getSortableType()
    {
        return static::SORT_TYPE_MOVE;
    }

    /**
     * Check if there are any results to display in list
     *
     * @return void
     */
    protected function hasResults()
    {
        return true;
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return $this->getAttributeGroup() 
            || 0 < $this->getItemsCount();
    }

    /**
     * Check - pager box is visible or not
     *
     * @return boolean
     */
    protected function isPagerVisible()
    {
        return false;
    }

    // }}}

    /**
     * Get container class
     *
     * @return string
     */
    protected function getContainerClass()
    {
        $class = parent::getContainerClass() . ' attributes';

        if ($this->getAttributeGroup()) {
            $class = parent::getContainerClass()
                . ' group';
        }

        return $class;
    }

    /**
     * Get panel class
     *
     * @return \XLite\View\Base\FormStickyPanel
     */
    protected function getPanelClass()
    {
        $groups = $this->getAttributeGroups();

        return (
            $this->getProductClass()->getAttributesCount()
            && (
                !$groups->count() 
                || (
                    $this->getAttributeGroup() 
                    && $groups->last()->getId() == $this->getAttributeGroup()->getId()
                )
            )
        )
            ? 'XLite\View\StickyPanel\ItemsList\Attribute'
            : null;
    }


    // {{{ Search

    /**
     * Return search parameters.
     *
     * @return array
     */
    static public function getSearchParams()
    {
        return array();
    }

    /**
     * Return params list to use for search
     * TODO refactor
     *
     * @return \XLite\Core\CommonCell
     */
    protected function getSearchCondition()
    {
        $result = parent::getSearchCondition();

        foreach (static::getSearchParams() as $modelParam => $requestParam) {
            $paramValue = $this->getParam($requestParam);

            if ('' !== $paramValue && 0 !== $paramValue) {
                $result->$modelParam = $paramValue;
            }
        }

        $result->productClass = $this->getProductClass();
        if (\XLite\Core\Request::getInstance()->isGet()) {
            $result->attributeGroup = $this->getAttributeGroup();
        }

        return $result;
    }

    // }}}

}
