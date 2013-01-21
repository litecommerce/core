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

namespace XLite\View\Model;

/**
 * Attribute view model
 *
 */
class Attribute extends \XLite\View\Model\AModel
{
    /**
     * Shema default
     *
     * @var array
     */
    protected $schemaDefault = array(
        'name' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Attribute',
            self::SCHEMA_REQUIRED => true,
        ),
        'attribute_group' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Select\AttributeGroups',
            self::SCHEMA_LABEL    => 'Attribute group',
            self::SCHEMA_REQUIRED => false,
        ),
        'type' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Select\AttributeTypes',
            self::SCHEMA_LABEL    => 'Type',
            self::SCHEMA_REQUIRED => false,
        ),
    );

    /**
     * Return current model ID
     *
     * @return integer
     */
    public function getModelId()
    {
        return \XLite\Core\Request::getInstance()->id;
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     */
    protected function getFormFieldsForSectionDefault()
    {
        if ($this->getModelObject()->getId()) {
            $this->schemaDefault['type'][self::SCHEMA_COMMENT] = 'Before editing attriubutes specific for the chosen type you should save the changes';
    
            if (
                $this->getModelObject()->getAttributeValuesCount()
                || $this->getModelObject()->getProductClass()->getProductsCount()
            ) {
                $this->schemaDefault['type'][self::SCHEMA_COMMENT] .= '<br /><br />Changing the type of a product attribute after having defined values for this attribute for some products will result in losing the defined attribute values';
            }

            if (
                \XLite\Model\Attribute::TYPE_NUMBER == $this->getModelObject()->getType()
            ) {
                $this->schemaDefault['decimals'] = array(
                    self::SCHEMA_CLASS    => 'XLite\View\FormField\Select\Decimals',
                    self::SCHEMA_LABEL    => 'Decimals',
                    self::SCHEMA_REQUIRED => false,
                    \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'edit-decimals custom-field',
                );
                $this->schemaDefault['unit'] = array(
                    self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
                    self::SCHEMA_LABEL    => 'Unit',
                    self::SCHEMA_REQUIRED => false,
                    self::SCHEMA_COMMENT  => '(suffix)',
                    \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'edit-unit custom-field',
                );
            }

            if (
                \XLite\Model\Attribute::TYPE_NUMBER == $this->getModelObject()->getType()
                || \XLite\Model\Attribute::TYPE_CHECKBOX == $this->getModelObject()->getType()
                || \XLite\Model\Attribute::TYPE_TEXT == $this->getModelObject()->getType()
            ) {
                $this->schemaDefault['default_value'] = array(
                    self::SCHEMA_CLASS    => $this->getModelObject()->getWidgetClass(),
                    self::SCHEMA_LABEL    => 'Default value',
                    self::SCHEMA_REQUIRED => false,
                    'rows'                => 1,
                    'maxHeight'           => 100,
                    \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'custom-field',
                );
            }

            if (
                \XLite\Model\Attribute::TYPE_SELECT == $this->getModelObject()->getType()
            ) {
                $this->schemaDefault['values'] = array(
                    self::SCHEMA_CLASS    => 'XLite\View\FormField\ItemsList',
                    self::SCHEMA_LABEL    => 'Allowed attribute values and default one',
                    self::SCHEMA_REQUIRED => false,
                    \XLite\View\FormField\ItemsList::PARAM_LIST_CLASS => 'XLite\View\ItemsList\Model\AttributeOption',
                    \XLite\View\FormField\AFormField::PARAM_WRAPPER_CLASS => 'custom-field',
                );
            }
        }

        return $this->getFieldsBySchema($this->schemaDefault);
    }

    /**
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     */
    protected function setModelProperties(array $data)
    {
        $data['attribute_group'] =  \XLite\Core\Database::getRepo('XLite\Model\AttributeGroup')->find($data['attribute_group']); 

        parent::setModelProperties($data);

        $this->getModelObject()->setProductClass($this->getProductClass());
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Model\Attribute
     */
    protected function getDefaultModelObject()
    {
        $model = $this->getModelId()
            ? \XLite\Core\Database::getRepo('XLite\Model\Attribute')->find($this->getModelId())
            : null;

        return $model ?: new \XLite\Model\Attribute;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\View\Form\Model\Attribute';
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();

        $label = $this->getModelObject()->getId() ? 'Save changes' : 'Next';

        $result['submit'] = new \XLite\View\Button\Submit(
            array(
                \XLite\View\Button\AButton::PARAM_LABEL => $label,
                \XLite\View\Button\AButton::PARAM_STYLE => 'action',
            )
        );

        return $result;
    }

    /**
     * Add top message
     *
     * @return void
     */
    protected function addDataSavedTopMessage()
    {
        if ('create' != $this->currentAction) {
            \XLite\Core\TopMessage::addInfo('The attribute has been updated');

        } else {
            \XLite\Core\TopMessage::addInfo('The attribute has been added');
        }
    }

}
