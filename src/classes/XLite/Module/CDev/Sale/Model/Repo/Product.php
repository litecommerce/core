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

namespace XLite\Module\CDev\Sale\Model\Repo;

/**
 * The Product model repository extension
 *
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
     * @param boolean                    $countOnly    Count only flag
     *
     * @return void
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

        $queryBuilder->andWhere('p.participateSale = :participateSale')
            ->andWhere($cnd)
            ->setParameter('participateSale', $value)
            ->setParameter('discountTypePercent', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PERCENT)
            ->setParameter('discountTypePercent', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PERCENT)
            ->setParameter('discountTypePrice', \XLite\Module\CDev\Sale\Model\Product::SALE_DISCOUNT_TYPE_PRICE);
    }

    /**
     * Define calculated price definition DQL
     *
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder Query builder
     * @param string                                  $alias        Main alias
     *
     * @return string
     */
    protected function defineCalculatedPriceDQL(\XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder, $alias)
    {
        $dql = parent::defineCalculatedPriceDQL($queryBuilder, $alias);

        $queryBuilder->SetParameter('saleDiscountTypePercent', \XLite\Model\Product::SALE_DISCOUNT_TYPE_PERCENT);

        return 'IF(' . $alias . '.participateSale = 1,'
            . ' IF(' . $alias . '.discountType = :saleDiscountTypePercent,'
            . ' ' . $dql . ' * (1 - ' . $alias . '.salePriceValue / 100),'
            . ' ' . $alias . '.salePriceValue'
            . '),'
            . ' ' . $dql . ')';
    }

    // }}}
}
