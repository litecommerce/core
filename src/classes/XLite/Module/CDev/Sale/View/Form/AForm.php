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

namespace XLite\Module\CDev\Sale\View\Form;

/**
 * "Set the sale price" dialog form class
 *
 */
class AForm extends \XLite\View\Form\AForm implements \XLite\Base\IDecorator
{
    /**
     * Set validators pairs for products data. Sale structure.
     *
     * @param mixed &$data Data
     *
     * @return void
     */
    protected function setSaleDataValidators(&$data)
    {
        if ($this->getPostedData('participateSale')) {
            switch ($this->getPostedData('discountType')) {

                case \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PRICE:
                    $data->addPair('salePriceValue', new \XLite\Core\Validator\Float(), null, 'Sale price')
                        ->setRange(0);
                    break;

                case \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PERCENT:
                    $data->addPair('salePriceValue', new \XLite\Core\Validator\Integer(), null, 'Percent off')
                        ->setRange(1, 100);
                    break;

                default:
            }
        }
    }
}
