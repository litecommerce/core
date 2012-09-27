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

namespace XLite\Module\CDev\VAT\Model\Repo;

/**
 * Product repository
 * 
 */
class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Assign price range-based search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param float                      $min          Minimum
     * @param float                      $max          Maximum
     *
     * @return void
     */
    protected function assignPriceRangeCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $min, $max)
    {
        if (\XLite::isAdminZone()) {
            parent::assignPriceRangeCondition($queryBuilder, $min, $max);

        } else {

            $queryBuilder->leftJoin('p.classes', 'classes', 'WITH');
            $cnd = \XLite\Module\CDev\VAT\Logic\Product\Tax::getInstance()
                ->getSearchPriceCondition('p.price', 'classes');

            if (isset($min)) {
                $queryBuilder->andWhere($cnd . ' > :minPrice')
                    ->setParameter('minPrice', doubleval($min));
            }

            if (isset($max)) {
                $queryBuilder->andWhere($cnd . ' < :maxPrice')
                    ->setParameter('maxPrice', doubleval($max));
            }
        }
    }
}
