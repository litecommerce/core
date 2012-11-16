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

namespace XLite\View\ItemsList\Model;

/**
 * Abstract admin model-based items list
 *
 */
abstract class AModel extends \XLite\View\ItemsList\AItemsList
{
    /**
     * Sortable types
     */
    const SORT_TYPE_NONE  = 0;
    const SORT_TYPE_MOVE  = 1;
    const SORT_TYPE_INPUT = 2;

    /**
     * Create inline position
     */
    const CREATE_INLINE_NONE   = 0;
    const CREATE_INLINE_TOP    = 1;
    const CREATE_INLINE_BOTTOM = 2;


    /**
     * Hightlight step
     *
     * @var integer
     */
    protected $hightlightStep = 2;

    /**
     * Error messages
     *
     * @var array
     */
    protected $errorMessages = array();

    /**
     * Request data
     *
     * @var array
     */
    protected $requestData;

    /**
     * Inline fields
     *
     * @var array
     */
    protected $inlineFields;

    /**
     * Dump entity
     *
     * @var \XLite\Model\AEntity
     */
    protected $dumpEntity;

    // {{{ Fields

    /**
     * Get data prefix
     *
     * @return string
     */
    public function getDataPrefix()
    {
        return 'data';
    }

    /**
     * Get data prefix for remove cells
     *
     * @return string
     */
    public function getRemoveDataPrefix()
    {
        return 'delete';
    }

    /**
     * Get data prefix for select cells
     *
     * @return string
     */
    public function getSelectorDataPrefix()
    {
        return 'select';
    }

    /**
     * Get data prefix for new data
     *
     * @return string
     */
    public function getCreateDataPrefix()
    {
        return 'new';
    }

    /**
     * Get self
     *
     * @return \XLite\View\ItemsList\Model\AModel
     */
    protected function getSelf()
    {
        return $this;
    }

    // }}}

    // {{{ Model processing

    /**
     * Get field objects list (only inline-based form fields)
     *
     * @return array
     */
    abstract protected function getFieldObjects();

    /**
     * Define repository name
     *
     * @return string
     */
    abstract protected function defineRepositoryName();

    /**
     * Quick process
     *
     * @param array $parameters Parameters OPTIONAL
     *
     * @return void
     */
    public function processQuick(array $parameters = array())
    {
        $this->setWidgetParams($parameters);
        $this->init();
        $this->process();
    }


