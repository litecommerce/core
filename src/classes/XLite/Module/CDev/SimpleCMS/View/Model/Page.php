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
 * @copyright Copyright (c) 2010-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 */

namespace XLite\Module\CDev\SimpleCMS\View\Model;

/**
 * Page view model
 *
 */
class Page extends \XLite\View\Model\AModel
{
    /**
     * Shema default
     *
     * @var   array
     */
    protected $schemaDefault = array(
        'name' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Text',
            self::SCHEMA_LABEL    => 'Name',
            self::SCHEMA_REQUIRED => true,
        ),
        'enabled' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Input\Checkbox\Enabled',
            self::SCHEMA_LABEL    => 'Enabled',
            self::SCHEMA_REQUIRED => false,
        ),
        'cleanURL' => array(
            self::SCHEMA_CLASS    => 'XLite\Module\CDev\SimpleCMS\View\FormField\Input\Text\CleanURL',
            self::SCHEMA_LABEL    => 'CleanURL',
            self::SCHEMA_REQUIRED => false,
        ),
        'teaser' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Simple',
            self::SCHEMA_LABEL    => 'Teaser',
            self::SCHEMA_REQUIRED => false,
        ),
        'body' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Advanced',
            self::SCHEMA_LABEL    => 'Content',
            self::SCHEMA_REQUIRED => true,
        ),
        'metaKeywords' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Textarea\Simple',
            self::SCHEMA_LABEL    => 'Meta keywords',
            self::SCHEMA_REQUIRED => false,
        ),
        'image' => array(
            self::SCHEMA_CLASS    => 'XLite\View\FormField\Image',
            self::SCHEMA_LABEL    => 'Open graph image',
            self::SCHEMA_REQUIRED => false,
            \XLite\View\FormField\Image::PARAM_OBJECT => 'page',
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
     * This object will be used if another one is not pased
     *
     * @return \XLite\Module\CDev\SimpleCMS\Model\Page
     */
    protected function getDefaultModelObject()
    {
        $model = $this->getModelId()
            ? \XLite\Core\Database::getRepo('XLite\Module\CDev\SimpleCMS\Model\Page')->find($this->getModelId())
            : null;

        return $model ?: new \XLite\Module\CDev\SimpleCMS\Model\Page;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\Module\CDev\SimpleCMS\View\Form\Model\Page';
    }

    /**
     * Return fields list by the corresponding schema
     *
     * @return array
     */
    protected function getFormFieldsForSectionDefault()
    {
        if ($this->getModelObject()->getId()) {
            $this->schemaDefault['image'][\XLite\View\FormField\Image::PARAM_OBJECT_ID] = $this->getDefaultModelObject()->getId();
            if ($this->getDefaultModelObject()->getImage()) {
                $this->schemaDefault['image'][\XLite\View\FormField\Image::PARAM_FILE_OBJECT_ID] = $this->getDefaultModelObject()->getImage()->getId();
//                $this->schemaDefault['image'][\XLite\View\FormField\Image::PARAM_REMOVE_BUTTON] = true;
            }
        }

        return $this->getFieldsBySchema($this->schemaDefault);
    }

    /**
     * Return list of the "Button" widgets
     *
     * @return array
     */
    protected function getFormButtons()
    {
        $result = parent::getFormButtons();

        $label = $this->getModelObject()->getId() ? 'Update' : 'Create';

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
            \XLite\Core\TopMessage::addInfo('The page has been updated');

        } else {
            \XLite\Core\TopMessage::addInfo('The page has been added');
        }
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
        if ($this->getPostedData('autogenerateCleanURL')) {
            $data['cleanURL'] = $this->generateCleanURL($data['name']);
        }

        if (!$data['cleanURL']) {
            unset($data['cleanURL']);
        }

        parent::setModelProperties($data);
    }

    /**
     * Generate clean URL
     *
     * @param string $name Page name
     *
     * @return string
     */
    protected function generateCleanURL($name)
    {
        $result = '';

        if (isset($name)) {
            $separator = \XLite\Core\Converter::getCleanURLSeparator();
            $result   .= strtolower(preg_replace('/\W+/S', $separator, $name));

            $suffix    = '';
            $increment = 1;

            $entity    = $this->getDefaultModelObject();
            $repo      = \XLite\Core\Database::getRepo(get_class($entity));

            while (
                ($tmp = $repo->findOneByCleanURL($result . $suffix))
                && $entity->getUniqueIdentifier() != $tmp->getUniqueIdentifier()
                && $increment < 1000
            ) {
                $suffix = $separator . $increment++;
            }

            if (!empty($suffix)) {
                $result .= $suffix;
            }
        }

        return $result;
    }

}
