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
    /**
     * Product Id (saved)
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $productId;

    /**
     * Constructor
     *
     * @param integer $productId Product identificator OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function __construct($productId = null)
    {
        parent::__construct();

        if (isset($productId)) {
            $this->productId = intval($productId);
        }
    }

    /**
     * Validate
     *
     * @param mixed $data Data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function validate($data)
    {
        if (!\XLite\Core\Converter::getInstance()->isEmptyString($data)) {
            $entity = \XLite\Core\Database::getRepo('XLite\Model\Product')->findOneBySku($this->sanitize($data));

            // DO NOT use "!==" here
            if ($entity && (empty($this->productId) || $entity->getProductId() != $this->productId)) {
                $this->throwSKUError();
            }
        }
    }

    /**
     * Sanitize
     *
     * @param mixed $data Data
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function sanitize($data)
    {
        return substr($data, 0, \XLite\Core\Database::getRepo('XLite\Model\Product')->getFieldInfo('sku', 'length'));
    }

    /**
     * Wrapper
     *
     * @return void
     * @throws \XLite\Core\Validator\Exception
     * @see    ____func_see____
     * @since  1.0.24
     */
    protected function throwSKUError()
    {
        throw $this->throwError('SKU must be unique');
    }
}