    /**
     * Process
     *
     * @return void
     */
    public function process()
    {
        $this->processRemove();
        $this->processCreate();
        $this->processUpdate();

        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Get repository
     *
     * @return \XLite\Model\Repo\ARepo
     */
    protected function getRepository()
    {
        return \XLite\Core\Database::getRepo($this->defineRepositoryName());
    }

    // {{{ Create

    /**
     * Get create field classes
     *
     * @return void
     */
    protected function getCreateFieldClasses()
    {
        return array();
    }

    /**
     * Process create new entities
     *
     * @return void
     */
    protected function processCreate()
    {
        $errCount = 0;
        $count = 0;

        foreach ($this->getNewDataLine() as $key => $line) {

            if ($this->isNewLineSufficient($line, $key)) {
                $entity = $this->createEntity();
                $fields = $this->createInlineFields($line, $entity);

                $validated = 0 < count($fields);
                foreach ($fields as $inline) {
                    $validated = $this->validateCell($inline, $key) && $validated;
                }

                if ($validated) {
                    foreach ($fields as $inline) {
                        $this->saveCell($inline);
                    }
                    $entity->getRepository()->insert($entity);
                    $count++;

                } else {
                    $errCount++;
                }
            }
        }

        if (0 < $count) {
            $label = $this->getCreateMessage($count);
            if ($label) {
                \XLite\Core\TopMessage::getInstance()->addInfo($label);
            }
        }

        if (0 < $errCount) {
            $this->processCreateErrors();
        }
    }

    /**
     * Get create message
     *
     * @param integer $count Count
     *
     * @return string
     */
    protected function getCreateMessage($count)
    {
        return \XLite\Core\Translation::lbl('X entities has been created', array('count' => $count));
    }

    /**
     * Create entity
     *
     * @return \XLite\Model\AEntity
     */
    protected function createEntity()
    {
        $entityClass = $this->defineRepositoryName();

        return new $entityClass;
    }

    /**
     * Get dump entity
     *
     * @return \XLite\Model\AEntity
     */
    protected function getDumpEntity()
    {
        if (!isset($this->dumpEntity)) {
            $this->dumpEntity = $this->createEntity();
        }

        return $this->dumpEntity;
    }

    /**
     * Get new data line
     *
     * @return array
     */
    protected function getNewDataLine()
    {
        $data = $this->getRequestData();
        $prefix = $this->getCreateDataPrefix();

        return (isset($data[$prefix]) && is_array($data[$prefix])) ? $data[$prefix] : array();
    }

    /**
     * Check - new line is sufficient or not
     *
     * @param array   $line Data line
     * @param integer $key  Field key gathered from request data, eg: new[this-key][field-name] (see ..\AInline::processCreate())
     *
     * @return boolean
     */
    protected function isNewLineSufficient(array $line, $key)
    {
        return 0 !== $key && 0 < count($line);
    }

    /**
     * Create inline fields list
     *
     * @param array                $line   Line data
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return array
     */
    protected function createInlineFields(array $line, \XLite\Model\AEntity $entity)
    {
        $list = array();

        foreach ($this->getCreateFieldClasses() as $object) {
            $this->prepareInlineField($object, $entity);
            $list[] = $object;
        }

        return $list;
    }

    /**
     * Process errors
     *
     * @return void
     */
    protected function processCreateErrors()
    {
        \XLite\Core\TopMessage::getInstance()->addBatch($this->getErrorMessages(), \XLite\Core\TopMessage::ERROR);

        // Run controller's method
        $this->setActionError();
    }

    // }}}

    // {{{ Remove

    /**
     * Get remove message
     *
     * @param integer $count Count
     *
     * @return string
     */
    protected function getRemoveMessage($count)
    {
        return \XLite\Core\Translation::lbl('X entities has been removed', array('count' => $count));
    }

    /**
     * Process remove
     *
     * @return integer
     */
    protected function processRemove()
    {
        $count = 0;

        $repo = $this->getRepository();
        foreach ($this->getEntityIdListForRemove() as $id) {
            $entity = $repo->find($id);
            if ($entity && $this->removeEntity($entity)) {
                $count++;
            }
        }

        if (0 < $count) {
            $label = $this->getRemoveMessage($count);
            if ($label) {
                \XLite\Core\TopMessage::getInstance()->addInfo($label);
            }
        }

        return $count;
    }

    /**
     * Get entity's ID list for remove
     *
     * @return void
     */
    protected function getEntityIdListForRemove()
    {
        $data = $this->getRequestData();
        $prefix = $this->getRemoveDataPrefix();

        $list = array();

        if (isset($data[$prefix]) && is_array($data[$prefix]) && $data[$prefix]) {
            foreach ($data[$prefix] as $id => $allow) {
                if ($allow) {
                    $list[] = $id;
                }
            }
        }

        return $list;
    }

    /**
     * Remove entity
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return boolean
     */
    protected function removeEntity(\XLite\Model\AEntity $entity)
    {
        $entity->getRepository()->delete($entity, false);

        return true;
    }

    // }}}

    // {{{ Update

    /**
     * Process update
     *
     * @return void
     */
    protected function processUpdate()
    {
        $result = true;

        if ($this->isActiveModelProcessing()) {
            $result = $this->validateUpdate();

            if ($result) {
                $result = $this->update();

            } else {
                $this->processUpdateErrors();
            }
        }

        return $result;
    }

    /**
     * Check - moel processing is active or not
     *
     * @return void
     */
    protected function isActiveModelProcessing()
    {
        return $this->hasResults() && $this->getFieldObjects();
    }

    /**
     * Validate data
     *
     * @return boolean
     */
    protected function validateUpdate()
    {
        $validated = true;

        foreach ($this->prepareInlineFields() as $field) {
            $validated = $this->validateCell($field) && $validated;
        }

        return $validated;
    }

    /**
     * Save data
     *
     * @return integer
     */
    protected function update()
    {
        $count = 0;

        foreach ($this->prepareInlineFields() as $field) {
            $count++;
            $this->saveCell($field);
        }

        foreach ($this->getPageData() as $entity) {
            $entity->getRepository()->update($entity, array(), false);
            if ($this->isDefault()) {
                $entity->setDefaultValue($this->isDefaultEntity($entity));
            }
        }

        return $count;
    }

    /**
     * Is default entity 
     *
     * @param \XLite\Model\AEntity $entity Line
     *
     * @return boolean
     */
    protected function isDefaultEntity(\XLite\Model\AEntity $entity)
    {
        return isset($this->requestData['defaultValue']) 
            && $this->requestData['defaultValue'] == $entity->getId();
    }

    /**
     * Process errors
     *
     * @return void
     */
    protected function processUpdateErrors()
    {
        \XLite\Core\TopMessage::getInstance()->addBatch($this->getErrorMessages(), \XLite\Core\TopMessage::ERROR);

        // Run controller's method
        $this->setActionError();
    }

    /**
     * Validate inline field
     *
     * @param \XLite\View\FormField\Inline\AInline $inline Inline field
     * @param integer                              $key    Field key gathered from request data, eg: new[this-key][field-name] (see ..\AInline::processCreate()) OPTIONAL
     *
     * @return boolean
     */
    protected function validateCell(\XLite\View\FormField\Inline\AInline $inline, $key = null)
    {
        $inline->setValueFromRequest($this->getRequestData(), $key);
        list($flag, $message) = $inline->validate();
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
     */
    protected function saveCell(\XLite\View\FormField\Inline\AInline $inline)
    {
        $inline->saveValue();
    }

    /**
     * Get inline fields
     *
     * @return array
     */
    protected function prepareInlineFields()
    {
        if (!isset($this->inlineFields)) {
            $this->inlineFields = $this->defineInlineFields();
        }

        return $this->inlineFields;
    }

    /**
     * Define inline fields
     *
     * @return array
     */
    protected function defineInlineFields()
    {
        $list = array();

        foreach ($this->getPageData() as $entity) {
            foreach ($this->getFieldObjects() as $object) {
                $this->prepareInlineField($object, $entity);
                $list[] = $object;
            }
        }

        return $list;
    }

    /**
     * Get inline field
     *
     * @param \XLite\View\FormField\Inline\AInline $field  Field
     * @param \XLite\Model\AEntity                 $entity Entity
     *
     * @return void
     */
    protected function prepareInlineField(\XLite\View\FormField\Inline\AInline $field, \XLite\Model\AEntity $entity)
    {
        $field->setWidgetParams(array('entity' => $entity, 'itemsList' => $this));
    }

    // }}}

    // {{{ Misc.

    /**
     * Get request data
     *
     * @return array
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
     */
    protected function defineRequestData()
    {
        return \XLite\Core\Request::getInstance()->getData();
    }

    /**
     * Add error message
     *
     * @param \XLite\View\Inline\AInline $inline  Inline field
     * @param string                     $message Message
     *
     * @return void
     */
    protected function addErrorMessage(\XLite\View\Inline\AInline $inline, $message)
    {
        $this->errorMessages[] = $inline->getLabel() . ': ' . $message;
    }

    /**
     * Get error messages
     *
     * @return array
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
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = $this->getDir() . '/model/style.css';

        return $list;
    }

    /**
     * Check - body tempalte is visible or not
     *
     * @return boolean
     */
    protected function isPageBodyVisible()
    {
        return $this->hasResults() || static::CREATE_INLINE_NONE != $this->isInlineCreation();
    }

    /**
     * Check - pager box is visible or not
     *
     * @return boolean
     */
    protected function isPagerVisible()
    {
        return $this->isPageBodyVisible() && $this->getPager();
    }

    /**
     * Return dir which contains the page body template
     *
     * @return string
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
     */
    protected function getLineClass($index, \XLite\Model\AEntity $entity)
    {
        return implode(' ', $this->defineLineClass($index, $entity));
    }

    /**
     * Define line class as list of names
     *
     * @param integer              $index  Line index
     * @param \XLite\Model\AEntity $entity Line model
     *
     * @return array
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

        $classes[] = 'entity-' . $entity->getUniqueIdentifier();

        return $classes;
    }

    /**
     * Auxiliary method to check visibility
     *
     * @return boolean
     */
    protected function isDisplayWithEmptyList()
    {
        return true;
    }

    /**
     * Return class name for the list pager
     *
     * @return string
     */
    protected function getPagerClass()
    {
        return 'XLite\View\Pager\Admin\Model\Infinity';
    }

    /**
     * Return internal list name
     *
     * @return string
     */
    protected function getListName()
    {
        return parent::getListName() . '.' . implode('.', $this->getListNameSuffixes());
    }

    /**
     * Get list name suffixes
     *
     * @return array
     */
    protected function getListNameSuffixes()
    {
        $parts = explode('\\', get_called_class());

        $names = array();
        if ('Module' === $parts[1]) {
            $names[] = strtolower($parts[2]);
            $names[] = strtolower($parts[3]);
        }

        $names[] = strtolower($parts[count($parts) - 1]);

        return $names;
    }

    /**
     * Build entity page URL
     *
     * @param \XLite\Model\AEntity $entity Entity
     * @param array                $column Column data
     *
     * @return string
     */
    protected function buildEntityURL(\XLite\Model\AEntity $entity, array $column)
    {
        return \XLite\Core\Converter::buildURL(
            $column[static::COLUMN_LINK],
            '',
            array($entity->getUniqueIdentifierName() => $entity->getUniqueIdentifier())
        );
    }

    /**
     * Get container class
     *
     * @return string
     */
    protected function getContainerClass()
    {
        return 'widget items-list'
            . ' widgetclass-' . $this->getWidgetClass()
            . ' widgettarget-' . $this->getWidgetTarget()
            . ' sessioncell-' . $this->getSessionCell();
    }

    /**
     * Get container attributes
     *
     * @return array
     */
    protected function getContainerAttributes()
    {
        return array(
            'class' => $this->getContainerClass(),
        );
    }

    /**
     * Get container attributes as string
     *
     * @return string
     */
    protected function getContainerAttributesAsString()
    {
        $list = array();
        foreach ($this->getContainerAttributes() as $name => $value) {
            $list[] = $name . '="' . func_htmlspecialchars($value) . '"';
        }

        return implode(' ', $list);
    }


    // }}}

    // {{{ Line behaviors

    /**
     * Mark list as sortable
     *
     * @return integer
     */
    protected function getSortableType()
    {
        return static::SORT_TYPE_NONE;
    }

    /**
     * Mark list as switchable (enable / disable)
     *
     * @return boolean
     */
    protected function isSwitchable()
    {
        return false;
    }

    /**
     * Mark list as removable
     *
     * @return boolean
     */
    protected function isRemoved()
    {
        return false;
    }

    /**
     * Mark list iten as default
     *
     * @return boolean
     */
    protected function isDefault()
    {
        return false;
    }

    /**
     * Mark list as selectable
     *
     * @return boolean
     */
    protected function isSelectable()
    {
        return false;
    }

    /**
     * Creation button position
     *
     * @return integer
     */
    protected function isCreation()
    {
        return static::CREATE_INLINE_NONE;
    }

    /**
     * Inline creation mechanism position
     *
     * @return integer
     */
    protected function isInlineCreation()
    {
        return static::CREATE_INLINE_NONE;
    }

    /**
     * Get create entity URL
     *
     * @return string
     */
    protected function getCreateURL()
    {
        return null;
    }

    /**
     * Get create button label
     *
     * @return string
     */
    protected function getCreateButtonLabel()
    {
        return 'Create';
    }

    /**
     * Get entity position
     *
     * @param \XLite\Model\AEntity $entity Entity
     *
     * @return integer
     */
    protected function getEntityPosition(\XLite\Model\AEntity $entity)
    {
        return $entity->getOrder();
    }

    // }}}

    // {{{ Sticky panel

    /**
     * Check - sticky panel is visible or not
     *
     * @return boolean
     */
    protected function isPanelVisible()
    {
        return $this->getPanelClass() && $this->isPageBodyVisible();
    }

    /**
     * Get panel class
     *
     * @return \XLite\View\Base\FormStickyPanel
     */
    protected function getPanelClass()
    {
        return 'XLite\View\StickyPanel\ItemsListForm';
    }

    // }}}

    // {{{ Data

    /**
     * Return coupons list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return array|integer
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return $this->getRepository()->search($cnd, $countOnly);
    }

    // }}}
}

