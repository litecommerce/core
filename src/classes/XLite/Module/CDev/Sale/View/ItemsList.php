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

namespace XLite\Module\CDev\Sale\View;

/**
 * ItemsList
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class ItemsList extends \XLite\View\ItemsList\Product\Customer\ACustomer implements \XLite\Base\IDecorator
{
    /**
     * Return list of product labels
     *
     * @param \XLite\Model\Product $product The product to look for
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getLabels(\XLite\Model\Product $product)
    {
        $labels = parent::getLabels($product);

        if ($this->participateSale($product)) {

            $labels += array(
                'orange sale-price' => intval($product->getSalePercent()) . '% '
                    . \XLite\Core\Translation::getInstance()->translate('off')
            );
        }

        return $labels;
    }

    /**
     * Return products list
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getData(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        return $this->getOnlyEntities(
            parent::getData($cnd, $countOnly)
        );
    }

    /**
     * getPageData
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getPageData()
    {
        return $this->getOnlyEntities(
            parent::getPageData()
        );
    }

    /**
     * Return collection result from the mixed one.
     *
     * @param  mixed $data
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.18
     */
    protected function getOnlyEntities($data)
    {
        if (is_array($data)) {

            // Sanitize result array as it is contains the following values:
            // array(0 => Product object, 'cnt' => <counter>)
            // We should return array of product objects

            $result = array();

            foreach ($data as $row) {

                $result[] = is_array($row) ? $row[0] : $row;
            }

        } else {

            $result = $data;
        }

        return $result;
    }
}
