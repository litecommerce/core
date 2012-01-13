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

namespace XLite\View\ItemsList\Admin;

/**
 * Abstract admin model-based items list
 * 
 * @see   ____class_see____
 * @since 1.0.15
 */
abstract class AAdmin extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Sortable types 
     */
    const SORT_TYPE_NONE  = 0;
    const SORT_TYPE_MOVE  = 1;
    const SORT_TYPE_INPUT = 2;


    /**
     * Hightlight step 
     * 
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $hightlightStep = 2;

    /**
     * Error messages 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $errorMessages = array();

    /**
     * Request data 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $requestData;

    /**
     * Inline fields 
     * 
     * @var   array
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $inlineFields;

    // {{{ Model processed

    /**
     * Get field classes list (only inline-based form fields)
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function getFieldClasses();

    /**
     * Process
     * 
     * @return boolean}integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function process()
    {
        $result = true;

        if ($this->isActiveModelProcessing()) {
            $result = $this->validate();

            if ($result) {
                $result = $this->save();

            } else {
                $this->processErrors();
            }
        }

        return $result;
    }

    /**
     * Check - moel processing is active or not
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isActiveModelProcessing()
    {
        return $this->hasResults() && $this->getFieldClasses();
    }

    /**
     * Validate data
     * 
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function validate()
    {
        $validated = true;

        foreach ($this->getInlineFields() as $field) {
            $validated = $this->validateCell($field) && $validated;
        }

        return $validated;
    }

    /**
     * Save data
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function save()
    {
        $count = 0;

        foreach ($this->getInlineFields() as $field) {
            $count++;
            $this->saveCell($field);
        }

        return $count;
    }

    /**
     * Process errors 
     * 
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function processErrors()
    {
        \XLite\Core\TopMessage::getInstance()->addBatch($this->getErrorMessages(), \XLite\Core\TopMessage::ERROR);

        // Run controller's method
        $this->setActionError();
    }

    /**
     * Validate inline field
     *
     * @param \XLite\View\FormField\Inline\AInline $inline Inline field
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function validateCell(\XLite\View\FormField\Inline\AInline $inline)
    {
        $inline->getField()->setValue($inline->getFieldDataFromRequest($this->getRequestData()));
        list($flag, $message) = $inline->getField()->validate();
        if (!$flag) {
            $this->addErrorMessage($inline, $message);
        }

        return $flag;
    }

    /**
     * Save cell 
     * 
     * @param \XLite\View\FormField\Inline\AInline $inline Inline field
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function saveCell(\XLite\View\FormField\Inline\AInline $inline)
    {
        $inline->saveValue();
    }

    /**
     * Get inline fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getInlineFields()
    {
        if (!isset($this->inlineFields)) {
            $this->inlineFields = $thid->defineInlineFields();
        }

        return $this->inlineFields;
    }

    /**
     * Define inline fields 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineInlineFields()
    {
        $list = array();

        foreach ($this->getPageData() as $entity) {
            foreach ($this->getFieldClasses() as $class) {
                $list[] = $this->getInlineField($class, $entity);
            }
        }

        return $list;
    }

    /**
     * Get inline field 
     * 
     * @param string               $class  Class
     * @param \XLite\Model\AEntity $entity Entity
     *  
     * @return \XLite\View\FormField\Inline\AInline
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getInlineField($class, \XLite\Model\AEntity $entity)
    {
        return new $class(array('entity' => $entity));
    }

    /**
     * Get request data 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getRequestData()
    {
        if (!isset($this->requestData)) {
            $this->requestData = $this->defineRequestData();
        }

        return $this->requestData;
    }

    /**
     * Define request data 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineRequestData()
    {
        return \XLite\Core\Request::getInstance()->getData();
    }

    /**
     * Add error message 
     * 
     * @param \XLite\View\Inline\AInline $inline  Inline field
     * @param string                     $message message
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function addErrorMessage(\XLite\View\Inline\AInline $inline, $message)
    {
        $this->errorMessages[] = $inline->getField()->getLabel() . ': ' . $message;
    }

    /**
     * Get error messages 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getErrorMessages()
    {
        return $this->errorMessages;
    }

    // }}}

    // {{{ Content helpers

    /**
     * Get a list of CSS files
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/model/style.css';

        return $list;
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageBodyDir()
    {
        return 'model';
    }

    /**
     * Get line class
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getLineClass($index, \XLite\Model\AEntity $entity)
    {
        return implode(' ', $this->defineLineClass($index, $entity));
    }

    /**
     * Define line class  as list of names
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line model
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineLineClass($index, \XLite\Model\AEntity $entity)
    {
        $classes = array('line');

        if (0 === $index) {
            $classes[] = 'first';
        }

        if ($this->getItemsCount() == $index + 1) {
            $classes[] = 'last';
        }

        if (0 === ($index + 1) % $this->hightlightStep) {
            $classes[] = 'even';
        }

        return $classes;
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPagerClass()
    {
        return 'XLite\View\Pager\Admin\Model';
    }

    /**
     * Return internal list name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getListName()
    {
        $parts = explode('\\', get_called_class());

        $names = array();
        if ('Module' === $parts[1]) {
            $names[] = strtolower($parts[2]);
            $names[] = strtolower($parts[3]);
        }

        $names[] = strtolower($parts[count($parts) - 1]);

        return implode('.', $names) . '.' . parent::getListName();
    }

    /**
     * Build entity page URL 
     * 
     * @param \XLite\Model\AEntity $entity Entity
     * @param array                $column Column data
     *  
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function buildEntityURL(\XLite\Model\AEntity $entity, array $column)
    {
        return \XLite\Core\Converter::buildURL($column[static::COLUMN_LINK], '', array('id' => $entity->getUniqueIndetifier()));
    }

    /**
     * Get container class 
     * 
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getContainerClass()
    {
        return 'items-list'
            . ' widgetclass-' . $this->getWidgetClass()
            . ' widgettarget-' . $this->getWidgetTarget()
            . ' sessioncell-' . $this->getSessionCell();
    }

    // }}}

    // {{{ Line behaviors

    /**
     * Mark list as sortable 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getSortableType()
    {
        return static::SORT_TYPE_NONE;
    }

    /**
     * Mark list as switchyabvle (enable / disable)
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isSwitchable()
    {
        return false;
    }

    /**
     * Mark list as removable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isRemoved()
    {
        return false;
    }

    /**
     * Mark list as selectable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isSelectable()
    {
        return false;
    }

    /**
     * Get entity activity
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntityActivity(\XLite\Model\AEntity $entity)
    {
        return $entity->getEnabled();
    }

    /**
     * Get entity position 
     * 
     * @param \XLite\Model\AEntity $entity Entity
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getEntityPosition(\XLite\Model\AEntity $entity)
    {
        return $entity->getOrder();
    }

    // }}}

}

