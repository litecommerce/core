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
 * @since     1.0.16
 */

namespace XLite\Controller\Admin;

/**
 * AttributeAddChoices 
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class AttributeAddChoices extends \XLite\Controller\Admin\Base\AttributePopup
{
    /**
     * Return page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getTitle()
    {
        return 'Edit attribute values';
    }

    /**
     * Save changes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function doActionSave()
    {
        $this->setReturnURL($this->buildURL('attributes'));

        $objects = array(
            'insert' => array(),
            'update' => array(),
            'delete' => array(),
        );

        foreach ($this->getPostedData() as $id => $data) {
            $id = intval($id);

            if (0 < $id) {
                $choice = \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Choice')->find($id);

                if (!isset($choice)) {
                    return \XLite\Core\TopMessage::addError('Unknown choice ID: {{id}}', array('id' => $id));
                }

                if (\Includes\Utils\ArrayManager::getIndex($data, 'toDelete')) {
                    $objects['delete'][] = $choice;

                } else {
                    $title = \Includes\Utils\ArrayManager::getIndex($data, 'title');

                    if (empty($title)) {
                        return \XLite\Core\TopMessage::addError(
                            'Empty title for attribute "{{title}}"',
                            array('title' => $title)
                        );
                    }

                    $choice->setTitle($title);
                    $objects['update'][] = $choice;
                }

            } else {
                $title = \Includes\Utils\ArrayManager::getIndex($data, 'title');

                if (!empty($title)) {
                    $choice = new \XLite\Model\Attribute\Choice();
                    $choice->setTitle($title);
                    $choice->setAttribute($this->getAttribute());

                    $objects['insert'][] = $choice;
                }
            }
        }

        foreach ($objects as $method => $choices) {
            \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Choice')->{$method . 'InBatch'}($choices);
        }

        \XLite\Core\TopMessage::addInfo('Attribute choices has been successfully saved');
    }
}
