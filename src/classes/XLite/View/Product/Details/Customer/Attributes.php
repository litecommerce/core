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

namespace XLite\View\Product\Details\Customer;

/**
 * Product attributes 
 *
 */
class Attributes extends \XLite\View\Product\Details\AAttributes
{
    /**
     * Attributes 
     *
     * @var array
     */
    protected $attributes;

    /**
     * Register CSS files
     *
     * @return array
     */
    public function getAttrList()
    {
        if (is_null($this->attributes)) {
            $this->attributes = array();
            foreach ($this->getAttributesList() as $a) {
                $value = $a->getAttributeValue($this->getProduct(), true);
                if ($value) {
                    $this->attributes[] = array(
                        'name'  => $a->getName(),
                        'value' => htmlspecialchars($value),
                        'class' => $this->getFieldClass($a, $value)
                    );
                }
            }
        }

        return $this->attributes;
    }

    /**
     * Return field class 
     *
     * @param \XLite\Model\Attribute $attribute Attribute
     * @param string                 $value     Value
     *
     * @return string
     */
    protected function getFieldClass(\XLite\Model\Attribute $attribute, $value)
    {
        $class = str_replace(' ', '-', strtolower($attribute->getTypes($attribute->getType())));
        if (\XLite\Model\Attribute::TYPE_CHECKBOX == $attribute->getType()) {
            $class .= ' ' . (static::t('yes') == $value ? 'checked' : 'no-checked');
        }

        return $class;
    }

    /**
     * Return widget default template
     *
     * @return string
     */
    protected function getDefaultTemplate()
    {
        return 'product/details/parts/attribute.tpl';
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getAttrList();
    }
}
