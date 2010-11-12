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
class Product extends \XLite\Model\Repo\Base\I18n implements \XLite\Base\IREST
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
    const P_BY_TITLE          = 'byTitle';
    const P_BY_DESCR          = 'byDescr';
    const P_BY_SKU            = 'bySKU';

    const INCLUDING_ALL     = 'all';
    const INCLUDING_ANY     = 'any';
    const INCLUDING_PHRASE  = 'phrase';

    const TITLE_FIELD       = 'translations.name';
    const BRIEF_DESCR_FIELD = 'translations.brief_description';
    const DESCR_FIELD       = 'translations.description';
    const SKU_FIELD         = 'p.sku';

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
        $conditionsBy = $this->getConditionBy();

        $allEmpty = true;

        foreach ($conditionsBy as $conditionBy) {
            if ('Y' === $this->currentSearchCnd->{$conditionBy}) {
                $allEmpty = false;
            }
        }

        // if ALL parameters is FALSE then we search by ALL parameters
        if ($allEmpty) {
            foreach ($conditionsBy as $conditionBy) {
                $this->currentSearchCnd->{$conditionBy} = 'Y';
            }
        }

        $result = array();

        foreach ($conditionsBy as $conditionBy) {

            $conditionFields = ('Y' === $this->currentSearchCnd->{$conditionBy})
                ? $this->{'getSubstringSearchFields' . ucfirst($conditionBy)}()
                : array();

            $result = array_merge($result, $conditionFields);
        }

        return $result;
    }

    /**
     * Return conditions parameters that are responsible for substring set of fields.
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditionBy()
    {
        return array(
            self::P_BY_TITLE,
            self::P_BY_DESCR,
            self::P_BY_SKU,
        );
    }

    /**
     * Return fields set for title search
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubstringSearchFieldsByTitle()
    {
        return array(
            self::TITLE_FIELD,
        );
    }

    /**
     * Return fields set for description search
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubstringSearchFieldsByDescr()
    {
        return array(
            self::BRIEF_DESCR_FIELD,
            self::DESCR_FIELD,
        );
    }

    /**
     * Return fields set for SKU search
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getSubstringSearchFieldsBySKU()
    {
        return array(
            self::SKU_FIELD,
        );
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

            if (!\XLite\Core\Database::getRepo('XLite\Model\Category')->addSubTreeCondition($queryBuilder, $value)) {
                // TODO - add throw exception
            }
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

            $including = empty($including) ? self::INCLUDING_PHRASE : $including;

            $cnd = $this->{'getCndSubstring' . ucfirst($including)} ($queryBuilder, $value);

            $queryBuilder->andWhere($cnd);

        }
    }

    /**
     * Prepare certain search condition (EXACT PHRASE method)
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *  
     * @return \Doctrine\ORM\Query\Expr\Base condition class
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCndSubstringPhrase(\Doctrine\ORM\QueryBuilder $queryBuilder, $value) 
    {
        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        // EXACT PHRASE method (or if NONE is selected)
        foreach ($this->getSubstringSearchFields() as $field) {
            $cnd->add($field . ' LIKE :substring');
        }

        $queryBuilder->setParameter('substring', '%' . $value . '%');

        return $cnd;
    }

    /**
     * Prepare certain search condition (ALL WORDS method)
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *  
     * @return \Doctrine\ORM\Query\Expr\Base condition class
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCndSubstringAll(\Doctrine\ORM\QueryBuilder $queryBuilder, $value) 
    {
        $searchWords = $this->getSearchWords($value);

        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        foreach ($this->getSubstringSearchFields() as $field) {

            $fieldCnd = new \Doctrine\ORM\Query\Expr\Andx();

            foreach ($searchWords as $index => $word) {

                // Collect AND expressions for one field
                $fieldCnd->add($field . ' LIKE :word' . $index);

                $queryBuilder->setParameter('word' . $index, '%' . $word . '%');

            }

            // Add AND expression into OR main expression
            // (
            //    ((field1 LIKE word1) AND (field1 LIKE word2))
            //        OR
            //    ((field2 LIKE word1) AND (field2 LIKE word2))
            // )
            $cnd->add($fieldCnd);
        }

        return $cnd;
    }

    /**
     * Prepare certain search condition for substring (ANY WORDS method)
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder to prepare
     * @param string                     $value        condition data
     *  
     * @return \Doctrine\ORM\Query\Expr\Base condition class
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getCndSubstringAny(\Doctrine\ORM\QueryBuilder $queryBuilder, $value) 
    {
        $searchWords = $this->getSearchWords($value);

        $cnd = new \Doctrine\ORM\Query\Expr\Orx();

        foreach ($this->getSubstringSearchFields() as $field) {

            foreach ($searchWords as $index => $word) {

                // Collect OR expressions
                $cnd->add($field . ' LIKE :word' . $index);

                $queryBuilder->setParameter('word' . $index, '%' . $word . '%');

            }
        }

        return $cnd;
    }

    /**
     * Return search words for "All" and "Any" INCLUDING parameter
     * 
     * @param string $value search string
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * Adds additional condition to the query for checking if product is enabled
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder query builder object
     * @param string                     $alias        entity alias
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addEnabledCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if (!\XLite::isAdminZone()) {
            $queryBuilder
                ->andWhere(($alias ?: $queryBuilder->getRootAlias()) . '.enabled = :enabled')
                ->setParameter('enabled', true);
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
        $queryBuilder = $this->createQueryBuilder();

        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        if ($countOnly) {
            $queryBuilder->select('COUNT(p.product_id)');
        }

        $result = $queryBuilder->getQuery()->getResult();

        return $countOnly ? count($result) : $result;
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
        $this->addEnabledCondition($result = parent::createQueryBuilder($alias), $alias);

        if (!\XLite::isAdminZone()) {
            $result->andWhere('p.enabled = :enabled')->setParameter('enabled', true);
        }

        $result->groupBy('p.product_id');

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

    /**
     * Get REST entity names 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRESTNames()
    {
        return array (
            'product',
        );
    }

    /**
     * Get product data as REST 
     * 
     * @param integer $id Product id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getProductREST($id)
    {
        $product = $this->find($id);

        $data = null;

        if ($product) {
            foreach ($this->_class->fieldNames as $name) {
                $mname = 'get' . \XLite\Core\Converter::convertToCamelCase($name);
                $data[$name] = $product->$mname();
            }

            $data['name'] = $product->getName();
            $data['description'] = $product->getDescription();
        }

        return $data;
    }
}
