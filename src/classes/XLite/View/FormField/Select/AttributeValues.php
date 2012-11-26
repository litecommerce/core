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

namespace XLite\View\FormField\Select;

/**
 * Attribute groups selector
 *
 */
class AttributeValues extends \XLite\View\FormField\Select\Regular
{
    /**
     * Common params
     */
    const PARAM_ATTRIBUTE  = 'attribute';

    /**
     * Get attribute groups list
     *
     * @return array
     */
    protected function getAttributeValuesList()
    {
        $list = array();
        $cnd = new \XLite\Core\CommonCell;
        $cnd->attribute = $this->getParam(self::PARAM_ATTRIBUTE); 

        foreach (\XLite\Core\Database::getRepo('\XLite\Model\AttributeOption')->search($cnd) as $e) {
            $list[$e->getId()] = $e->getName();
        }

        return $list;
    }

    /**
     * Define widget params
     *
     * @return void
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            self::PARAM_ATTRIBUTE => new \XLite\Model\WidgetParam\Object(
                'Attribute', null, false, 'XLite\Model\Attribute'
            ),
        );
    }

    /**
     * Get options
     *
     * @return array
     */
    protected function getOptions()
    {
        return $this->getAttributeValuesList();
    }

    /**
     * Get default options
     *
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }
}
