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

namespace XLite\Module\CDev\FeaturedProducts\Model\Repo;

/**
 * Featured Product repository
 *
 */
class FeaturedProduct extends \XLite\Model\Repo\ARepo
{
    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'order_by';


    /**
     * Get featured products list
     *
     * @param integer $categoryId Category ID
     *
     * @return array(\XLite\Module\CDev\FeaturedProducts\Model\FeaturedProduct) Objects
     */
    public function getFeaturedProducts($categoryId)
    {
        return $this->findByCategoryId($categoryId);
    }


    /**
     * Find by type
     *
     * @param integer $categoryId Category ID
     *
     * @return array
     */
    protected function findByCategoryId($categoryId)
    {
        if (!is_numeric($categoryId) || 0 >= $categoryId) {
            $categoryId = \XLite\Core\Database::getRepo('\XLite\Model\Category')->getRootCategoryId();
        }

        return $this->defineByCategoryIdQuery($categoryId)->getResult();
    }

    /**
     * Define query builder for findByCategoryId()
     *
     * @param integer $categoryId Category ID
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineByCategoryIdQuery($categoryId)
    {
        $qb = $this->createQueryBuilder('f')
            ->innerJoin('f.product', 'p')
            ->andWhere('f.category = :categoryId')
            ->setParameter('categoryId', $categoryId);

        return \XLite\Core\Database::getRepo('XLite\Model\Product')->assignExternalEnabledCondition($qb, 'p');
    }
}
