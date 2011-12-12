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
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

// TODO - requires the multiple inheritance
// TODO - must also extends \XLite\Model\Repo\the \XLite\Model\Repo\Base\Searchable

/**
 * The "product" model repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Product extends \XLite\Model\Repo\Base\I18n implements \XLite\Base\IREST
{
    /**
     * Allowable search params
     */

    const P_SKU               = 'SKU';
    const P_CATEGORY_ID       = 'categoryId';
    const P_SUBSTRING         = 'substring';
    const P_PRICE             = 'price';
    const P_SEARCH_IN_SUBCATS = 'searchInSubcats';
    const P_INVENTORY         = 'inventory';
    const P_ORDER_BY          = 'orderBy';
    const P_LIMIT             = 'limit';
    const P_INCLUDING         = 'including';

    const P_BY_TITLE          = 'byTitle';
    const P_BY_DESCR          = 'byDescr';
    const P_BY_SKU            = 'bySKU';

    const INCLUDING_ALL     = 'all';
    const INCLUDING_ANY     = 'any';
    const INCLUDING_PHRASE  = 'phrase';

    const INV_ALL = 'all';
    const INV_LOW = 'low';
    const INV_OUT = 'out';

    const TITLE_FIELD       = 'translations.name';
    const BRIEF_DESCR_FIELD = 'translations.brief_description';
    const DESCR_FIELD       = 'translations.description';
    const SKU_FIELD         = 'p.sku';

    /**
     * currentSearchCnd
     *
     * @var   \XLite\Core\CommonCell
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $currentSearchCnd = null;

    /**
     * Alternative record identifiers
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $alternativeIdentifier = array(
        array('sku'),
    );


    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder);
        }

        if ($countOnly) {
            $queryBuilder->select('COUNT(DISTINCT p.product_id)');
            $result = intval($queryBuilder->getSingleScalarResult());

        } else {
            $result = $queryBuilder->getResult();
        }

        return $result;
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function createQueryBuilder($alias = null)
    {
        $queryBuilder = parent::createQueryBuilder($alias);

        $alias = $alias ?: $queryBuilder->getRootAlias();
        $this->addEnabledCondition($queryBuilder, $alias);
        $this->addDateCondition($queryBuilder, $alias);

        return $queryBuilder;
    }

    /**
     * Find product by clean URL
     * TODO - to revise
     *
     * @param string $url Clean URL
     *
     * @return \XLite_Model_Product
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findOneByCleanURL($url)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.clean_url = :url')
            ->setParameter('url', $url)
            ->setMaxResults(1)
            ->getSingleResult();
    }

    /**
     * Count last updated products
     * 
     * @param integer $limit Time limit
     *  
     * @return integer
     * @see    ____func_see____
     * @since  1.0.14
     */
    public function countLastUpdated($limit)
    {
        return intval($this->defineCountLastUpdatedQuery($limit)->getSingleScalarResult());
    }

    /**
     * Get REST entity names
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getProductREST($id)
    {
        $product = $this->find($id);

        $data = null;

        if ($product) {
            foreach ($this->_class->fieldNames as $name) {
                $mname = 'get' . \XLite\Core\Converter::convertToCamelCase($name);
                // $maname assebmled from 'get' + \XLite\Core\Converter::convertToCamelCase() method
                $data[$name] = $product->$mname();
            }

            $data['name'] = $product->getName();
            $data['description'] = $product->getDescription();
        }

        return $data;
    }

    /**
     * Get import iterator 
     * 
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function getImportIterator()
    {
        return $this->defineImportQuery()->iterate();
    }

    /**
     * Define import querty 
     * 
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.10
     */
    protected function defineImportQuery()
    {
        return $this->createPureQueryBuilder('p');
    }

    /**
     * Define query for countLastUpdated()
     * 
     * @param integer $limit Time limit
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.14
     */
    protected function defineCountLastUpdatedQuery($limit)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.product_id) cnt')
            ->andWhere('p.updateDate > :time')
            ->setParameter('time', $limit)
            ->setMaxResults(1);
    }

    /**
     * Return list of handling search params
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getHandlingSearchParams()
    {
        return array(
            self::P_SKU,
            self::P_CATEGORY_ID,
            self::P_SUBSTRING,
            self::P_PRICE,
            self::P_INVENTORY,
            self::P_ORDER_BY,
            self::P_LIMIT,
        );
    }

    /**
     * Check if param can be used for search
     *
     * @param string $param Name of param to check
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * List of fields to use in search by substring TODO !REFACTOR!
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param mixed                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndSKU(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->andWhere('p.sku LIKE :sku')
            ->setParameter('sku', '%' . $value . '%');
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param mixed                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndCategoryId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $queryBuilder->innerJoin('p.categoryProducts', 'cp')
            ->innerJoin('cp.category', 'c')
            ->addOrderBy('cp.orderby');

        if (empty($this->currentSearchCnd->{self::P_SEARCH_IN_SUBCATS})) {

            $queryBuilder->andWhere('c.category_id = :categoryId')
                ->setParameter('categoryId', $value);

        } elseif (!\XLite\Core\Database::getRepo('XLite\Model\Category')->addSubTreeCondition($queryBuilder, $value)) {

            // TODO - add throw exception

        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndSubstring(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (!empty($value)) {
            $including = $this->currentSearchCnd->{self::P_INCLUDING};
            $including = in_array($including, $this->getAllowedIncludingValues()) ? $including : self::INCLUDING_PHRASE;

            $cnd = $this->{'getCndSubstring' . ucfirst($including)} ($queryBuilder, $value);

            $queryBuilder->andWhere($cnd);

        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndPrice(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (is_array($value) && 2 == count($value)) {
            $min = trim(array_shift($value));
            $max = trim(array_shift($value));

            $min = (0 == strlen($min) || !is_numeric($min)) ? null : doubleval($min);
            $max = (0 == strlen($max) || !is_numeric($max)) ? null : doubleval($max);

            $this->assignPriceRangeCondition($queryBuilder, $min, $max);
        }
    }

    /**
     * Assign prica range-based search condition 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param float                      $min          Minimum
     * @param float                      $max          Maximum
     *  
     * @return void
     * @see    ____func_see____
     * @since  1.0.8
     */
    protected function assignPriceRangeCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $min, $max)
    {
        if (isset($min)) {
            $queryBuilder->andWhere('p.price > :minPrice')
                ->setParameter('minPrice', doubleval($min));
        }

        if (isset($max)) {
            $queryBuilder->andWhere('p.price < :maxPrice')
                ->setParameter('maxPrice', doubleval($max));
        }
    }

    /**
     * Returns array of allowed values for 'includes' input variable
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.1
     */
    protected function getAllowedIncludingValues()
    {
        return array(self::INCLUDING_ALL, self::INCLUDING_ANY, self::INCLUDING_PHRASE);
    }

    /**
     * Prepare certain search condition (EXACT PHRASE method)
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return \Doctrine\ORM\Query\Expr\Base Condition class
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return \Doctrine\ORM\Query\Expr\Base Condition class
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return \Doctrine\ORM\Query\Expr\Base Condition class
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param string $value Search string
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getSearchWords($value)
    {
        $value  = trim($value);
        $result = array();

        if (preg_match_all('/"([^"]+)"/', $value, $match)) {
            $result = $match[1];
            $value  = str_replace($match[0], '', $value);

        }

        return array_merge((array)$result, array_map('trim', explode(' ', $value)));
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param string                     $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndInventory(\Doctrine\ORM\QueryBuilder $queryBuilder, $value = self::INV_ALL)
    {
        $queryBuilder->innerJoinInventory();

        if (in_array($value, array(self::INV_LOW, self::INV_OUT))) {
            $queryBuilder->andWhere('i.enabled = :enabled')
                ->setParameter('enabled', true);
        }

        if ($value === self::INV_LOW) {
            $queryBuilder->andWhere('i.lowLimitEnabled = :lowLimitEnabled')
                ->setParameter('lowLimitEnabled', true)
                ->andWhere('i.amount < i.lowLimitAmount');

        } elseif ($value === self::INV_OUT) {

            $queryBuilder->andWhere('i.amount <= :zero')
                ->setParameter('zero', 0);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        list($sort, $order) = $value;

        // FIXME - add aliases for sort modes
        if ('i.amount' === $sort) {
            $queryBuilder->innerJoinInventory();
        }

        $queryBuilder->addOrderBy($sort, $order);
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCndLimit(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value)
    {
        call_user_func_array(array($this, 'assignFrame'), array_merge(array($queryBuilder), $value));
    }

    /**
     * Call corresponded method to handle a search condition
     *
     * @param mixed                      $value        Condition data
     * @param string                     $key          Condition name
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
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
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addEnabledCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if (!\XLite::isAdminZone()) {
            $alias = $alias ?: $queryBuilder->getRootAlias();
            $queryBuilder->andWhere($alias . '.enabled = :enabled')
                ->setParameter('enabled', true);
        }
    }

    /**
     * Adds additional condition to the query for checking if product is up-to-date
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addDateCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if (!\XLite::isAdminZone()) {
            $alias = $alias ?: $queryBuilder->getRootAlias();
            $queryBuilder->andWhere($alias . '.arrivalDate < :now')
                ->setParameter('now', time());
        }
    }

    /**
     * Delete single entity
     *
     * @param \XLite\Model\AEntity $entity Entity to detach
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performDelete(\XLite\Model\AEntity $entity)
    {
        $carts = (array) \XLite\Core\Database::getRepo('XLite\Model\Cart')->findAll();
        $items = array();

        foreach ($carts as $cart) {
            foreach ((array) $cart->getItemsByProductId($entity->getProductId()) as $item) {
                $cart->getItems()->removeElement($item);
                $items[] = $item;
            }
        }

        \XLite\Core\Database::getRepo('XLite\Model\OrderItem')->deleteInBatch($items);
        \XLite\Core\Database::getRepo('XLite\Model\Cart')->updateInBatch($carts);

        parent::performDelete($entity);
    }
}
