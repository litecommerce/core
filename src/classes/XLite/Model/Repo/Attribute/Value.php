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

namespace XLite\Model\Repo\Attribute;

/**
 * Attribute value
 *
 * @see   ____class_see____
 * @since 1.0.15
 */
class Value extends \XLite\Model\Repo\ARepo
{
    /**
     * Searxh product IDs by their attribute values
     *
     * @param array $attributes Attribute values list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.16
     */
    public function getProductIDsByValues(array $attributes)
    {
        $result = array();
        $query  = $this->defineProductIDsByValuesQuery($attributes);

        if (isset($query)) {
            $result = $query->getArrayResult();

            $result = empty($result) 
                ? array('0') 
                : \Includes\Utils\ArrayManager::getArraysArrayFieldValues($result, 'productId');
        }

        return $result;
    }

    /**
     * Define query to the corresponded method
     *
     * @param array $attributes Attribute values list
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function defineProductIDsByValuesQuery(array $attributes)
    {
        $queryBuilder = $this->createQueryBuilder('av')->select('DISTINCT(av.productId)');
        $expressions = array();

        foreach ($attributes as $id => $data) {
            $exprPart = $this->getExpressionPartForProductIDsByValuesQuery($queryBuilder, $id, $data);

            if (isset($exprPart)) {
                $exprPart->add('av.attributeId = :attributeId' . $id);
                $queryBuilder->setParameter('attributeId' . $id, $id);

                $expressions[] = $exprPart;
            }
        }

        return empty($expressions)
            ? null
            : $queryBuilder
                ->andWhere(new \Doctrine\ORM\Query\Expr\Orx($expressions))
                ->addGroupBy('av.productId')
                ->andHaving('COUNT(av.productId) = :cnt')
                ->setParameter('cnt', count($expressions));
    }

    /**
     * Subclasses should define their own search criterions
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query object
     * @param integer                    $id           Attribute ID
     * @param mixed                      $data         Attribute value to search
     *
     * @return \Doctrine\ORM\Query\Expr\Base
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getExpressionPartForProductIDsByValuesQuery(\Doctrine\ORM\QueryBuilder $queryBuilder, $id, $data)
    {
        return null;
    }
}
