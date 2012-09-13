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
 * Abstract admin model-based items list (table)
 *
 */
abstract class Table extends \XLite\View\ItemsList\Model\AModel
{
    const COLUMN_NAME          = 'name';
    const COLUMN_TEMPLATE      = 'template';
    const COLUMN_CLASS         = 'class';
    const COLUMN_CODE          = 'code';
    const COLUMN_LINK          = 'link';
    const COLUMN_METHOD_SUFFIX = 'methodSuffix';
    const COLUMN_CREATE_CLASS  = 'createClass';
    const COLUMN_MAIN          = 'main';
    const COLUMN_SERVICE       = 'service';
    const COLUMN_PARAMS        = 'params';
    const COLUMN_SORT          = 'sort';
    const COLUMN_SEARCH_WIDGET = 'searchWidget';
    const COLUMN_NO_WRAP       = 'noWrap';

    /**
     * Columns (local cache)
     *
     * @var array
     */
    protected $columns;

    /**
     * Main column index
     *
     * @var integer
     */
    protected $mainColumn;

    /**
     * Define columns structure
     *
     * @return array
     */
    abstract protected function defineColumns();

    /**
     * Get a list of CSS files
     *
     * @return array
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
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = $this->getDir() . '/' . $this->getPageBodyDir() . '/controller.js';

        return $list;
    }

    /**
     * Check - pager box is visible or not
     *
     * @return boolean
     */
    protected function isPagerVisible()
    {
        return parent::isPagerVisible()
            && $this->getPager()->isVisible();
    }

    /**
     * Get preprocessed columns structire
     *
     * @return array
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
                    static::COLUMN_SERVICE  => true,
                );
            }

            foreach ($this->defineColumns() as $idx => $column) {
                $column[static::COLUMN_CODE] = $idx;
                $column[static::COLUMN_METHOD_SUFFIX] = \XLite\Core\Converter::convertToCamelCase($column[static::COLUMN_CODE]);
                if (!isset($column[static::COLUMN_TEMPLATE]) && !isset($column[static::COLUMN_CLASS])) {
                    $column[static::COLUMN_TEMPLATE] = 'items_list/model/table/field.tpl';
                }
                $column[static::COLUMN_PARAMS] = isset($column[static::COLUMN_PARAMS]) ? $column[static::COLUMN_PARAMS] : array();
                $this->columns[] = $column;
            }

            if ($this->getRightActions()) {
                $this->columns[] = array(
                    static::COLUMN_CODE     => 'actions right',
                    static::COLUMN_NAME     => '',
                    static::COLUMN_TEMPLATE => 'items_list/model/table/right_actions.tpl',
                    static::COLUMN_SERVICE  => true,
                );
            }
        }

        return $this->columns;
    }

    /**
     * Returnd columns count
     * 
     * @return integer
     */
    protected function getColumnsCount()
    {
        return count($this->getColumns());
    }

    /**
     * Check - table header is visible or not
     * 
     * @return boolean
     */
    protected function isTableHeaderVisible()
    {
        $result = false;
        foreach ($this->getColumns() as $column) {
            if (!empty($column['name'])) {
                $result = true;
                break;
            }
        }

        return $result;
    }

    /**
     * Get main column
     *
     * @return array
     */
    protected function getMainColumn()
    {
        if (!isset($this->mainColumn)) {
            $result = null;
            $first = null;

            foreach ($this->getColumns() as $i => $column) {
                if (!isset($column[static::COLUMN_SERVICE]) || !$column[static::COLUMN_SERVICE]) {
                    if (!isset($first)) {
                        $first = $i;
                    }
                    if (isset($column[static::COLUMN_MAIN]) && $column[static::COLUMN_MAIN]) {
                        $result = $i;
                        break;
                    }
                }
            }

            $this->mainColumn = isset($result) ? $result : $first;
        }

        $columns = $this->getColumns();

        return isset($columns[$this->mainColumn]) ? $columns[$this->mainColumn] : null;
    }

