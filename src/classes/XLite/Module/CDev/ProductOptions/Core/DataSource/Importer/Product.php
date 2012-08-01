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

namespace XLite\Module\CDev\ProductOptions\Core\DataSource\Importer;

/**
 * Product importer
 * 
 * @see   ____class_see____
 * @since 1.0.19
 */
abstract class Product extends \XLite\Core\DataSource\Importer\Product implements \XLite\Base\IDecorator
{
    /**
     * Update product
     *
     * @param \XLite\Model\Product $product Product
     * @param array                $cell    Cell
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function update(\XLite\Model\Product $product, array $cell)
    {
        parent::update($product, $cell);

        if (isset($cell['optionGroups'])) {
            $ts = microtime(true);
            $this->updateOptionGroups($product, $cell['optionGroups']);
            $this->logOperation(
                'Update option groups (eid: ' . $cell['id'] . ')',
                microtime(true) - $ts
            );
        }
    }

    /**
     * Update option groups 
     *
     * @param \XLite\Model\Product $product      Product
     * @param array                $optionGroups Option groups
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function updateOptionGroups(\XLite\Model\Product $product, array $optionGroups)
    {
        $ids = array();
        foreach ($product->getOptionGroups() as $group) {
            $ids[$group->getGroupId()] = $group;
        }

        foreach ($optionGroups as $group) {
            $model = $this->detectOptionGroup($product, $group);

            if ($model) {
                unset($ids[$model->getGroupId()]);

            } else {
                $model = $this->createOptionGroup($product, $group);
            }

            $this->updateOptionGroup($model, $group);
        }

        foreach ($ids as $group) {
            \XLite\Core\Database::getEM()->remove($group);
            $product->getOptionGroups()->removeElement($group);
        }
    }

    /**
     * Detect option group 
     * 
     * @param \XLite\Model\Product $product Product
     * @param array                $group   Group data
     *  
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function detectOptionGroup(\XLite\Model\Product $product, array $group)
    {
        $model = null;

        foreach ($product->getOptionGroups() as $entity) {
            foreach ($entity->getTranslations() as $translation) {
                if ($translation->getName() == $group['name']) {
                    $model = $entity;
                    break;
                }
            }

            if ($model) {
                break;
            }
        }

        return $model;
    }

    /**
     * Create option group
     *
     * @param \XLite\Model\Product $product Product
     * @param array                $group   Group data
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroup
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function createOptionGroup(\XLite\Model\Product $product, array $group)
    {
        $model = new \XLite\Module\CDev\ProductOptions\Model\OptionGroup;
        $product->addOptionGroups($model);
        $model->setProduct($product);

        return $model;
    }

    /**
     * Update option group 
     * 
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionGroup $model Model
     * @param array                                               $group Group data
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function updateOptionGroup(\XLite\Module\CDev\ProductOptions\Model\OptionGroup $model, array $group)
    {
        $model->setName($group['name']);
        $model->setEnabled(true);

        $ids = array();
        if ($model->getGroupId()) {
            foreach ($model->getOptions() as $option) {
                $ids[$option->getOptionId()] = $option;
            }
        }

        foreach ($group['options'] as $option) {
            $entity = $this->detectOption($model, $option);

            if ($entity) {
                unset($ids[$entity->getOptionId()]);

            } else {
                $entity = $this->createOption($model, $option);
            }

            $this->updateOption($entity, $option);
        }

        foreach ($ids as $option) {
            \XLite\Core\Database::getEM()->remove($option);
            $model->getOptions()->removeElement($option);
        }
    }

    /**
     * Detect option
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionGroup $group  Option group
     * @param array                                               $option Option data
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\Option
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function detectOption(\XLite\Module\CDev\ProductOptions\Model\OptionGroup $group, array $option)
    {
        $model = null;

        foreach ($group->getOptions() as $entity) {
            foreach ($entity->getTranslations() as $translation) {
                if ($translation->getName() == $option['name']) {
                    $model = $entity;
                    break;
                }
            }

            if ($model) {
                break;
            }
        }

        return $model;
    }

    /**
     * Create option
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\OptionGroup $group  Option group
     * @param array                                               $option Option data
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\Option
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function createOption(\XLite\Module\CDev\ProductOptions\Model\OptionGroup $group, array $option)
    {
        $model = new \XLite\Module\CDev\ProductOptions\Model\Option;
        $group->addOptions($model);
        $model->setGroup($group);

        return $model;
    }

    /**
     * Create option
     *
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $model  Option
     * @param array                                          $option Option data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function updateOption(\XLite\Module\CDev\ProductOptions\Model\Option $model, array $option)
    {
        $model->setName($option['name']);
        $model->setEnabled(true);

        $this->updateOptionSurcharge($model, $option['modifiers']['price'], 'price');
        $this->updateOptionSurcharge($model, $option['modifiers']['weight'], 'weight');
    }

    /**
     * Update option surcharge 
     * 
     * @param \XLite\Module\CDev\ProductOptions\Model\Option $model    Option
     * @param array                                          $modifier Modifier data
     * @param string                                         $type     Modifier type
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.21
     */
    protected function updateOptionSurcharge(\XLite\Module\CDev\ProductOptions\Model\Option $model, array $modifier, $type)
    {
        $surcharge = $model->getSurcharge($type);
        if (0 != $modifier['value'] && !$surcharge) {

            // Create if new value is not empty
            $surcharge = new \XLite\Module\CDev\ProductOptions\Model\OptionSurcharge;
            $model->addSurcharges($surcharge);
            $surcharge->setOption($model);
            $surcharge->setType($type);
        }

        if (0 == $modifier['value'] && $surcharge) {

            // Remove if new value is empty
            \XLite\Core\Database::getEM()->remove($surcharge);
            $model->getSurcharges()->removeElement($surcharge);
            
        } elseif ($surcharge) {
            $surcharge->setModifierType($modifier['type']);
            $surcharge->setModifier($modifier['value']);
        }
    }
}
