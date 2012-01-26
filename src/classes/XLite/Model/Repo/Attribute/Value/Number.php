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

namespace XLite\Model\Repo\Attribute\Value;

/**
 * Attribute value
 *
 * @see   ____class_see____
 * @since 1.0.16
 */
class Number extends \XLite\Model\Repo\Attribute\Value
{
    /**
     * Subclasses must define their own search criterions
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query object
     * @param integer                    $id           Attribute ID
     * @param array                      $data         Attribute value to search
     *
     * @return \Doctrine\ORM\Query\Expr\Andx
     * @see    ____func_see____
     * @since  1.0.16
     */
    protected function getExpressionPartForProductIDsByValuesQuery(\Doctrine\ORM\QueryBuilder $queryBuilder, $id, $data)
    {
        $result = null;

        if (is_array($data)) {
            $min = \Includes\Utils\ArrayManager::getIndex($data, 'min');
            $max = \Includes\Utils\ArrayManager::getIndex($data, 'max');

            if (is_numeric($min) || is_numeric($max)) {
                $result = new \Doctrine\ORM\Query\Expr\Andx();

                if (is_numeric($min)) {
                    $result->add('av.value >= :valueMin' . $id);
                    $queryBuilder->setParameter('valueMin' . $id, doubleval($data['min']));
                }

                if (is_numeric($max)) {
                    $result->add('av.value <= :valueMax' . $id);
                    $queryBuilder->setParameter('valueMax' . $id, doubleval($data['max']));
                }
            }
        }

        return $result;
    }
}