    /**
     * Check - specified column is main or not
     *
     * @param array $column Column
     *
     * @return void
     */
    protected function isMainColumn(array $column)
    {
        $main = $this->getMainColumn();

        return $main && $column[static::COLUMN_CODE] == $main[static::COLUMN_CODE];
    }

    /**
     * Get column value
     *
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model
     *
     * @return mixed
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
     * Get field objects list (only inline-based form fields)
     *
     * @return array
     */
    protected function getFieldObjects()
    {
        $list = array();

        foreach ($this->getColumns() as $column) {
            $name = $column[static::COLUMN_CODE];
            if (
                isset($column[static::COLUMN_CLASS])
                && is_subclass_of($column[static::COLUMN_CLASS], 'XLite\View\FormField\Inline\AInline')
            ) {
                $params = isset($column[static::COLUMN_PARAMS]) ? $column[static::COLUMN_PARAMS] : array();
                $list[] = array(
                    'class'      => $column[static::COLUMN_CLASS],
                    'parameters' => array('fieldName' => $name, 'fieldParams' => $params),
                );
            }
        }

        if ($this->isSwitchable()) {
            $cell = $this->getSwitcherField();
            $list[] = array(
                'class'      => $cell['class'],
                'parameters' => array('fieldName' => $cell['name'], 'fieldParams' => $cell['params']),
            );
        }

        if (static::SORT_TYPE_NONE != $this->getSortableType()) {
            $cell = $this->getSortField();
            $list[] = array(
                'class'      => $cell['class'],
                'parameters' => array('fieldName' => $cell['name'], 'fieldParams' => $cell['params']),
            );
        }

        foreach ($list as $i => $class) {
            $list[$i] = new $class['class']($class['parameters']);
        }

        return $list;
    }

    /**
     * Get switcher field 
     * 
     * @return array
     */
    protected function getSwitcherField()
    {
        return array(
            'class'  => 'XLite\View\FormField\Inline\Input\Checkbox\Switcher\Enabled',
            'name'   => 'enabled',
            'params' => array(),
        );
    }

    /**
     * Get sort field 
     * 
     * @return array
     */
    protected function getSortField()
    {
        return static::SORT_TYPE_INPUT == $this->getSortableType()
            ? array(
                'class'  => 'XLite\View\FormField\Inline\Input\Text\Position\OrderBy',
                'name'   => 'position',
                'params' => array(),
            )
            :
            array(
                'class'  => 'XLite\View\FormField\Inline\Input\Text\Position\Move',
                'name'   => 'position',
                'params' => array(),
            );
    }

    /**
     * Get create field classes
     *
     * @return void
     */
    protected function getCreateFieldClasses()
    {
        $list = array();

        foreach ($this->getColumns() as $column) {
            $name = $column[static::COLUMN_CODE];
            $class = null;
            if (
                isset($column[static::COLUMN_CREATE_CLASS])
                && is_subclass_of($column[static::COLUMN_CREATE_CLASS], 'XLite\View\FormField\Inline\AInline')
            ) {
                $class = $column[static::COLUMN_CREATE_CLASS];

            } elseif (
                isset($column[static::COLUMN_CLASS])
                && is_subclass_of($column[static::COLUMN_CLASS], 'XLite\View\FormField\Inline\AInline')
            ) {
                $class = $column[static::COLUMN_CLASS];
            }

            if ($class) {
                $params = isset($column[static::COLUMN_PARAMS]) ? $column[static::COLUMN_PARAMS] : array();
                $list[] = array(
                    'class'      => $class,
                    'parameters' => array('fieldName' => $name, 'fieldParams' => $params),
                );
            }
        }

        foreach ($list as $i => $class) {
            $list[$i] = new $class['class']($class['parameters']);
        }

        return $list;
    }

