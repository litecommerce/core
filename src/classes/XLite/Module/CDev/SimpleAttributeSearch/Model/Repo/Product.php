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
 * @since     1.0.15
 */

namespace XLite\Module\CDev\SimpleAttributeSearch\Model\Repo;

/**
 * Product 
 *
 * @see   ____class_see____
 * @since 1.0.15
 */
class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Allowable search params
     */
    const P_ATTRIBUTES = 'attributes';

    /**
     * Return list of handling search params
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getHandlingSearchParams()
    {
        $list = parent::getHandlingSearchParams();
        $list[] = static::P_ATTRIBUTES;

        return $list;
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $attributes   Attributes list
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function prepareCndAttributes(\Doctrine\ORM\QueryBuilder $queryBuilder, array $attributes)
    {
    }
}
