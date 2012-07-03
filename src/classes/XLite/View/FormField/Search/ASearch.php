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
 * @since     1.0.24
 */

namespace XLite\View\FormField\Search;

/**
 * Abstract items lsit search cell
 * 
 * @see   ____class_see____
 * @since 1.0.24
 */
abstract class ASearch extends \XLite\View\AView
{
    /**
     * Widget parameters 
     */
    const PARAM_COLUMN = 'column';

    /**
     * Fields attributes 
     */
    const FIELD_NAME  = 'name';
    const FIELD_CLASS = 'class';
    const FIELD_TITLE = 'title';

    /**
     * Fields 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.24
     */
    protected $fields;

    /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams += array(
            static::PARAM_COLUMN => new \XLite\Model\WidgetParam\Collection('Column', array()),
        );
    }

    /**
     * Check if widget is visible
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isVisible()
    {
        return parent::isVisible()
            && $this->getColumn();
    }

    /**
     * Get column 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getColumn()
    {
        return $this->getParam(static::PARAM_COLUMN);
    }

    /**
     * Return widget default template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultTemplate()
    {
        return 'form_field/search/field.tpl';
    }

    // {{{ Fields and conditions

    /**
     * Define fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    abstract protected function defineFields();

    /**
     * Get fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getFields()
    {
        if (!isset($this->fields)) {
            $this->fields = array();
            foreach ($this->defineFields() as $field) {
                if (!empty($field[static::FIELD_CLASS])) {
                    $this->fields[] = array(
                        'field'  => $field,
                        'widget' => $this->getWidget($this->assembleFieldParameters($field), $field[static::FIELD_CLASS]),
                    );
                }
            }
        }

        return $this->fields;
    }

    /**
     * Get field by name
     * 
     * @param string $name Name
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function displayField($name)
    {
        $field = null;

        foreach ($this->getFields() as $f) {
            if ($name == $f['field'][static::FIELD_NAME]) {
                $field = $f;
                break;
            }
        }

        if ($field) {
            $field['widget']->display();
        }
    }

    /**
     * Assemble field parameters 
     * 
     * @param array $field Field info
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function assembleFieldParameters(array $field)
    {
        $parameters = array(
            'fieldName'  => $field[static::FIELD_NAME],
            'attributes' => array('class' => $this->assembleFieldClass($field)),
            'value'      => $this->getCondition($field[static::FIELD_NAME]),
            'fieldOnly'  => true,
            'fieldId'    => 'search-field-' . $field[static::FIELD_NAME],
        );

        if (!empty($field[static::FIELD_TITLE])) {
            $parameters['label'] = $field[static::FIELD_TITLE];
        }

        return $parameters;
    }

    /**
     * Assemble field class
     *
     * @param array $field Field info
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function assembleFieldClass(array $field)
    {
        $name = preg_replace('/[^a-z0-9]/iSs', '-', $field[static::FIELD_NAME]);
        $name = str_replace('--', '-', $name);

        return 'search-field ' . $name . ' not-significant';
    }

    /**
     * Get condition 
     * 
     * @param string $name Condition name
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function getCondition($name)
    {
        return parent::getCondition($name);
    }

    // }}}
}
