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

namespace XLite\View;

/**
 * Attributes page view
 *
 *
 * @ListChild (list="admin.center", zone="admin")
 */
class Attributes extends \XLite\View\AView
{
    /**
     * Return list of allowed targets
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        return array_merge(parent::getAllowedTargets(), array('attributes'));
    }

    /**
     * Get a list of CSS files required to display the widget properly
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'attributes/style.css';
        $list[] = 'form_field/inline/style.css';
        $list[] = 'form_field/inline/input/text/position/move.css';
        $list[] = 'form_field/inline/input/text/position.css';
        $list[] = 'form_field/form_field.css';
        $list[] = 'form_field/input/text/position.css';
        $list[] = 'items_list/items_list.css';
        $list[] = 'items_list/model/style.css';
        $list[] = 'items_list/model/table/style.css';
        $list[] = 'form_field/inline/input/text.css';

        return $list;
    }

    /**
     * Get a list of JavaScript files required to display the widget properly
     *
     * @return array
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'form_field/inline/controller.js';
        $list[] = 'form_field/inline/input/text/position/move.js';
        $list[] = 'form_field/js/text.js';
        $list[] = 'form_field/input/text/integer.js';
        $list[] = 'button/js/remove.js';
        $list[] = 'items_list/items_list.js';
        $list[] = 'items_list/model/table/controller.js';
        $list[] = 'attributes/script.js';
        $list[] = 'form_field/inline/input/text.js';

        return $list;
    }

    /**
     * Register files from common repository
     *
     * @return array
     */
    public function getCommonFiles()
    {
        $list = parent::getCommonFiles();
        $list['js'][] = 'js/jquery.blockUI.js';
        $list['js'][] = 'js/jquery.textarea-expander.js';

        return $list;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'attributes/body.tpl';
    }

    /**
     * Check - search box is visible or not
     * 
     * @return boolean
     */
    protected function isSearchVisible()
    {
        return 0 < \XLite\Core\Database::getRepo('XLite\Model\Attribute')->count();
    }

    /**
     * Check - list box is visible or not
     * 
     * @return boolean
     */
    protected function isListVisible()
    {
        return $this->getProductClass()->getAttributesCount()
            || $this->getProductClass()->getAttributeGroups()->count();
    }

}
