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
 * @see       ____file_see____
 * @since     1.0.15
 */

namespace XLite\View\ItemsList\Model;

/**
 * Abstract admin model-based items list
 *
 * @see   ____class_see____
 * @since 1.0.15
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

    /**
     * Dump entity
     *
     * @var   \XLite\Model\AEntity
     * @see   ____var_see____
     * @since 1.0.15
     */
    protected $dumpEntity;

    // {{{ Fields

    /**
     * Get data prefix
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getDataPrefix()
    {
        return 'data';
    }

    /**
     * Get data prefix for remove cells
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getRemoveDataPrefix()
    {
        return 'delete';
    }

    /**
     * Get data prefix for select cells
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getSelectorDataPrefix()
    {
        return 'select';
    }

    /**
     * Get data prefix for new data
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    public function getCreateDataPrefix()
    {
        return 'new';
    }

    /**
     * Get self
     *
     * @return \XLite\View\ItemsList\Model\AModel
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getSelf()
    {
        return $this;
    }

    // }}}

    // {{{ Model processing

    /**
     * Get field classes list (only inline-based form fields)
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function getFieldClasses();

    /**
     * Define repository name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    abstract protected function defineRepositoryName();

    /**
     * Quick process
     *
     * @param array $parameters Parameters OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateFieldClasses()
    {
        return array();
    }

    /**
     * Process create new entities
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function processCreate()
    {
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
                    \XLite\Core\Database::getEM()->persist($entity);
                    $count++;
                }
            }
        }

        if (0 < $count) {
            $label = $this->getCreateMessage($count);
            if ($label) {
                \XLite\Core\TopMessage::getInstance()->addInfo($label);
            }
        }
    }

    /**
     * Get create message
     *
     * @param integer $count Count
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateMessage($count)
    {
        return \XLite\Core\Translation::lbl('X entities has been created', array('count' => $count));
    }

    /**
     * Create entity
     *
     * @return \XLite\Model\AEntity
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function createInlineFields(array $line, \XLite\Model\AEntity $entity)
    {
        $list = array();

        foreach ($this->getCreateFieldClasses() as $class) {
            $list[] = $this->getInlineField($class, $entity);
        }

        return $list;
    }

    // }}}

    // {{{ Remove

    /**
     * Get remove message
     *
     * @param integer $count Count
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getRemoveMessage($count)
    {
        return \XLite\Core\Translation::lbl('X entities has been removed', array('count' => $count));
    }

    /**
     * Process remove
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function processRemove()
    {
        $data = $this->getRequestData();
        $prefix = $this->getRemoveDataPrefix();
        $count = 0;

        if (isset($data[$prefix]) && is_array($data[$prefix]) && $data[$prefix]) {
            $repo = $this->getRepository();
            foreach ($data[$prefix] as $id => $allow) {
                if ($allow) {
                    $entity = $repo->find($id);
                    if ($entity) {
                        \XLite\Core\Database::getEM()->remove($entity);
                        $count++;
                    }
                }
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

    // }}}

    // {{{ Update

    /**
     * Process update
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
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
    protected function validateUpdate()
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
    protected function update()
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
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function validateCell(\XLite\View\FormField\Inline\AInline $inline, $key = null)
    {
        $value = $inline->getFieldDataFromRequest($this->getRequestData(), $key);
        if (isset($value)) {
            $inline->getField()->setValue($value);
        }
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
            $this->inlineFields = $this->defineInlineFields();
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
        return new $class(array('entity' => $entity, 'itemsList' => $this));
    }

    // }}}

    // {{{ Misc.

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
     * @param string                     $message Message
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
     * Check - body tempalte is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isPageBodyVisible()
    {
        return $this->hasResults() || static::CREATE_INLINE_NONE != $this->isInlineCreation();
    }

    /**
     * Check - pager box is visible or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isPagerVisible()
    {
        return $this->isPageBodyVisible() && $this->getPager();
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
     * Define line class as list of names
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

        $classes[] = 'entity-' . $entity->getUniqueIdentifier();

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
        return 'XLite\View\Pager\Admin\Model\Infinity';
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
        return parent::getListName() . '.' . implode('.', $this->getListNameSuffixes());
    }

    /**
     * Get list name suffixes
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
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

        return $name;
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
        return \XLite\Core\Converter::buildURL($column[static::COLUMN_LINK], '', array('id' => $entity->getUniqueIdentifier()));
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
     * Creation button position
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isCreation()
    {
        return static::CREATE_INLINE_NONE;
    }

    /**
     * Inline creation mechanism position
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function isInlineCreation()
    {
        return static::CREATE_INLINE_NONE;
    }

    /**
     * Get create entity URL
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getCreateURL()
    {
        return null;
    }

    /**
     * Get create button label
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.15
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
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function isPanelVisible()
    {
        return $this->getPanelClass() && $this->isPageBodyVisible();
    }

    /**
     * Get panel class
     *
     * @return \XLite\View\Base\FormStickyPanel
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getPanelClass()
    {
        return 'XLite\View\StickyPanel\ItemsListForm';
    }

    // }}}

}

