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
 * @since     1.0.19
 */

namespace XLite\View\ItemsList\Model\Currency;

/**
 * Admin list
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
class Admin extends \XLite\View\ItemsList\Model\Currency\ACurrency
{
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

        $list[] = 'currencies/style.css';

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
            'code' => array(
                static::COLUMN_NAME => \XLite\Core\Translation::lbl('Code'),
            ),
            'name' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Name'),
                static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Input\Text\Currency\Name',
            ),
            'symbol' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Symbol'),
                static::COLUMN_CLASS => 'XLite\View\FormField\Inline\Input\Text\Currency\Symbol',
            ),
            'e' => array(
                static::COLUMN_NAME  => \XLite\Core\Translation::lbl('Fractional part length'),
            ),
        );
    }

    /**
     * Get list name suffixes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getListNameSuffixes()
    {
        return array_merge(parent::getListNameSuffixes(), array('currency', 'admin'));
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
        return 'XLite\View\StickyPanel\Currency\Admin';
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Currency')->search($cnd, $countOnly);
    }

}

