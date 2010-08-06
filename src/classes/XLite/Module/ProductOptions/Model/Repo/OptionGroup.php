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
 * @category   LiteCommerce
 * @package    XLite
 * @subpackage Model
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\ProductOptions\Model\Repo;

/**
 * Option group repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class OptionGroup extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Find all active option groups by product id 
     * 
     * @param integer $productId Product id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findActiveByProductId($productId)
    {
        $data = $this->defineActiveByProductIdQuery(intval($productId))
            ->getQuery()
            ->getResult();

        $data = $this->postprocessActiveByProductId($data, intval($productId));

        return $data;
    }

    /**
     * Define query for findActiveByProductId() method
     * 
     * @param integer $productId Product id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineActiveByProductIdQuery($productId)
    {
        return $this->createQueryBuilder()
            ->addSelect('options')
            ->leftJoin('o.options', 'options', 'WITH', 'options.enabled = :true')
            ->andWhere('o.product_id = :productId AND o.enabled = :true')
            ->setParameter('productId', $productId)
            ->setParameter('true', true);
    }

    /**
     * Postprocessing for findActiveByProductId() method
     * 
     * @param array   $data      Data
     * @param integer $productId Product id
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessActiveByProductId(array $data, $productId)
    {
        foreach ($data as $i => $item) {
            if (
                $item->getType() == \XLite\Module\ProductOptions\Model\OptionGroup::GROUP_TYPE
                && 0 == count($item->getOptions())
            ) {
                unset($data[$i]);
            }
        }

        return $data;
    }

    public function findOneByGroupIdAndProductId($groupId, $productId)
    {
        try {
            $group = $this->defineOneByGroupIdAndProductIdQuery($groupId, $productId)
                ->getQuery()
                ->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $group = null;
        }

        return $group;
    }

    /**
     * Define query for findActiveByProductId() method
     *
     * @param integer $groupId   Option group id
     * @param integer $productId Product id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineOneByGroupIdAndProductIdQuery($groupId, $productId)
    {
        return $this->createQueryBuilder()
            ->andWhere('o.group_id = :groupId AND o.product_id = :productId')
            ->setParameter('groupId', $groupId)
            ->setParameter('productId', $productId)
            ->setMaxResults(1);
    }
}

