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
 * @since     1.0.16
 */

namespace XLite\View\Button\Attribute\Base;

/**
 * Popup 
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class Popup extends \XLite\View\Button\Popup\Link
{
    /**
     * Widget param names
     */
    const PARAM_ATTRIBUTE = 'attribute';

    /**
     * Return URL parameters to use in AJAX popup
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getAdditionalURLParams()
    {
        $list = parent::getAdditionalURLParams();
        $list['attributeId'] = $this->getParam(static::PARAM_ATTRIBUTE)->getId();

        return $list;
    }

    /**
     * Define widgets parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ATTRIBUTE => new \XLite\Model\WidgetParam\Object(
                'Attribute', null, false, '\XLite\Model\Attribute\Type\Selector'
            ),
        );
    }
}
