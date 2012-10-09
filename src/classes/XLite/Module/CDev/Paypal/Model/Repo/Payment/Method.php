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

namespace XLite\Module\CDev\Paypal\Model\Repo\Payment;

/**
 * Payment method model repository
 * 
 */
class Method extends \XLite\Model\Repo\Payment\Method implements \XLite\Base\IDecorator
{
    /**
     * Find payment methods by specified type for dialog 'Add payment method'
     *
     * @param string $type Payment method type
     *
     * @return \Doctrine\Common\Collection\Collection
     **/
    public function findForAdditionByType($type)
    {
        $result = array();
        
        $methods = parent::findForAdditionByType($type);

        foreach ($methods as $m) {
            $result[] = $m[0];
        }

        return $result;
    }

    /**
     * Define query for findAdditionByType()
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder
     * 
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function addOrderByForAdditionByTypeQuery($qb)
    {
        $qb->addSelect('LOCATE(:modulePrefix, m.class) module_prefix')
            ->addOrderBy('module_prefix', 'desc')
            ->setParameter('modulePrefix', 'Module\\CDev\\Paypal');

        return parent::addOrderByForAdditionByTypeQuery($qb);
    }
}
