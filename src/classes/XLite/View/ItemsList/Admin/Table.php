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
 * @since     1.0.15
 */

namespace XLite\View\ItemsList\Admin;

/**
 * Abstract admin model-based items list (table)
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class Table extends \XLite\View\ItemsList\Admin\AAdmin
{
    const COLUMN_NAME          = 'name';
    const COLUMN_TEMPLATE      = 'template';
    const COLUMN_CLASS         = 'class';
    const COLUMN_CODE          = 'code';
    const COLUMN_LINK          = 'link';
    const COLUMN_METHOD_SUFFIX = 'methodSuffix';
    const COLUMN_CREATE_CLASS  = 'createClass';

    /**
     * Columns (local cache)
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $columns;

    /**
     * Define columns structure
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function defineColumns();

    /**
     * Get a list of CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/style.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/controller.js';

        return $list;
    }

    /**
     * Get preprocessed columns structire
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getColumns()
    {
        if (!isset($this->columns)) {
            $this->columns = array();

            if ($this->getLeftActions()) {
                $this->columns[] = array(
                    static::COLUMN_CODE     => 'actions left',
                    static::COLUMN_NAME     => '',
                    static::COLUMN_TEMPLATE => 'items_list/model/table/left_actions.tpl',
                );
            }

            foreach ($this->defineColumns() as $idx => $column) {
                $column[static::COLUMN_CODE] = $idx;
                $column[static::COLUMN_METHOD_SUFFIX] = \XLite\Core\Converter::convertToCamelCase($column[static::COLUMN_CODE]);
                if (!isset($column[static::COLUMN_TEMPLATE]) && !isset($column[static::COLUMN_CLASS])) {
                    $column[static::COLUMN_TEMPLATE] = 'items_list/model/table/field.tpl';
                }
                $this->columns[] = $column;
            }

            if ($this->getRightActions()) {
                $this->columns[] = array(
                    static::COLUMN_CODE     => 'actions right',
                    static::COLUMN_NAME     => '',
                    static::COLUMN_TEMPLATE => 'items_list/model/table/right_actions.tpl',
                );
            }
        }

        return $this->columns;
    }

    /**
     * Get column value 
     * 
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getColumnValue(array $column, \XLite\Model\AEntity $entity)
    {
        $suffix = $column[static::COLUMN_METHOD_SUFFIX];

        // Getter
        $method = 'get' . $suffix . 'ColumnValue';
        $value = method_exists($this, $method)
            ? $this->$method($entity)
            : $entity->{$column[static::COLUMN_CODE]};

        // Preprocessing
        $method = 'preprocess' . \XLite\Core\Converter::convertToCamelCase($column[static::COLUMN_CODE]);
        if (method_exists($this, $method)) {

            // $method assembled frm 'preprocess' + field name
            $value = $this->$method($value, $column, $entity);
        }

        return $value;
    }

    /**
     * Get field classes list (only inline-based form fields)
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFieldClasses()
    {
        $list = array();

        foreach ($this->getColumns() as $column) {
            if (
                isset($column[static::COLUMN_CLASS])
                && is_subclass_of($column[static::COLUMN_CLASS], 'XLite\View\FormField\Inline\AInline')
            ) {
                $list[] = $column[static::COLUMN_CLASS];
            }
        }

        return $list;
    }

    /**
     * Get create field classes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateFieldClasses()
    {
        $list = array();

        foreach ($this->getColumns() as $column) {
            if (
                isset($column[static::COLUMN_CREATE_CLASS])
                && is_subclass_of($column[static::COLUMN_CREATE_CLASS], 'XLite\View\FormField\Inline\AInline')
            ) {
                $list[] = $column[static::COLUMN_CREATE_CLASS];
            }
        }

        return $list;
    }

    /**
     * Get create line columns 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateColumns()
    {
        $columns = array();

        if ($this->getLeftActions()) {
            $columns[] = array(
                static::COLUMN_CODE     => 'actions left',
                static::COLUMN_NAME     => '',
                static::COLUMN_TEMPLATE => 'items_list/model/table/empty.tpl',
            );
        }

        foreach ($this->defineColumns() as $idx => $column) {
            if (
                (isset($column[static::COLUMN_CREATE_CLASS]) && $column[static::COLUMN_CREATE_CLASS])
                || (isset($column[static::COLUMN_CLASS]) && $column[static::COLUMN_CLASS])
            ) {
                $column[static::COLUMN_CODE] = $idx;
                $column[static::COLUMN_METHOD_SUFFIX] = \XLite\Core\Converter::convertToCamelCase($column[static::COLUMN_CODE]);
                if (!isset($column[static::COLUMN_CREATE_CLASS]) || !$column[static::COLUMN_CREATE_CLASS]) {
                    $column[static::COLUMN_CREATE_CLASS] = $column[static::COLUMN_CLASS];
                }
                $columns[] = $column;

            } else {
                $columns[] = array(
                    static::COLUMN_CODE => $idx,
                    static::COLUMN_TEMPLATE => 'items_list/model/table/empty.tpl',
                );
            }
        }

        if ($this->getRightActions()) {
            $columns[] = array(
                static::COLUMN_CODE     => 'actions right',
                static::COLUMN_NAME     => '',
                static::COLUMN_TEMPLATE => 'items_list/model/table/empty.tpl',
            );
        }

        return $columns;
    }

    /**
     * List has top creation box
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isTopInlineCreation()
    {
        return static::CREATE_INLINE_TOP === $this->isInlineCreation();
    }

    /**
     * List has bottom creation box
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isBottomInlineCreation()
    {
        return static::CREATE_INLINE_BOTTOM === $this->isInlineCreation();
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
        return 'XLite\View\Pager\Admin\Model\Table';
    }

    // {{{ Content helpers

    /**
     * Get container class
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return parent::getContainerClass() . ' items-list-table';
    }

    /**
     * Get head class 
     * 
     * @param array $column Column
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getHeadClass(array $column)
    {
        return $column[static::COLUMN_CODE];
    }

    /**
     * Get column cell class 
     * 
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model OPTIONAL
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getColumnClass(array $column, \XLite\Model\AEntity $entity = null)
    {
        return 'cell '
            . $column[static::COLUMN_CODE]
            . ($this->hasColumnAttantion($column, $entity) ? ' attention' : '');
    }

    /**
     * Check - has specified column attantion or not
     *
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasColumnAttantion(array $column, \XLite\Model\AEntity $entity)
    {
        return false;
    }

    // }}}

    // {{{ Top behaviors

    /**
     * Get top actions 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getTopActions()
    {
        $actions = array();

        if ($this->getCreateURL()) {
            $actions[] = 'items_list/model/table/parts/create.tpl';
        }

        return $actions;
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
        return null;
    }

    // }}}

    // {{{ Line bahaviors

    /**
     * Get left actions tempaltes
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLeftActions()
    {
        $list = array();

        if (static::SORT_TYPE_MOVE === $this->getSortableType()) {
            $list[] = 'items_list/model/table/parts/move.tpl';

        } elseif (static::SORT_TYPE_INPUT === $this->getSortableType()) {
            $list[] = 'items_list/model/table/parts/sort.tpl';
        }

        if ($this->isSwitchable()) {
            $list[] = 'items_list/model/table/parts/switcher.tpl';
        }

        if ($this->isSelectable()) {
            $list[] = 'items_list/model/table/parts/selector.tpl';
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
        $list = array();

        if ($this->isRemoved()) {
            $list[] = 'items_list/model/table/parts/remove.tpl';
        }

        return $list;
    }

    // }}}

    // {{{ Inherited methods

    /**
     * Check - table header is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isHeaderVisible()
    {
        return 0 < count($this->getTopActions());
    }

    /**
     * Return file name for body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getBodyTemplate()
    {
        return 'model/table.tpl';
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
        return parent::getPageBodyDir() . '/table';
    }

    // }}}

}

