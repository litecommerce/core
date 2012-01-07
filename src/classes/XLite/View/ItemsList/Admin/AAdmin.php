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
 * Abstract admin model-based items list
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class AAdmin extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Sortable types 
     */
    const SORT_TYPE_NONE  = 0;
    const SORT_TYPE_MOVE  = 1;
    const SORT_TYPE_INPUT = 2;


    /**
     * Hightlight step 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $hightlightStep = 2;

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

        $list[] = $this->getDir() . '/model/style.css';

        return $list;
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
        return 'model';
    }

    /**
     * Get line class
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLineClass($index, \XLite\Model\AEntity $entity)
    {
        return implode(' ', $this->defineLineClass($index, $entity));
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
        $classes = array();

        if (0 === $index) {
            $classes[] = 'first';
        }

        if ($this->getItemsCount() == $index + 1) {
            $classes[] = 'last';
        }

        if (0 === ($index + 1) % $this->hightlightStep) {
            $classes[] = 'even';
        }

        return $classes;
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
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
        return 'XLite\View\Pager\Admin\Model';
    }

    /**
     * Return internal list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        $parts = explode('\\', get_called_class());

        $names = array();
        if ('Module' === $parts[1]) {
            $names[] = strtolower($parts[2]);
            $names[] = strtolower($parts[3]);
        }

        $names[] = strtolower($parts[count($parts) - 1]);

        return implode('.', $names) . '.' . parent::getListName();
    }

    /**
     * Build entity page URL 
     * 
     * @param \XLite\Model\AEntity $entity Entity
     * @param array                $column Column data
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function buildEntityURL(\XLite\Model\AEntity $entity, array $column)
    {
        return \XLite\Core\Converter::buildURL($column[static::COLUMN_LINK], '', array('id' => $entity->getUniqueIndetifier()));
    }

    // {{{ Line behaviors

    /**
     * Mark list as sortable 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getSortableType()
    {
        return static::SORT_TYPE_NONE;
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
        return false;
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
        return false;
    }

    /**
     * Mark list as selectable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isSelectable()
    {
        return false;
    }

    /**
     * Get entity activity
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntityActivity(\XLite\Model\AEntity $entity)
    {
        return $entity->getEnabled();
    }

    /**
     * Get entity position 
     * 
     * @param \XLite\Model\AEntity $entity Entity
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntityPosition(\XLite\Model\AEntity $entity)
    {
        return $entity->getOrder();
    }

    // }}}
}

