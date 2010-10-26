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

// TODO - requires the multiple inheritance
// TODO - must also extends \XLite\Model\Repo\the \XLite\Model\Repo\Base\Searchable

/**
 * The "product" model repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Product extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Allowable search params 
     */

    const P_SKU               = 'SKU';
    const P_CATEGORY_ID       = 'categoryId';
    const P_SUBSTRING         = 'substring';
    const P_SEARCH_IN_SUBCATS = 'searchInSubcats';
    const P_ORDER_BY          = 'orderBy';
    const P_LIMIT             = 'limit';
    const P_INCLUDING         = 'including';    
    const P_BY_TITLE          = 'by_title';
    const P_BY_DESCR          = 'by_descr';
    const P_BY_SKU            = 'by_sku';

    const INCLUDING_ALL = 'all';
    const INCLUDING_ANY = 'any';
    const INCLUDING_PHRASE = 'phrase';

    /**
     * currentSearchCnd 
     * 
     * @var    \XLite\Core\CommonCell
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $currentSearchCnd = null;

    /**
     * Search inner cache
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $searchCache = array();

    /**
     * Return list of handling search params 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_SKU,
            self::P_CATEGORY_ID,
            self::P_SUBSTRING,
            self::P_ORDER_BY,
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
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * List of fields to use in search by substring TODO !REFACTOR!
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubstringSearchFields()
    {
        $result = array();

        if (
            'Y' !== $this->currentSearchCnd->{self::P_BY_TITLE}
            && 'Y' !== $this->currentSearchCnd->{self::P_BY_DESCR}
            && 'Y' !== $this->currentSearchCnd->{self::P_BY_SKU}
        ) {

            $result = array(
                'p.sku',
                'translations.name',
                'translations.brief_description',
                'translations.description',
            );

        } else {

            if ('Y' === $this->currentSearchCnd->{self::P_BY_TITLE}) {
                $result = array(
                    'translations.name',
                );
            }

            if ('Y' === $this->currentSearchCnd->{self::P_BY_DESCR}) {
                $result = array_merge(
                    $result,
                    array(
                        'translations.brief_description',
                        'translations.description',
                    )
                );
            }

            if ('Y' === $this->currentSearchCnd->{self::P_BY_SKU}) {
                $result = array_merge(
                    $result,
                    array(
                        'p.sku',
                    )
                );
            }

        }

        return $result;
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
    protected function prepareCndSKU(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->andWhere('p.sku LIKE :sku')
            ->setParameter('sku', '%' . $value . '%');
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
    protected function prepareCndCategoryId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->leftJoin('p.category_products', 'cp')
            ->addOrderBy('cp.orderby');

        if (empty($this->currentSearchCnd->{self::P_SEARCH_IN_SUBCATS})) {
            $queryBuilder->andWhere('cp.category_id = :categoryId')
                ->setParameter('categoryId', $value);
        } else {
            $queryBuilder->leftJoin('cp.category', 'c');

            \XLite\Core\Database::getRepo('\XLite\Model\Category')->addSubTreeCondition($queryBuilder, $value);
        }
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
    protected function prepareCndSubstring(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {

            $including = $this->currentSearchCnd->{self::P_INCLUDING};

            $searchWords = $this->getSearchWords($value);

            $cnd = new \Doctrine\ORM\Query\Expr\Orx();

            $isAll = (self::INCLUDING_ALL === $including);

            if (
                empty($including)
                || empty($searchWords)
                || self::INCLUDING_PHRASE === $including
            ) {
                // EXACT PHRASE method (or if NONE is selected)
                foreach ($this->getSubstringSearchFields() as $field) {
                    $cnd->add($field . ' LIKE :substring');
                }

                $queryBuilder->setParameter('substring', '%' . $value . '%');

            } else {

                foreach ($this->getSubstringSearchFields() as $field) {

                    if ($isAll) {

                        $fieldCnd = new \Doctrine\ORM\Query\Expr\Andx();

                    }

                    foreach ($searchWords as $index => $word) {

                        $fieldWhere = $field . ' LIKE :word' . $index;

                        if ($isAll) {
                            // Collect AND expressions for one field
                            $fieldCnd->add($fieldWhere);

                        } else {
                            // Collect OR expressions
                            $cnd->add($field . ' LIKE :word' . $index);

                        }

                        $queryBuilder->setParameter('word' . $index, '%' . $word . '%');
                    }

                    if ($isAll) {
                        // Add AND expression into OR main expression 
                        // (
                        //    ((field1 LIKE word1) AND (field1 LIKE word2)) 
                        //        OR 
                        //    ((field2 LIKE word1) AND (field2 LIKE word2))
                        // ) 
                        $cnd->add($fieldCnd);

                    }
                }
            }

            $queryBuilder->andWhere($cnd);

        }
    }

    /**
     * Return search words for "All" and "Any" INCLUDING parameter
     * 
     * @param string $value search string
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchWords($value)
    {
        $value = trim($value);

        $result = array();

        if (preg_match_all('/"([^"]+)"/', $value, $match)) {

            $result = $match[1];

            $value = str_replace($match[0], '', $value);

        }

        return array_merge(
            (array)$result,
            array_map(
                'trim',
                explode(' ', $value)
            )
        );
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
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        list($sort, $order) = $value;

        $queryBuilder->addOrderBy($sort, $order);
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
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value)); 
    }

    /**
     * Call corresponded method to handle a search condition
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
        if ($this->isSearchParamHasHandler($key)) {
            $this->{'prepareCnd' . ucfirst($key)}($queryBuilder, $value);
        } else {
            // TODO - add logging here
        }
    }


    /**
     * Common search
     * 
     * @param \XLite\Core\CommonCell $cnd       search condition
     * @param bool                   $countOnly return items list or only its size
     *  
     * @return \Doctrine\ORM\PersistentCollection|int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $cndHash = serialize($cnd);

        if (empty($this->searchCache[$cndHash])) {

            $queryBuilder = $this->createQueryBuilder();

            $this->currentSearchCnd = $cnd;

            foreach ($this->currentSearchCnd as $key => $value) {
                $this->callSearchConditionHandler($value, $key, $queryBuilder);
            }

            if ($countOnly) {
                $queryBuilder->select('COUNT(p.product_id)');
            }

            $this->searchCache[$cndHash] = $queryBuilder->getQuery()->getResult();

        }

        return $countOnly
            ? count($this->searchCache[$cndHash])
            : $this->searchCache[$cndHash];
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

        $result->groupBy('p.product_id');

        return $result;
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
    public function createPureQueryBuilder($alias = null)
    {
        $result = parent::createPureQueryBuilder($alias);

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
     * @return \XLite_Model_Product
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCleanUrl($url)
    {
        try {
            $result = $this->createQueryBuilder()
                ->andWhere('p.clean_url = :url')
                ->setMaxResults(1)
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
