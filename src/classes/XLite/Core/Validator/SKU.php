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

namespace XLite\Core\Validator;

/**
 * Product SKU
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class SKU extends \XLite\Core\Validator\AValidator
{
    protected $productId = null;

    /**
     * Constructor
     *
     * @param integer|null $productid Product identificator
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($productId = null)
    {
        if (isset($productId)) {

            $this->productId = (int) $productId;
        }
    }


    /**
     * Validate
     *
     * @param mixed $data Data
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function validate($data)
    {
        $data = $this->sanitize($data);

        $productSKU = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBySku($data);

        if (
            $productSKU
            && (
                isset($this->productId) && $productSKU->getProductId() !== $this->productId
                || !isset($this->productId)
            )
        ) {
            throw $this->throwError('SKU must be unique');
        }
    }

    /**
     * Sanitize
     *
     * @param mixed $data Daa
     *
     * @return mixed
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function sanitize($data)
    {
        return (string)$data;
    }

}
