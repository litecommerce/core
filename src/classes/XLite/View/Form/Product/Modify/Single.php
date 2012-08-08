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

namespace XLite\View\Form\Product\Modify;

/**
 * Details
 *
 */
class Single extends \XLite\View\Form\Product\Modify\Base\Single
{
    /**
     * getDefaultAction
     *
     * @return string
     */
    protected function getDefaultAction()
    {
        return 'modify';
    }

    /**
     * Ability to add the 'enctype="multipart/form-data"' form attribute
     *
     * @return boolean
     */
    protected function isMultipart()
    {
        return true;
    }

    /**
     * Get validator
     *
     * @return \XLite\Core\Validator\HashArray
     */
    protected function getValidator()
    {
        $validator = parent::getValidator();

        $data = $validator->addPair('postedData', new \XLite\Core\Validator\HashArray());
        $this->setDataValidators($data);

        return $validator;
    }

    /**
     * Get product identificator from request
     *
     * @return integer Product identificator
     */
    protected function getProductId()
    {
        return \XLite\Core\Request::getInstance()->product_id ?: \XLite\Core\Request::getInstance()->id;
    }

    /**
     * Set validators pairs for products data
     *
     * @param mixed $data Data
     *
     * @return null
     */
    protected function setDataValidators(&$data)
    {
        $data->addPair('sku', new \XLite\Core\Validator\SKU($this->getProductId()), null, 'SKU');
        $data->addPair('name', new \XLite\Core\Validator\String(true), null, 'Product Name');
        $data->addPair('category_ids', new \XLite\Core\Validator\PlainArray(), \XLite\Core\Validator\Pair\APair::SOFT, 'Category')
            ->setValidator(new \XLite\Core\Validator\Integer());
        $data->addPair('price', new \XLite\Core\Validator\Float(), null, 'Price')->setRange(0);
        $data->addPair('weight', new \XLite\Core\Validator\Float(), null, 'Weight')->setRange(0);
        $data->addPair('free_shipping', new \XLite\Core\Validator\Enum\Boolean(), null, 'Shippable');
        $data->addPair('enabled', new \XLite\Core\Validator\Enum\Boolean(), null, 'Available for sale');
        $data->addPair('meta_title', new \XLite\Core\Validator\String(), null, 'Product page title');
        $data->addPair('brief_description', new \XLite\Core\Validator\String(), null, 'Brief description');
        $data->addPair('description', new \XLite\Core\Validator\String(), null, 'Full description');
        $data->addPair('meta_tags', new \XLite\Core\Validator\String(), null, 'Meta keywords');
        $data->addPair('meta_desc', new \XLite\Core\Validator\String(), null, 'Meta description');

        $data->addPair(
            'cleanURL',
            new \XLite\Core\Validator\String\CleanURL(false, null, '\XLite\Model\Product', $this->getProductId()),
            null,
            'Clean URL'
        );

        $data->addPair(
            'category_ids',
            new \XLite\Core\Validator\PlainArray(),
            \XLite\Core\Validator\Pair\APair::SOFT,
            'Category'
        )->setValidator(new \XLite\Core\Validator\Integer());
    }
}
