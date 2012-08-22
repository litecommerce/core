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
 * Category widget
 *
 *
 * @ListChild (list="center", zone="customer")
 */
class Category extends \XLite\View\AView
{
    /**
     * Return list of targets allowed for this widget
     *
     * @return array
     */
    public static function getAllowedTargets()
    {
        $result = parent::getAllowedTargets();
        $result[] = 'category';
        $result[] = 'main';

        return $result;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'category_description.tpl';
    }

    /**
     * Check widget visibility
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible() && $this->getCategory()->getDescription();
    }

    /**
     * Return description with postprocessing WEB LC root constant
     *
     * @return string
     */
    protected function getDescription()
    {
        return $this->getCategory()->getViewDescription();
    }
}
