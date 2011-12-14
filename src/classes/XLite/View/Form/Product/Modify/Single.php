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

namespace XLite\View\Form\Product\Modify;

/**
 * Details
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Single extends \XLite\View\Form\Product\Modify\Base\Single
{
    /**
     * getDefaultAction
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDefaultAction()
    {
        return 'modify';
    }

    /**
     * Ability to add the 'enctype="multipart/form-data"' form attribute
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isMultipart()
    {
        return true;
    }

    /**
     * Get validator
     *
     * @return \XLite\Core\Validator\HashArray
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getValidator()
    {
        $validator = parent::getValidator();

        $strict = \XLite\Core\Validator\Pair\APair::STRICT;
        $soft = \XLite\Core\Validator\Pair\APair::SOFT;

        $data = $validator->addPair(
            'postedData',
            new \XLite\Core\Validator\HashArray
        );
        $data->addPair('sku', new \XLite\Core\Validator\String(), null, static::t('SKU'));
        $data->addPair('name', new \XLite\Core\Validator\String(true), null, static::t('Product Name'));
        $data->addPair('category_ids', new \XLite\Core\Validator\PlainArray(), $soft, static::t('Category'))
            ->setValidator(new \XLite\Core\Validator\Integer());
        $data->addPair('price', new \XLite\Core\Validator\Float(), null, static::t('Price'))
            ->setRange(0);
        $data->addPair('weight', new \XLite\Core\Validator\Float(), null, static::t('Weight'))
            ->setRange(0);
        $data->addPair('free_shipping', new \XLite\Core\Validator\Enum\Boolean(), null, static::t('Shippable'));
        $data->addPair('enabled', new \XLite\Core\Validator\Enum\Boolean(), null, static::t('Available for sale'));
        $data->addPair('meta_title', new \XLite\Core\Validator\String(), null, static::t('Product page title'));
        $data->addPair('brief_description', new \XLite\Core\Validator\String(), null, static::t('Brief descrption'));
        $data->addPair('description', new \XLite\Core\Validator\String(), null, static::t('Full description'));
        $data->addPair('meta_tags', new \XLite\Core\Validator\String(), null, static::t('Meta keywords'));
        $data->addPair('meta_desc', new \XLite\Core\Validator\String(), null, static::t('Meta description'));
        $data->addPair('clean_url', new \XLite\Core\Validator\String(), null, static::t('Clean URL'));

        return $validator;
    }

}
