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
 */

return function()
{
    // Upgrade to 1.1.0 version is allowed from 1.0.25 version only
    $curVer = \Includes\Utils\Converter::composeVersion(\XLite::getInstance()->getMajorVersion(), \XLite::getInstance()->getMinorVersion());

    if (version_compare($curVer, '1.0.25', '<')) {
        \XLite\Core\TopMessage::getInstance()->addWarning('Please upgrade to 1.0.25 version first.');
        \XLite\Upgrade\Cell::getInstance()->setCoreVersion('1.0.25');
        \XLite\Upgrade\Cell::getInstance()->clear(false);

        // Redirect
        $url = \XLite\Core\Converter::buildURL('upgrade');
        \XLite\Core\Operator::redirect($url);

        return;
    }

    $entities = (array) \XLite\Core\Database::getRepo('\XLite\Model\Product')->findBySku('');

    foreach ($entities as $entity) {
        $entity->setSKU(null);
    }

    \XLite\Core\Database::getRepo('\XLite\Model\Product')->updateInBatch($entities);

    // Update language labels
    $labels = array(
        'update' => array(
            'Flat markup ($)' => 'Flat markup',
            'Markup per item ($)' => 'Markup per item',
            'Markup per weight unit ($)' => 'Markup per weight unit',
            'Per item markup ($)' => 'Per item markup',
            'Per weight unit markup ($)' => 'Per weight unit markup',
        ),
    );

    $objects = array();

    foreach ($labels as $method => $tmp) {
        $objects[$method] = array();

        foreach ($tmp as $oldKey => $data) {
            $object = \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->findOneBy(array('name'=>$oldKey));

            if (isset($object)) {
                if (empty($data)) {
                    $data = $oldKey;
                }

                switch ($method) {
                    case 'update':
                        if (is_array($data)) {
                            list($newKey, list($oldTranslation, $newTranslation)) = $data;

                            if (isset($newKey)) {
                                $object->setName($newKey);
                            }

                            if (is_null($object->getLabel())) {
                                $objects['delete'] = $object;
                                unset($object);

                            } elseif ($object->getLabel() === $oldTranslation) {
                                if (isset($newTranslation)) {
                                    $object->setLabel($newTranslation);

                                } else {
                                    $objects['delete'] = $object;
                                    unset($object);
                                }
                            }

                        } else {
                            $object->setName($data);
                        }

                        break;

                    case 'delete':
                        if (!is_null($object->getLabel()) && $object->getLabel() !== $data) {
                            unset($object);
                        }

                        break;

                    default:
                        // ...
                }

            } elseif ('insert' === $method) {
                $object = new \XLite\Model\LanguageLabel();
                $object->setName($oldKey);
                $object->setLabel($data);
            }

            if (isset($object)) {
                $objects[$method][] = $object;
            }
        }
    }

    foreach ($objects as $method => $labels) {
        \XLite\Core\Database::getRepo('\XLite\Model\LanguageLabel')->{$method . 'InBatch'}($labels);
    }

};
