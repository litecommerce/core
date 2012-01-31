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

namespace XLite\Controller\Admin;

/**
 * Attributes book 
 *
 * @see   ____class_see____
 * @since 1.0.14
 */
class Attributes extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Field name for the boxes
     */
    const FIELD_ATTRS = 'attributes';

    /**
     * Check if there is the "default" field in attribute
     *
     * @param \XLite\Model\Attribute $attribute Attribute to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function hasAttributeDefaultValue(\XLite\Model\Attribute $attribute)
    {
        return in_array($attribute->getTypeName(), array('Number', 'Text', 'Selector'));
    }

    /**
     * Return the current page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function getTitle()
    {
        return 'Attributes';
    }

    /**
     * Save attribiutes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function doActionSave()
    {
        $objects = array(
            'insert' => array(),
            'update' => array(),
            'delete' => array(),
        );

        foreach ($this->getPostedData() as $groupId => $groupData) {
            $groupId = intval($groupId);
            $group   = null;
            $title   = \Includes\Utils\ArrayManager::getIndex($groupData, 'title');

            if (0 < $groupId) {
                $group = \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Group')->find($groupId);

                if (isset($group)) {
                    if (\Includes\Utils\ArrayManager::getIndex($groupData, 'toDelete')) {
                        $objects['delete']['group'][] = $group;
                        continue;

                    } elseif (empty($title)) {
                        return \XLite\Core\TopMessage::addError(
                            'Empty title for group "{{group}"',
                            array('group' => $group->getTitle())
                        );

                    } else {
                        $objects['update']['group'][] = $group;
                    }

                } else {
                    return \XLite\Core\TopMessage::addError('Unknown group ID: {{id}}', array('id' => $groupId));
                }

            } elseif (!empty($title)) {
                $group = new \XLite\Model\Attribute\Group();
                $objects['insert']['group'][] = $group;
            }

            if (isset($group)) {
                $group->setTitle($title);

                if (!empty($groupData['pos'])) {
                    $group->setPos($groupData['pos']);
                }
            }

            $attributes = (array) \Includes\Utils\ArrayManager::getIndex($groupData, static::FIELD_ATTRS);

            foreach ($attributes as $attrId => $attrData) {
                $attrId = intval($attrId);
                $attr   = null;
                $title  = \Includes\Utils\ArrayManager::getIndex($attrData, 'title');

                if (0 < $attrId) {
                    $attr = \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->find($attrId);

                    if (isset($attr)) {
                        if (\Includes\Utils\ArrayManager::getIndex($attrData, 'toDelete')) {
                            $objects['delete']['attr'][] = $attr;
                            continue;

                        } elseif (empty($title)) {
                            return \XLite\Core\TopMessage::addError(
                                'Empty title for attribute "{{attr}"',
                                array('attr' => $attr->getTitle())
                            );

                        } else {
                            $objects['update']['attr'][] = $attr;
                        }

                    } else {
                        return \XLite\Core\TopMessage::addError('Unknown attribute ID: {{id}}', array('id' => $attributeId));
                    }

                } elseif (!empty($title)) {
                    if (empty($attrData['class'])) {
                        return \XLite\Core\TopMessage::addError('Attribute type is not selected');
                    }

                    $class = '\XLite\Model\Attribute\Type\\' . $attrData['class'];

                    if (!\Includes\Utils\Operator::checkIfClassExists($class)) {
                        return \XLite\Core\TopMessage::addError('Unknown attribute class: {{class}}', array('class' => $class));
                    }

                    $attr = new $class();
                    $objects['insert']['attr'][] = $attr;
                }

                if (isset($attr)) {
                    $attr->setTitle($title);

                    if (!empty($attrData['name'])) {
                        $attr->setName($attrData['name']);

                    } else {
                        return \XLite\Core\TopMessage::addError(
                            'Empty identifier for attribute "{{attr}}"',
                            array('attr' => $attr->getTitle())
                        );
                    }

                    if (!empty($attrData['pos'])) {
                        $attr->setPos($attrData['pos']);
                    }

                    $attr->setDefaultValue(\Includes\Utils\ArrayManager::getIndex($attrData, 'default'));

                    switch ($attr->getTypeName()) {
                        case 'Number':
                            $attr->setDecimals(\Includes\Utils\ArrayManager::getIndex($attrData, 'decimals'));
                            $attr->setUnit(\Includes\Utils\ArrayManager::getIndex($attrData, 'unit'));
                            break;

                        default:
                            // ...
                    }

                    if (isset($group) && $group->isPersistent()) {
                        $attr->setGroup($group);
                    }
                }
            }
        }

        foreach ($objects as $operation => $tmp) {
            foreach ($tmp as $type => $data) {
                \XLite\Core\Database::getRepo('\XLite\Model\Attribute' . ('group' === $type ? '\Group' : ''))
                    ->{$operation . 'InBatch'}($data);
            }
        }

        \XLite\Core\TopMessage::addInfo('Groups and attributes have been sucessfully saved');
    }
}
