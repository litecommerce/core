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

namespace XLite\Model\Repo;

/**
 * The "product" model repository
 * 
 * @package    XLite
 * @see        ____class_see____
 * @since      3.0.0
 */
class Product extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Allowable search params 
     */

    const P_CATEGORY_ID = 'categoryId';
    const P_SUBSTRING   = 'substring';
    const P_LIMIT       = 'limit';
    

    /**
     * Return list of possible search params 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getAllowedSearchParams()
    {
        return array(
            self::P_CATEGORY_ID,
            self::P_SUBSTRING,
            self::P_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     * 
     * @param string $param name of param to check
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isSearchParamAllowed($param)
    {
        return in_array($param, $this->getAllowedSearchParams());
    }


    /**
     * Prepare certain search condition 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param mixed                      $value        condition data
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareSearchConditionCategoryId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder
            ->innerJoin('p.category_products', 'cp')
            ->andWhere('cp.category_id = :categoryId')
            ->setParameter('categoryId', $value);
    }

    /**
     * Prepare certain search condition 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareSearchConditionSubstring(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        foreach (array('name', 'brief_description', 'description', 'sku') as $field) {
            $cnd->add('p.' . $field . ' LIKE :substring');
        }

        $queryBuilder
            ->andWhere($cnd)
            ->setParameter('substring', '%' . $value , '%');
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param array                      $value        condition data
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareSearchConditionLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value)); 
    }

    /**
     * Call corresponded method to handle a serch condition
     * 
     * @param mixed                      $value        condition data
     * @param string                     $key          condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder)
    {
        if ($this->isSearchParamAllowed($key)) {
            $this->{'prepareSearchCondition' . ucfirst($key)}($queryBuilder, $value);
        } else {
            // TODO - add logging here
        }
    }


    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd search condition
     *
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function searchCommon(\XLite\Core\CommonCell $cnd)
    {
        $queryBuilder = $this->createQueryBuilder();
        $cnd = $cnd->getData();

        array_walk($cnd, array($this, 'callSearchConditionHandler'), $queryBuilder);

        return $queryBuilder->getQuery()->getResult();
    }

    /**
     * Common search
     * 
     * @param array $params search params hash
     *  
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(array $params = array())
    {
        return $this->searchCommon(new \XLite\Core\CommonCell($params));
    }


    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createQueryBuilder($alias = null)
    {
        $result = parent::createQueryBuilder($alias);

        if (!\XLite::isAdminZone()) {
            $result->andWhere('p.enabled = :enabled')->setParameter('enabled', true);
        }

        return $result;
    }

    /**
     * Find product by clean URL
     * TODO - to revise
     * 
     * @param string $url Clean URL
     *  
     * @return XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCleanUrl($url)
    {
        try {
            $result = $this->createQueryBuilder()
                ->andWhere('p.clean_url = :url')
                ->setParameter('url', $url)
                ->getQuery()
                ->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $result = null;
            // TODO - add logging here
        }

        return $result;
    }
}
