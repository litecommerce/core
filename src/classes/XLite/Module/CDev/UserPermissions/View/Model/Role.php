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

namespace XLite\Module\CDev\UserPermissions\View\Model;

/**
 * Role 
 * 
 */
class Role extends \XLite\View\Model\AModel
{
    /**
     * Shema default
     *
     * @var array
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
        ),
        'permissions' => array(
            self::SCHEMA_CLASS    => 'XLite\Module\CDev\UserPermissions\View\FormField\Permissions',
            self::SCHEMA_LABEL    => 'Permissions',
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
        if ($this->getModelObject()->isPermanentRole()) {
            unset($this->schemaDefault['enabled']);
            $this->schemaDefault['permissions'][self::SCHEMA_CLASS] = 'XLite\View\FormField\Label';
        }

        return $this->getFieldsBySchema($this->schemaDefault);
    }

    /**
     * This object will be used if another one is not pased
     *
     * @return \XLite\Module\CDev\UserPermissions\Model\Role
     */
    protected function getDefaultModelObject()
    {
        $model = $this->getModelId()
            ? \XLite\Core\Database::getRepo('XLite\Model\Role')->find($this->getModelId())
            : null;

        return $model ?: new \XLite\Model\Role;
    }

    /**
     * Return name of web form widget class
     *
     * @return string
     */
    protected function getFormClass()
    {
        return '\XLite\Module\CDev\UserPermissions\View\Form\Role';
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
     * Populate model object properties by the passed data
     *
     * @param array $data Data to set
     *
     * @return void
     */
    protected function setModelProperties(array $data)
    {
        $permissions = $data['permissions'];
        unset($data['permissions']);

        if (isset($data['enabled']) && $this->getModelObject()->isPermanentRole()) {
            unset($data['enabled']);
        }

        if (!empty($data['name'])) {
            $data['name'] = strip_tags($data['name']);
        }

        parent::setModelProperties($data);

        $model = $this->getModelObject();

        if (!$model->isPermanentRole()) {

            // Remove old links
            foreach ($model->getPermissions() as $perm) {
                $perm->getRoles()->removeElement($model);
            }
            $model->getPermissions()->clear();

            $permanent = \XLite\Core\Database::getRepo('XLite\Model\Role')->getPermanentRole();
            if ($permanent->getId() == $model->getId()) {
                $root = \XLite\Core\Database::getRepo('XLite\Model\Role\Permission')->findOneBy(
                    array('code' => \XLite\Model\Role\Permission::ROOT_ACCESS)
                );
                if ($root && !in_array($root->getId(), $permissions)) {
                    $permissions[] = $root->getId();
                }
            }

            // Add new links
            foreach ($permissions as $pid) {
                $permission = \XLite\Core\Database::getRepo('XLite\Model\Role\Permission')->find($pid);
                if ($permission) {
                    $model->addPermissions($permission);
                    $permission->addRoles($model);
                }
            }
        }
    }

    /**
     * Add top message
     *
     * @return void
     */
    protected function addDataSavedTopMessage()
    {
        if ($this->getModelObject()->getId()) {
            \XLite\Core\TopMessage::addInfo('The role has been updated');

        } else {
            \XLite\Core\TopMessage::addInfo('The role has been added');
        }
    }

    /**
     * Retrieve property from the model object
     *
     * @param mixed $name Field/property name
     *
     * @return mixed
     */
    protected function getModelObjectValue($name)
    {
        return ('permissions' == $name && $this->getModelObject()->isPermanentRole())
            ? 'Root access'
            : parent::getModelObjectValue($name);
    }

}