    /**
     * Get create line columns
     *
     * @return array
     */
    protected function getCreateColumns()
    {
        $columns = array();

        if ($this->getLeftActions()) {
            $columns[] = array(
                static::COLUMN_CODE     => 'actions left',
                static::COLUMN_NAME     => '',
                static::COLUMN_SERVICE  => true,
                static::COLUMN_TEMPLATE => 'items_list/model/table/parts/empty_left.tpl',
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
                static::COLUMN_SERVICE  => true,
                static::COLUMN_TEMPLATE => $this->isRemoved()
                    ? 'items_list/model/table/parts/remove_create.tpl'
                    : 'items_list/model/table/parts/empty_right.tpl',
            );
        }

        return $columns;
    }

    /**
     * List has top creation box
     *
     * @return boolean
     */
    protected function isTopInlineCreation()
    {
        return static::CREATE_INLINE_TOP === $this->isInlineCreation();
    }

    /**
     * List has bottom creation box
     *
     * @return boolean
     */
    protected function isBottomInlineCreation()
    {
        return static::CREATE_INLINE_BOTTOM === $this->isInlineCreation();
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     */
    protected function getPagerClass()
    {
        return 'XLite\View\Pager\Admin\Model\Table';
    }

    /**
     * Get cell list name part
     *
     * @param string $type   Cell type
     * @param array  $column Column
     *
     * @return string
     */
    protected function getCellListNamePart($type, array $column)
    {
        return $type . '.' . str_replace(' ', '.', $column[static::COLUMN_CODE]);
    }

    // {{{ Content helpers

    /**
     * Get container class
     *
     * @return string
     */
    protected function getContainerClass()
    {
        $class = parent::getContainerClass()
            . ' items-list-table'
            . ($this->isTableHeaderVisible() ? ' no-thead' : '');

        return trim($class);
    }

    /**
     * Get head class
     *
     * @param array $column Column
     *
     * @return string
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
     */
    protected function getColumnClass(array $column, \XLite\Model\AEntity $entity = null)
    {
        return 'cell '
            . $column[static::COLUMN_CODE]
            . ($this->hasColumnAttention($column, $entity) ? ' attention' : '')
            . ($this->isMainColumn($column) ? ' main' : '')
            . (empty($column[static::COLUMN_NO_WRAP]) ? '' : ' no-wrap');
    }

    /**
     * Check - has specified column attention or not
     *
     * @param array                $column Column
     * @param \XLite\Model\AEntity $entity Model OPTIONAL
     *
     * @return boolean
     */
    protected function hasColumnAttention(array $column, \XLite\Model\AEntity $entity = null)
    {
        return false;
    }

    /**
     * Get action cell class
     *
     * @param integer $i        Cell index
     * @param string  $template Template
     *
     * @return string
     */
    protected function getActionCellClass($i, $template)
    {
        return 'action' . (0 < $i ? ' next' : '');
    }

    // }}}

    // {{{ Top / bottom behaviors

    /**
     * Get top actions
     *
     * @return array
     */
    protected function getTopActions()
    {
        $actions = array();

        if (static::CREATE_INLINE_TOP == $this->isCreation() && $this->getCreateURL()) {
            $actions[] = 'items_list/model/table/parts/create.tpl';

        } elseif (static::CREATE_INLINE_TOP == $this->isInlineCreation()) {
            $actions[] = 'items_list/model/table/parts/create_inline.tpl';
        }

        return $actions;
    }

    /**
     * Get bottom actions
     *
     * @return array
     */
    protected function getBottomActions()
    {
        $actions = array();

        if (static::CREATE_INLINE_BOTTOM == $this->isCreation() && $this->getCreateURL()) {
            $actions[] = 'items_list/model/table/parts/create.tpl';

        } elseif (static::CREATE_INLINE_BOTTOM == $this->isInlineCreation()) {
            $actions[] = 'items_list/model/table/parts/create_inline.tpl';
        }

        return $actions;
    }

    // }}}

    // {{{ Line bahaviors

    /**
     * Get left actions tempaltes
     *
     * @return array
     */
    protected function getLeftActions()
    {
        $list = array();

        if (static::SORT_TYPE_MOVE === $this->getSortableType()) {
            $list[] = 'items_list/model/table/parts/move.tpl';

        } elseif (static::SORT_TYPE_INPUT === $this->getSortableType()) {
            $list[] = 'items_list/model/table/parts/position.tpl';
        }

        if ($this->isSelectable()) {
            $list[] = 'items_list/model/table/parts/selector.tpl';
        }

        if ($this->isSwitchable()) {
            $list[] = 'items_list/model/table/parts/switcher.tpl';
        }

        return $list;
    }

    /**
     * Get right actions tempaltes
     *
     * @return array
     */
    protected function getRightActions()
    {
        $list = array();

        if ($this->isRemoved()) {
            $list[] = 'items_list/model/table/parts/remove.tpl';
        }

        return $list;
    }

    /**
     * Check - remove entity or not
     * 
     * @param \XLite\Model\AEntity $entity Entity
     *  
     * @return boolean
     */
    protected function isAllowEntityRemove(\XLite\Model\AEntity $entity)
    {
        return true;
    }

    // }}}

    // {{{ Inherited methods

    /**
     * Check - body tempalte is visible or not
     *
     * @return boolean
     */
    protected function isPageBodyVisible()
    {
        return parent::isPageBodyVisible() || $this->isHeadSearchVisible();
    }

    /**
     * Check - table header is visible or not
     *
     * @return boolean
     */
    protected function isHeaderVisible()
    {
        return 0 < count($this->getTopActions());
    }

    /**
     * isFooterVisible
     *
     * @return boolean
     */
    protected function isFooterVisible()
    {
        return 0 < count($this->getBottomActions());
    }

    /**
     * Return file name for body template
     *
     * @return string
     */
    protected function getBodyTemplate()
    {
        return 'model/table.tpl';
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     */
    protected function getPageBodyDir()
    {
        return parent::getPageBodyDir() . '/table';
    }

    /**
     * Remove entity
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return boolean
     */
    protected function removeEntity(\XLite\Model\AEntity $entity)
    {
        return $this->isAllowEntityRemove($entity) && parent::removeEntity($entity);
    }

    // }}}

    // {{{ Head sort

    /**
     * Check - specified column is sorted or not
     * 
     * @param array $column COlumn
     *  
     * @return boolean
     */
    protected function isColumnSorted(array $column)
    {
        $field = $this->getSortBy();

        return !empty($column[static::COLUMN_SORT]) && $field == $column[static::COLUMN_SORT];
    }

    /**
     * Get next sort direction
     * 
     * @param array $column Column
     *  
     * @return string
     */
    protected function getSortDirectionNext(array $column)
    {
        if ($this->isColumnSorted($column)) {
            $direction = static::SORT_ORDER_DESC == $this->getSortOrder() ? static::SORT_ORDER_ASC : static::SORT_ORDER_DESC;

        } else {
            $direction = $this->getSortOrder() ?: static::SORT_ORDER_DESC;
        }

        return $direction;
    }

    /**
     * Get sort link class 
     * 
     * @param array $column Column
     *  
     * @return string
     */
    protected function getSortLinkClass(array $column)
    {
        $classes = 'sort';
        if ($this->isColumnSorted($column)) {
            $classes .= ' current-sort ' . $this->getSortOrder() . '-direction';
        }

        return $classes;
    }

    // }}}

    // {{{ Head search

    /**
     * Check - search-in-head mechanism is available or not
     * 
     * @return boolean
     */
    protected function isHeadSearchVisible()
    {
        $found = false;

        foreach ($this->getColumns() as $column) {
            if ($this->isSearchColumn($column)) {
                $found = true;
                break;
            }
        }

        return $found;
    }

    /**
     * Check - specified column has search widget or not
     * 
     * @param array $column Column info
     *  
     * @return boolean
     */
    protected function isSearchColumn(array $column)
    {
        return !empty($column[static::COLUMN_SEARCH_WIDGET]);
    }


    /**
     * Get search cell class 
     * 
     * @param array $column ____param_comment____
     *  
     * @return void
     */
    protected function getSearchCellClass(array $column)
    {
        return 'search-cell ' . $column[static::COLUMN_CODE] . ' '
            . ($this->isSearchColumn($column) ? 'filled' : 'empty');
    }

    // }}}
}

