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

namespace XLite\Module\CDev\ProductComparison\Controller\Customer;

/**
 * Product comparison
 *
 */
class ProductComparison extends \XLite\Controller\Customer\ACustomer
{
    /**
     * Controller parameters
     *
     * @var array
     */
    protected $params = array('target');

    /**
     * Product comparison delete 
     *
     * @return void
     */
    protected function doActionDelete()
    {
        $id = \XLite\Core\Request::getInstance()->product_id;
        \XLite\Module\CDev\ProductComparison\Core\Data::getInstance()->deleteProductId($id);
        \XLite\Core\Event::updateProductComparison(
            array(
                'productId' => $id,
                'action'    => 'delete',
                'title'     => $this->getTitle()
            )
        );
    }

    /**
     * Product comparison add
     *
     * @return void
     */
    protected function doActionAdd()
    {
        $id = \XLite\Core\Request::getInstance()->product_id;
        \XLite\Module\CDev\ProductComparison\Core\Data::getInstance()->addProductId($id);
        \XLite\Core\Event::updateProductComparison(
            array(
                'productId' => $id,
                'action'    => 'add',
                'title'     => $this->getTitle()
            )
        );
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return static::t(
            'X products selected',
            array(
                'count' => \XLite\Module\CDev\ProductComparison\Core\Data::getInstance()->getProductsCount()
            )
        );
    }

}
