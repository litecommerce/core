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

namespace XLite\Module\CDev\Sale\Model\Repo;

/**
 * The Product model repository extension
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Product extends \XLite\Model\Repo\Product implements \XLite\Base\IDecorator
{
    /**
     * Allowable search params
     */
    const P_PARTICIPATE_SALE = 'participateSale';

    /**
     * Name of the calculated field - percent value.
     */
    const PERCENT_CALCULATED_FIELD = 'percentValueCalculated';


    // {{{ Search functionallity extension

    /**
     * Add arrivalDate to the list of handling search params
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHandlingSearchParams()
    {
        $params = parent::getHandlingSearchParams();

        $params[] = self::P_PARTICIPATE_SALE;

        return $params;
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndParticipateSale(\Doctrine\ORM\QueryBuilder $queryBuilder, $value, $countOnly)
    {
        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        $pricePercentCnd = new \Doctrine\ORM\Query\Expr\Andx();

        $pricePercentCnd->add('p.discountType = :discountTypePercent');
        $pricePercentCnd->add('p.salePriceValue > 0');

        $priceAbsoluteCnd = new \Doctrine\ORM\Query\Expr\Andx();

        $priceAbsoluteCnd->add('p.discountType = :discountTypePrice');
        $priceAbsoluteCnd->add('p.price > p.salePriceValue');

        $cnd->add($pricePercentCnd);
        $cnd->add($priceAbsoluteCnd);

        if (!$countOnly) {
            $queryBuilder->addSelect(
                'if(p.discountType = :discountTypePercent, p.salePriceValue, 100 - 100 * p.salePriceValue / p.price) ' . static::PERCENT_CALCULATED_FIELD
            );
        }

        $queryBuilder
            ->andWhere('p.participateSale = :participateSale')
            ->andWhere($cnd)
            ->setParameter('participateSale', $value)
            ->setParameter('discountTypePercent', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PERCENT)
            ->setParameter('discountTypePercent', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PERCENT)
            ->setParameter('discountTypePrice', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PRICE);
    }

    /**
     * Search result routine.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder routine
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function searchResult(\Doctrine\ORM\QueryBuilder $qb)
    {
        return $qb->getOnlyEntities();
    }

    // }}}
}
