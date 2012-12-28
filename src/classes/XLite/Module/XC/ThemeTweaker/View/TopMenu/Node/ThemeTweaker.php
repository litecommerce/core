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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\XC\ThemeTweaker\View\TopMenu\Node;

/**
 * Theme tweaker
 *
 * @ListChild (list="menu.settings", zone="admin", weight="500")
 */
class ThemeTweaker extends \XLite\View\TopMenu\Node
{
    /**
     * Check ACL permissions
     *
     * @return boolean
     */
    protected function checkACL()
    {
        return parent::checkACL()
            || \XLite\Core\Auth::getInstance()->isPermissionAllowed('manage theme tweaker');
    }

    /**
     * Define widget parameters
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[static::PARAM_TITLE]->setValue(static::t('Custom CSS & JS'));
        $this->widgetParams[static::PARAM_TARGET]->setValue('custom_css');
    }
}
