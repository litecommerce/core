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

namespace XLite\Module\CDev\SalesTax\Model\Repo;

/**
 * Tax repository
 *
 */
class Tax extends \XLite\Model\Repo\ARepo
{
    /**
     * Get tax
     *
     * @return \XLite\Module\CDev\SalesTax\Model\Tax
     */
    public function getTax()
    {
        $tax = $this->createQueryBuilder()
            ->setMaxResults(1)
            ->getSingleResult();

        if (!$tax) {
            $tax = $this->createTax();
        }

        return $tax;
    }

    /**
     * Find active taxes
     *
     * @return array
     */
    public function findActive()
    {
        $list = $this->defineFindActiveQuery()->getResult();
        if (0 == count($list) && 0 == count($this->findAll())) {
            $this->createTax();
            $list = $this->defineFindActiveQuery()->getResult();
        }

        return $list;
    }

    /**
     * Define query for findActive() method
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindActiveQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('tr')
            ->leftJoin('t.rates', 'tr')
            ->andWhere('t.enabled = :true AND tr IS NOT NULL')
            ->setParameter('true', true);
    }

    /**
     * Create tax
     *
     * @return \XLite\Module\CDev\SalesTax\Model\Tax
     */
    protected function createTax()
    {
        $tax = new \XLite\Module\CDev\SalesTax\Model\Tax;
        $tax->setName('Sales tax');
        $tax->setEnabled(true);
        \XLite\Core\Database::getEM()->persist($tax);

        return $tax;
    }
}
