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

namespace XLite\Module\CDev\Sale\View;

/**
 * Viewer
 *
 */
abstract class AView extends \XLite\View\AView implements \XLite\Base\IDecorator
{
    /**
     * Register CSS files
     *
     * @return array
     */
    public function getCSSFiles()
    {
        $list = parent::getCSSFiles();

        $list[] = 'modules/CDev/Sale/css/lc.css';

        return $list;
    }

    /**
     * Return sale percent value
     *
     * @param \XLite\Model\Product $product Product model
     *
     * @return integer
     */
    protected function getSalePercent(\XLite\Model\Product $product)
    {
        return intval($product->getSalePercent());
    }

    /**
     * Return sale participation flag
     *
     * @param \XLite\Model\Product $product Product model
     *
     * @return boolean
     */
    protected function participateSale(\XLite\Model\Product $product)
    {
        return $product->getParticipateSale() && ($product->getSalePrice() < $product->getPrice());
    }
}
