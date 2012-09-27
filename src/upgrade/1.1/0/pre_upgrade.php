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
            'I accept Terms and Conditions' => array('I accept Terms and Conditions', array('I accept <a href="{{URL}}">Terms & Conditions</a>', 'I accept <a href="{{URL}}" target="_blank">Terms & Conditions</a>')),
            'To get the format of the import data you can export your products to a file' => array('To get the format of the import data you can export your products to a file', array('To get the format of the import data you can <a href="{{url}}">export your products</a> to a file and then review the format of that file.', 'To find out the data format for import, you can create a sample data file <a href="{{url}}">by exporting your existing products</a>. Then you can prepare the file for import using the same format.')),
            'Depending on the size of your data file, importing may take some time.' => array('Depending on the size of your data file, importing may take some time.', array('Depending on the size of your data file, importing may take some time.', 'Note that data import time will strongly depend on the size of the file being imported.')),
            'If you store product images in the database, they are included in the SQL dump file' => array('If you store product images in the database, they are included in the SQL dump file', array('If you store product images in the database, they are included in the SQL dump file. If the product images are located on the file system, they are not included in the SQL dump file. To back such images up you need to download them directly from the server.', 'If you store product images in the database, they are included in the SQL dump file. If the product images are located on the file system, they are not included in the SQL dump file. To back up such images you need to download them directly from the server.')),
        ),
        'insert' => array(
            'N it.' => '{{count}} it.',
            'Placed on X by Y' => 'Placed on <span class="date">{{date}}</span> by <span class="name">{{name}}</span>',
            'Placed on X by Y link' => 'Placed on <span class="date">{{date}}</span> by <a href="{{url}}" class="name">{{name}}</a>',
            'Order Total X' => 'Order Total: <span>{{total}}</span>',
            'Payment was not finished' => 'Oops! Our payment system have not informed us whether your last order is paid, or not. Is there a payment form that you have not completed yet? If so, please complete and submit it. Or you can quickly <a href="{{url}}">re-order products</a> from your last incomplete order.',
            'Payment transaction X issued' => 'Payment transaction [method: {{trx_method}}, type: {{trx_type}}, amount: {{trx_value}}, status: {{trx_status}}]',
            'Backend payment transaction X issued' => 'Backend payment transaction [method: {{trx_method}}, type: {{trx_type}}, amount: {{trx_value}}, status: {{trx_status}}]',
            'Your account email is X.' => 'Your account email is {{email}}.',
            'Your account password is X.' => 'Your account password is {{password}}.',
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

    // Update config options
    $config = array(
        'insert' => array(
        ),
        'update' => array(
            'General' => array(
                'decimal_delim' => array('Currency decimal separator', 'Decimal separator for float values'),
                'redirect_to_cart' => array('Redirect customer to cart when adding a product', 'Redirect customer to the cart page after a product is added to cart'),
                'shop_closed' => array('Check this to close the shop temporarily', 'Close the shop temporarily'),
                'thousand_delim' => array('Currency thousands separator', 'Thousands separator for float values'),
            ),
        ),
        'delete' => array(
        ),
    );

    $objects = array();

    foreach ($config as $method => $tmp) {
        foreach ($tmp as $category => $data) {
            foreach ($data as $name => $value) {
                $object = \XLite\Core\Database::getRepo('\XLite\Model\Config')->findOneBy(array('name' => $name, 'category' => $category));
                if (isset($object)) {
                    if($method == 'update'){
                        list($oldLabel, $newLabel) = $value;

                        if ($object->getOptionName() === $oldLabel) {
                            $object->setOptionName($newLabel);
                        }

                    }

                } elseif ('insert' === $method) {
                    $object = new \XLite\Model\Config();
                    $object->setCategory($category);
                    $object->setName($name);

                }

                if (isset($object)) {
                    $objects[$method][] = $object;
                }
            }
        }
    }

    foreach ($objects as $method => $config) {
        \XLite\Core\Database::getRepo('\XLite\Model\Config')->{$method . 'InBatch'}($config);
    }

    // Update shop_currency option type
    $option = \XLite\Core\Database::getRepo('\XLite\Model\Config')->findOneBy(array('name' => 'shop_currency', 'category' => 'General'));
    if (isset($option)) {
        $option->setType('');
        \XLite\Core\Database::getEM()->persist($option);
        \XLite\Core\Database::getEM()->flush();
    }

    // Make cleanURL's unique for categories
    $cats = \XLite\Core\Database::getRepo('XLite\Model\Category')->findByCleanURL('');

    foreach ($cats as $cat) {

        $separator = '-';
        $result    = strtolower(preg_replace('/\W+/S', $separator, $cat->getName()));

        $suffix    = '';
        $increment = 1;

        $repo      = \XLite\Core\Database::getRepo('XLite\Model\Category');

        while (
            ($tmp = $repo->findOneByCleanURL($result . $suffix))
            && $cat->getCategoryId() != $tmp->getCategoryId()
            && $increment < 1000
        ) {
            $suffix = $separator . $increment++;
        }

        if (!empty($suffix)) {
            $result .= $suffix;
        }

        $cat->setCleanURL($result);
        $cat->update();
    }
};
