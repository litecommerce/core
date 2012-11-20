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

namespace XLite\View\FormField\Input\Text;

/**
 * Atutribute option 
 *
 */
class AttributeOption extends \XLite\View\FormField\Input\Text\Base\Combobox
{
    /**
     * Common params
     */
    const PARAM_ATTRIBUTE  = 'attribute';

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
     * Get URL
     *
     * @return string
     */
    protected function getURL()
    {
        return parent::getURL() . '&id=' 
            . $this->getParam(self::PARAM_ATTRIBUTE)->getId();
    }

    /**
     * Set value
     *
     * @param mixed $value Value to set
     *
     * @return void
     */
    public function setValue($value)
    {
        if ($value && is_object($value) && $value instanceOf \XLite\Model\AttributeOption) {
            $value = $value->getName();
        }

        parent::setValue($value);
    }

    /**
     * Get dictionary name
     *
     * @return string
     */
    protected function getDictionary()
    {
        return 'attributeOption';
    }
}
