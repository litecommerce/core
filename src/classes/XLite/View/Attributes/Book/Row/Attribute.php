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
 * @since     1.0.14
 */

namespace XLite\View\Attributes\Book\Row;

/**
 * Attribute
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
class Attribute extends \XLite\View\NestedDraggableRows\Row\ARow
{
    /**
     * Widget param names
     */
    const PARAM_ATTRIBUTE = 'attribute';

    /**
     * Return row identifier
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getRowUniqueId()
    {
        $result = '_';
        $attr = $this->getAttribute();

        if (isset($attr) && $attr->getId()) {
            $result = $attr->getId();
        }

        return $result;
    }

    /**
     * Common prefix for editable elements in lists
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getPrefixPostedData()
    {
        return parent::getPrefixPostedData()
            . '[' . ($this->getGroupId() ?: '_') . ']'
            . '[' . \XLite\Controller\Admin\Attributes::FIELD_ATTRS . ']';
    }

    /**
     * Return CSS class for the row
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getRowCSSClass()
    {
        return strtolower($this->getAttributeTypeName()) . ' no-nest level-1 level-2';
    }

    /**
     * Return widget template path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getDefaultTemplate()
    {
        return 'attributes/book/row/attribute/body.tpl';
    }

    /**
     * Return name of the "position" field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getRowPosFieldName()
    {
        return 'pos';
    }

    /**
     * Return value of the "position" field
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getRowPosFieldValue()
    {
        return ($attr = $this->getAttribute()) ? $attr->getPos() : 0;
    }

    /**
     * Define widget params
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_ATTRIBUTE => new \XLite\Model\WidgetParam\Object(
                'Attribute object', null, false, '\XLite\Model\Attribute'
            ),
        );
    }

    /**
     * Alias
     *
     * @return \XLite\Model\Attribute
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getAttribute()
    {
        return $this->getParam(static::PARAM_ATTRIBUTE);
    }

    /**
     * Return group ID attribute is assigned to
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getGroupId()
    {
        $result = null;
        $attr = $this->getAttribute();

        if (isset($attr)) {
            $group = $attr->getGroup();

            if (isset($group)) {
                $result = $group->getId();
            }
        }

        return $result;
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getAttributeTitle()
    {
        return ($attr = $this->getAttribute()) ? $attr->getTitle() : null;
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getAttributeTypeName()
    {
        return ($attr = $this->getAttribute()) ? $attr->getTypeName() : null;
    }

    /**
     * Alias
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function getAttributeID()
    {
        return ($attr = $this->getAttribute()) ? $attr->getName() : null;
    }

    /**
     * Get specific param for the "Number" attributes
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributeDecimals()
    {
        $result = 0;

        if ('Number' === $this->getAttributeTypeName()) {
            $attr = $this->getAttribute();

            if (isset($attr)) {
                $result = $attr->getDecimals();
            }
        }

        return $result;
    }

    /**
     * Get specific param for the "Number" attributes
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributeUnit()
    {
        $result = null;

        if ('Number' === $this->getAttributeTypeName()) {
            $attr = $this->getAttribute();

            if (isset($attr)) {
                $result = $attr->getUnit();
            }
        }

        return $result;
    }

    /**
     * Get specific param for the "Selector" attributes
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributeChoices()
    {
        $result = array();

        if ('Selector' === $this->getAttributeTypeName()) {
            $attr = $this->getAttribute();

            if (isset($attr)) {
                $result = $attr->getChoices();
            }
        }

        return $result;
    }

    /**
     * Get specific param for some attribute types
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributeDefaultValue()
    {
        $result = null;
        $attr = $this->getAttribute();

        if (isset($attr)) {
            $result = $attr->getDefaultValue();
        }

        return $result;
    }

    /**
     * Return number of products assigned to current attribute
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAssignedProductsCount()
    {
        return ($attr = $this->getAttribute()) ? $attr->getAssignedProductsCount() : 0;
    }

    /**
     * Return list of allowed attribute types
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAttributeTypes()
    {
        return array(
            'Text'     => 'Text field',
            'Number'   => 'Number',
            'Selector' => 'Selector',
            'Boolean'  => 'Checkbox',
        );
    }

    /**
     * Get list of "Decimal" param values for the "Number" attributes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getDecimalRange()
    {
        return range(0, 4);
    }

    /**
     * Get the "products assigned..." label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getAssignedProductsInfoLabel()
    {
        return static::t('{{X}} products in total', array('X' => $this->getAssignedProductsCount()));
    }
}
