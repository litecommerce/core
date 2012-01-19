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
 * @since     1.0.0
 */

namespace XLite\Controller\Admin;

/**
 * Product classes
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class ProductClasses extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Page title
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getTitle()
    {
        return 'Product classes';
    }

    /**
     * Add/update product classes
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function doActionSave()
    {
        $objects = array(
            'insert' => array(),
            'update' => array(),
            'delete' => array(),
        );

        foreach ($this->getPostedData() as $classId => $classData) {
            $classId = intval($classId);
            $class   = null;
            $name    = \Includes\Utils\ArrayManager::getIndex($classData, 'name');

            if (0 < $classId) {
                $class = \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->find($classId);

                if (isset($class)) {
                    if (\Includes\Utils\ArrayManager::getIndex($classData, $this->getPrefixToDelete())) {
                        $objects['delete']['class'][] = $class;
                        continue;

                    } elseif (empty($name)) {
                        return \XLite\Core\TopMessage::addError(
                            'Empty name for class "{{class}"',
                            array('class' => $class->getName())
                        );

                    } else {
                        $objects['update']['class'][] = $class;
                    }

                } else {
                    return \XLite\Core\TopMessage::addError('Unknown class ID: {{id}}', array('id' => $classId));
                }

            } elseif (!empty($name)) {
                $class = new \XLite\Model\ProductClass();
                $objects['insert']['class'][] = $class;
            }

            if (isset($class)) {
                $class->setName($name);

                if (!empty($classData['pos'])) {
                    $class->setPos($classData['pos']);
                }
            }
        }

        foreach ($objects as $operation => $tmp) {
            foreach ($tmp as $type => $data) {
                \XLite\Core\Database::getRepo('\XLite\Model\ProductClass')->{$operation . 'InBatch'}($data);
            }
        }

        \XLite\Core\TopMessage::addInfo('Product classes have been sucessfully saved');
    }
}
