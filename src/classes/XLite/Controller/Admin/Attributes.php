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
        $groupObjectsToInsert = $attrObjectsToInsert = array();
        $groupObjectsToUpdate = $attrObjectsToUpdate = array();

        foreach ($this->getPostedData() as $groupId => $groupData) {
            $group = \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Group')->find($groupId);
            $title = \Includes\Utils\ArrayManager::getIndex($groupData, 'title');

            if (isset($group) || isset($title)) {

                if (!isset($group)) {
                    $group = new \XLite\Model\Attribute\Group();
                    $groupObjectsToInsert[] = $group;

                } else {
                    $groupObjectsToUpdate[] = $group;
                }

                if (!empty($groupData['pos'])) {
                    $group->setPos($groupData['pos']);
                }

                if (!empty($title)) {
                    $group->setTitle($title);

                } else {
                    return \XLite\Core\TopMessage::addError('Empty title for group');
                }
            }

            $attributes = (array) \Includes\Utils\ArrayManager::getIndex($groupData, static::FIELD_ATTRS);

            foreach ($attributes as $attrId => $attrData) {
                $attr = \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->find($attrId);

                if (!isset($attr)) {
                    $attr = new \XLite\Model\Attribute();
                    $attrObjectsToInsert[] = $attr;

                } else {
                    $attrObjectsToUpdate[] = $attr;
                }

                if (!empty($attrData['name'])) {
                    $attr->setName($attrData['name']);

                } else {
                    return \XLite\Core\TopMessage::addError('Empty identifier for attribute');
                }

                if (!empty($attrData['pos'])) {
                    $attr->setPos($attrData['pos']);
                }

                if (!empty($attrData['title'])) {
                    $attr->setTitle($attrData['title']);

                } else {
                    return \XLite\Core\TopMessage::addError('Empty title for attribute');
                }

                $attr->setGroup($group);
            }
        }

        \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Group')->insertInBatch($groupObjectsToInsert);
        \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->insertInBatch($attrObjectsToInsert);

        \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Group')->updateInBatch($groupObjectsToUpdate);
        \XLite\Core\Database::getRepo('\XLite\Model\Attribute')->updateInBatch($attrObjectsToUpdate);

        \XLite\Core\TopMessage::addInfo('Groups and attributes have been sucessfully saved');
    }
}
