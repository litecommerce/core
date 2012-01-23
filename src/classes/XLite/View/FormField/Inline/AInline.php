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
 * @since     1.0.15
 */

namespace XLite\View\FormField\Inline;

/**
 * Abstract inline form-field
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class AInline extends \XLite\View\AView
{
    const PARAM_ENTITY     = 'entity';
    const PARAM_ITEMS_LIST = 'itemsList';


    /**
     * Form field 
     * 
     * @var   \XLite\View\FormField\AFormField
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $field;

    /**
     * Short name 
     * 
     * @var   string
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $shortName;

    /**
     * Register CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'form_field/inline/style.css';

        return $list;
    }

    /**
     * Register JS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getJSFiles()
    {
        $list = parent::getJSFiles();

        $list[] = 'form_field/inline/controller.js';

        return $list;
    }

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
            static::PARAM_ENTITY     => new \XLite\Model\WidgetParam\Object('Entity', null, false, 'XLite\Model\AEntity'),
            static::PARAM_ITEMS_LIST => new \XLite\Model\WidgetParam\Object('Items list', null, false, 'XLite\View\ItemsList\Admin\AAdmin'),
        );
    }

    /**
     * Get entity
     *
     * @return \XLite\Model\AEntity
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntity()
    {
        return $this->getParam(static::PARAM_ENTITY);
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
        return 'form_field/inline.tpl';
    }

    /**
     * Check - field is editable or not
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isEditable()
    {
        return true;
    }

    /**
     * Check - field is editable or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function hasSeparateView()
    {
        return $this->getEntity() && $this->getEntity()->getUniqueIndetifier();
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
            && ($this->isEditable() || $this->hasSeparateView());
    }

    // {{{ Content helpers

    /**
     * Get container class 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return 'inline-field'
            . ($this->isEditable() ? ' editable' : '')
            . ($this->hasSeparateView() ? ' has-view' : '');
    }

    /**
     * Get view template 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getViewTemplate()
    {
        return 'form_field/inline/view.tpl';
    }

    /**
     * Get field template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFieldTemplate()
    {
        return 'form_field/inline/field.tpl';
    }

    /**
     * Get view value 
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getViewValue()
    {
        return $this->getField()->getValue();
    }

    // }}}

    // {{{ Form field

    /**
     * Save value
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function saveValue()
    {
        $method = 'set' . $this->shortName;

        // $method assembled from 'set' + field short name
        $this->getEntity()->$method($this->preprocessSavedValue($this->getField()->getValue()));
    }

    /**
     * Define form field
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function defineFieldClass();

    /**
     * Preprocess value forsave
     * 
     * @param mixed $value Value
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function preprocessSavedValue($value)
    {
        return $value;
    }

    /**
     * Get entity value for field
     * 
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntityValue()
    {
        $method = 'get' . $this->shortName;

        // $method assembled from 'get' + field short name
        return $this->getEntity()->$method();
    }

    /**
     * Get field label 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLabel()
    {
        return \XLite\Core\Translation::lbl(ucfirst($this->shortName));
    }

    /**
     * Get field 
     * 
     * @return \XLite\View\FormField\AFormField
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getField()
    {
        if (!isset($this->field)) {
            $this->field = $this->defineField();
        }

        return $this->field;
    }

    /**
     * Define field 
     * 
     * @return \XLite\View\FormField\Inline\AInline
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineField()
    {
        return $this->getWidget($this->getFieldParams(), $this->defineFieldClass());
    }

    /**
     * Get field name parts
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getNameParts()
    {
        return array(
            $this->getParam(static::PARAM_ITEMS_LIST)->getDataPrefix(),
            $this->getEntity()->getUniqueIndetifier() ?: 0,
            $this->shortName,
        );
    }

    /**
     * Get initial field parameters
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getFieldParams()
    {
        $parts = $this->getNameParts();

        return array(
            'fieldOnly' => true,
            'fieldName' => array_shift($parts) . ($parts ? ('[' . implode('][', $parts) . ']') : ''),
            'value'     => $this->getEntityValue(),
            'label'     => $this->getLabel(),
        );
    }

    /**
     * Set value 
     * 
     * @param mixed $value Value
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function setValue($value)
    {
        $this->getField()->setValue($value);
    }

    // }}}

    // {{{ Request data

    /**
     * Get field data from request
     * 
     * @param array $data Request data OPTIONAL
     *  
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getFieldDataFromRequest(array $data = array())
    {
        $data = $data ?: \XLite\Core\Request::GetInstance()->getData();
        $found = true;

        foreach ($this->getNameParts() as $part) {
            if (isset($data[$part])) {
                $data =& $data[$part];

            } else {
                $found = false;
                break;
            }
        }

        return $found ? $data : null;
    }

    // }}}

}
