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

namespace XLite\View\FormField\Select;

/**
 * Categories selector
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Categories extends \XLite\View\FormField\Select\Multiple
{
    /**
     * Parameters
     */
    const INDENT_STRING     = '-';
    const INDENT_MULTIPLIER = 3;

    /**
     * Return default options list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultOptions()
    {
        $list = array();

        foreach(\XLite\Core\Database::getRepo('\XLite\Model\Category')->getCategoriesPlainList() as $category) {
            $list[$category['category_id']] = $this->getIndentationString($category) . $this->getCategoryName($category);
        }

        return $list;
    }

    /**
     * Return indentation string for displaying category depth level
     *
     * @param array $category Category data
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getIndentationString(array $category)
    {
        return str_repeat(static::INDENT_STRING, $category['depth'] * static::INDENT_MULTIPLIER);
    }

    /**
     * Return translated category name
     *
     * @param array $category Category data
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.6
     */
    protected function getCategoryName(array $category)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($category['category_id'])->getName();
    }
}
