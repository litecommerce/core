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

namespace XLite\Model\Repo;

/**
 * The "product" model repository
 *
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

    const SKU_GENERATION_LIMIT = 50;

    /**
     * currentSearchCnd
     *
     * @var \XLite\Core\CommonCell
     */
    protected $currentSearchCnd = null;

    /**
     * Alternative record identifiers
     *
     * @var array
     */
    protected $alternativeIdentifier = array(
        array('sku'),
    );


    /**
     * Get the list of all clean URLS
     *
     * @return array
     */
    public function findAllCleanURLs()
    {
        $result = array();

        $products = $this->defineAllCleanURLsQuery()->getArrayResult();

        foreach($products as $row) {
            $result[$row['cleanURL']] = $row['product_id'];
        }

        return $result;
    }

    /**
     * Define the Doctrine query
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineAllCleanURLsQuery()
    {
        return $this->createPureQueryBuilder()
            ->select('p.product_id')
            ->addSelect('p.cleanURL');
    }

    /**
     * Common search
     *
     * @param \XLite\Core\CommonCell $cnd       Search condition
     * @param boolean                $countOnly Return items list or only its size OPTIONAL
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function search(\XLite\Core\CommonCell $cnd, $countOnly = false)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->currentSearchCnd = $cnd;

        foreach ($this->currentSearchCnd as $key => $value) {
            $this->callSearchConditionHandler($value, $key, $queryBuilder, $countOnly);
        }

        return $countOnly
            ? $this->searchCount($queryBuilder)
            : $this->searchResult($queryBuilder);
    }

    /**
     * Search count only routine.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder routine
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function searchCount(\Doctrine\ORM\QueryBuilder $qb)
    {
        $qb->select('COUNT(DISTINCT p.product_id)');

        return intval($qb->getSingleScalarResult());
    }

    /**
     * Search result routine.
     *
     * @param \Doctrine\ORM\QueryBuilder $qb Query builder routine
     *
     * @return \Doctrine\ORM\PersistentCollection|integer
     */
    public function searchResult(\Doctrine\ORM\QueryBuilder $qb)
    {
        return $qb->getOnlyEntities();
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias OPTIONAL
     * @param string $code  Language code OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($alias = null, $code = null)
    {
        $queryBuilder = parent::createQueryBuilder($alias, $code);

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
     */
    public function findOneByCleanURL($url)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.cleanURL = :url')
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
     */
    public function countLastUpdated($limit)
    {
        return intval($this->defineCountLastUpdatedQuery($limit)->getSingleScalarResult());
    }

    /**
     * Get REST entity names
     *
     * @return array
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
     */
    public function getImportIterator()
    {
        return $this->defineImportQuery()->iterate();
    }

    /**
     * Assign enabled condition for extenral query builders
     * 
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $qb    External query builder
     * @param string                                  $alias Product repository alias OPTIONAL
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    public function assignExternalEnabledCondition(\XLite\Model\QueryBuilder\AQueryBuilder $qb, $alias = 'p')
    {
        $this->addEnabledCondition($qb, $alias);

        return $qb;
    }

    /**
     * Generate SKU
     *
     * @param \XLite\Model\Product $product Product
     *
     * @return string
     */
    public function generateSKU(\XLite\Model\Product $product)
    {
        $id = $product->getProductId();
        $id = str_repeat('0', 11 - strlen((string)$id)) . $id;
        $sku = $id;
        $i = 0;

        $qb = $this->defineGenerateSKUQuery();
        
        while ($i < static::SKU_GENERATION_LIMIT && 0 < intval($qb->setParameter('sku', $sku)->getSingleScalarResult())) {
            $i++;
            $sku = $id . '-' . $i;
        }

        if ($i >= static::SKU_GENERATION_LIMIT) {
            $sku = md5($sku . microtime(true));
        }

        return $sku;
    }

    /**
     * Define query for generateSKU() method
     * 
     * @return \XLite\Model\QueryBuilder\QUeryBuilder
     */
    protected function defineGenerateSKUQuery()
    {
        return $this->getQueryBuilder()
            ->from($this->_entityName, 'p')
            ->select('COUNT(p.product_id) cnt')
            ->andWhere('p.sku = :sku');
    }

    /**
     * Define import querty
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
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
     */
    protected function defineCountLastUpdatedQuery($limit)
    {
        return $this->createQueryBuilder('p')
            ->select('COUNT(p.product_id) cnt')
            ->andWhere('p.updateDate >= :time')
            ->setParameter('time', $limit)
            ->setMaxResults(1);
    }

    /**
     * Return list of handling search params
     *
     * @return array
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
     */
    protected function isSearchParamHasHandler($param)
    {
        return in_array($param, $this->getHandlingSearchParams());
    }

    /**
     * List of fields to use in search by substring TODO !REFACTOR!
     *
     * @return array
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
     */
    protected function prepareCndSKU(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        $value = trim($value);
        if (0 < strlen($value)) {
            $queryBuilder->andWhere('p.sku LIKE :sku')
                ->setParameter('sku', '%' . $value . '%');
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param mixed                      $value        Condition data
     *
     * @return void
     */
    protected function prepareCndCategoryId(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (is_object($value) && $value instanceOf \XLite\Model\Category) {
            $value = $value->getCategoryId();
        }

        $queryBuilder->linkInner('p.categoryProducts', 'cp')
            ->linkInner('cp.category', 'c')
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
     */
    protected function prepareCndPrice(\Doctrine\ORM\QueryBuilder $queryBuilder, $value)
    {
        if (is_array($value)) {
            $min = empty($value[0]) ? null : trim($value[0]);
            $min = (0 == strlen($min) || !is_numeric($min)) ? null : doubleval($min);

            $max = empty($value[1]) ? null : trim($value[1]);
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
     */
    protected function assignPriceRangeCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $min, $max)
    {
        $field = 'p.price';
        if (!\XLite::isAdminZone() && (isset($min) || isset($max))) {
            $this->assignCalculatedField($queryBuilder, 'price');
            $field = 'calculatedPrice';
        }

        if (isset($min)) {
            $queryBuilder->andWhere($field . ' > :minPrice')
                ->setParameter('minPrice', doubleval($min));
        }

        if (isset($max)) {
            $queryBuilder->andWhere($field . ' < :maxPrice')
                ->setParameter('maxPrice', doubleval($max));
        }
    }

    /**
     * Returns array of allowed values for 'includes' input variable
     *
     * @return boolean
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
     * @param boolean                    $countOnly    "Count only" flag. Do not need to add "order by" clauses if only count is needed.
     *
     * @return void
     */
    protected function prepareCndOrderBy(\Doctrine\ORM\QueryBuilder $queryBuilder, array $value, $countOnly)
    {
        if (!$countOnly) {
            list($sort, $order) = $value;

            // FIXME - add aliases for sort modes
            if ('i.amount' === $sort) {
                $queryBuilder->innerJoinInventory();

            } elseif ('p.price' == $sort && !\XLite::isAdminZone()) {
                $this->assignCalculatedField($queryBuilder, 'price');
                $sort = 'calculatedPrice';
            }

            $queryBuilder->addOrderBy($sort, $order);
        }
    }

    /**
     * Prepare certain search condition
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to prepare
     * @param array                      $value        Condition data
     *
     * @return void
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
     */
    protected function callSearchConditionHandler($value, $key, \Doctrine\ORM\QueryBuilder $queryBuilder, $countOnly)
    {
        if ($this->isSearchParamHasHandler($key)) {
            $this->{'prepareCnd' . ucfirst($key)}($queryBuilder, $value, $countOnly);

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
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function addEnabledCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if (!\XLite::isAdminZone()) {
            $this->assignEnabledCondition($queryBuilder, $alias);
        }

        return $queryBuilder;
    }

    /**
     * Assign enabled condition 
     * 
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param string                     $alias        Alias OPTIONAL
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function assignEnabledCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $alias = $alias ?: $queryBuilder->getRootAlias();
        $queryBuilder->andWhere($alias . '.enabled = :enabled')
            ->setParameter('enabled', true);

        return $queryBuilder;
    }

    /**
     * Define calculated price definition DQL 
     * 
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder Query builder
     * @param string                                  $alias        Main alias
     *  
     * @return string
     */
    protected function defineCalculatedPriceDQL(\XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder, $alias)
    {
        return $alias . '.price';
    }

    /**
     * Define calculated amount definition DQL
     *
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder Query builder
     * @param string                                  $alias        Main alias
     *
     * @return string
     */
    protected function defineCalculatedAmountDQL(\XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder, $alias)
    {
        return 'i.amount';
    }

    /**
     * Define calculated name definition DQL
     *
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder Query builder
     * @param string                                  $alias        Main alias
     *
     * @return string
     */
    protected function defineCalculatedNameDQL(\XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder, $alias)
    {
        return 'translations.name';
    }

    /**
     * Define calculated sku definition DQL
     *
     * @param \XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder Query builder
     * @param string                                  $alias        Main alias
     *
     * @return string
     */
    protected function defineCalculatedSkuDQL(\XLite\Model\QueryBuilder\AQueryBuilder $queryBuilder, $alias)
    {
        return $alias . '.sku';
    }

    /**
     * Adds additional condition to the query for checking if product is up-to-date
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
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
