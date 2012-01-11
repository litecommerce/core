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

namespace XLite\View\Button;

/**
 * Delete category popup button
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class DeleteCategory extends \XLite\View\Button\APopupButton
{
    /**
     * Widget param names
     */
    const PARAM_CATEGORY_ID          = 'categoryId';
    const PARAM_REMOVE_SUBCATEGORIES = 'removeSubcategories';

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();
        $list[] = 'button/js/delete_category.js';

        return $list;
    }

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareURLParams()
    {
        return array(
            'target'      => 'categories',
            'pre_action'  => 'delete',
            'widget'      => '\XLite\View\DeleteCategory',
            'category_id' => $this->getParam(self::PARAM_CATEGORY_ID),
            'subcats'     => (bool) $this->getParam(self::PARAM_REMOVE_SUBCATEGORIES),
        );
    }

    /**
     * Return default button label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultLabel()
    {
        return static::t('Delete');
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();
        
        $this->widgetParams += array(
            self::PARAM_CATEGORY_ID          => new \XLite\Model\WidgetParam\Int('Category ID', 1),
            self::PARAM_REMOVE_SUBCATEGORIES => new \XLite\Model\WidgetParam\Bool('Remove subcategories', false),
        );
    }

    /**
     * Return CSS classes
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getClass()
    {
        return 'delete-category-button ' . ($this->getParam(self::PARAM_STYLE) ?: '');
    }

    /**
     * Return template path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'button/delete_category.tpl';
    }
}
