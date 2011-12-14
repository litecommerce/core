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

/**
 * Category repository class
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Category extends \XLite\Model\Repo\Base\I18n
{
    /**
     * ID of the root pseudo-category
     */
    const CATEGORY_ID_ROOT = 1;

    /**
     * Maximum value of the "rpos" field in all records
     *
     * @var   integer
     * @see   ____var_see____
     * @since 1.0.6
     */
    protected $maxRightPos;

    /**
     * Flush unit-of-work changes after every record loading
     *
     * @var   boolean
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $flushAfterLoading = true;

    /**
     * Return the reserved ID of root category
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getRootCategoryId()
    {
        return self::CATEGORY_ID_ROOT;
    }

    /**
     * Return the category enabled condition
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getEnabledCondition()
    {
        return !\XLite::isAdminZone();
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string  $alias       Table alias OPTIONAL
     * @param boolean $excludeRoot Do not include root category into the search result OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function createQueryBuilder($alias = null, $excludeRoot = true)
    {
        $queryBuilder = parent::createQueryBuilder($alias);

        $this->addEnabledCondition($queryBuilder, $alias);
        $this->addOrderByCondition($queryBuilder, $alias);

        if ($excludeRoot) {
            $this->addExcludeRootCondition($queryBuilder, $alias);
        }

        return $queryBuilder;
    }

    /**
     * find() with cache
     *
     * @param integer $categoryId Category ID
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategory($categoryId)
    {
        return $this->find($this->prepareCategoryId($categoryId));
    }

    /**
     * Return full list of categories
     *
     * @param integer $rootId ID of the subtree root OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategories($rootId = null)
    {
        return $this->defineFullTreeQuery($rootId)->getResult();
    }

     /**
     * Return full list of categories
     *
     * @param integer $rootId ID of the subtree root OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategoriesPlainList($rootId = null)
    {
        return $this->defineFullTreeQuery($rootId)->getArrayResult();
    }

    /**
     * Return list of subcategories (one level)
     *
     * @param integer $rootId ID of the subtree root
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSubcategories($rootId)
    {
        return $this->defineSubcategoriesQuery($rootId)->getResult();
    }

    /**
     * Return list of categories on the same level
     *
     * @param \XLite\Model\Category $category Category
     * @param boolean               $hasSelf  Flag to include itself OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSiblings(\XLite\Model\Category $category, $hasSelf = false)
    {
        return $this->defineSiblingsQuery($category, $hasSelf)->getResult();
    }

    /**
     * Return categories subtree
     *
     * @param integer $categoryId Category Id
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getSubtree($categoryId)
    {
        return $category = $this->getCategory($categoryId)
            ? $this->defineSubtreeQuery($categoryId)->getResult()
            : array();
    }

    /**
     * Get categories path from root to the specified category
     *
     * @param integer $categoryId Category Id
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategoryPath($categoryId)
    {
        return $category = $this->getCategory($categoryId)
            ? $this->defineCategoryPathQuery($categoryId)->getResult()
            : array();
    }

    /**
     * Get depth of the category path
     *
     * @param integer $categoryId Category Id
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function getCategoryDepth($categoryId)
    {
        return $category = $this->getCategory($categoryId)
            ? $this->defineCategoryDepthQuery($categoryId)->getSingleScalarResult()
            : 0;
    }

    /**
     * Get categories list by product ID
     *
     * @param integer $productId Product ID
     *
     * @return \Doctrine\ORM\PersistentCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAllByProductId($productId)
    {
        return $this->defineSearchByProductIdQuery($productId)->getResult();
    }

    /**
     * Create new DB entry.
     * This function is used to create new QuickFlags entry
     *
     * @param array $data Entity properties
     *
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function insert(array $data)
    {
        $entity = parent::insert($data);

        // Create new record for the QuickFlags model
        $quickFlags = new \XLite\Model\Category\QuickFlags();
        $quickFlags->setCategory($entity);

        $this->update($entity, array('quickFlags' => $quickFlags));

        return $entity;
    }

    /**
     * Wrapper. Use this function instead of the native "delete...()"
     *
     * @param integer $categoryId  ID of category to delete
     * @param boolean $onlySubtree Flag OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function deleteCategory($categoryId, $onlySubtree = false)
    {
        // Find category by ID
        $entity = $this->getCategory($categoryId);

        // ROOT category cannot be removed. Only its subtree
        if ($categoryId == $this->getRootCategoryId()) {
            $onlySubtree = true;
        }

        // Save some variables
        $right = $entity->getRpos() - ($onlySubtree ? 1 : 0);
        $width = $entity->getRpos() - $entity->getLpos() - ($onlySubtree ? 1 : -1);

        if ($onlySubtree) {
            $this->deleteInBatch($this->getSubtree($entity->getCategoryId()));

        } else {
            $this->delete($entity);
        }

        // Update indexes in the nested set
        $this->defineUpdateIndexQuery('lpos', $right, -$width)->execute();
        $this->defineUpdateIndexQuery('rpos', $right, -$width)->execute();
    }


    /**
     * Add the conditions for the current subtree
     *
     * NOTE: function is public since it's needed to the Product model repository
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder to modify
     * @param integer                    $categoryId   Current category ID
     * @param string                     $field        Name of the field to use OPTIONAL
     * @param integer                    $lpos         Left position OPTIONAL
     * @param integer                    $rpos         Right position OPTIONAL
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function addSubTreeCondition(
        \Doctrine\ORM\QueryBuilder $queryBuilder,
        $categoryId,
        $field = 'lpos',
        $lpos = null,
        $rpos = null
    ) {
        $category = $this->getCategory($categoryId);

        if ($category) {
            $lpos = $lpos ?: $category->getLpos();
            $rpos = $rpos ?: $category->getRpos();

            $queryBuilder->andWhere($queryBuilder->expr()->between('c.' . $field, $lpos, $rpos));
        }

        return isset($category);
    }


    /**
     * Define the Doctrine query
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineMaxRightPosQuery()
    {
        return $this->createPureQueryBuilder()
            ->select('MAX(c.rpos)')
            ->groupBy('c.category_id')
            ->setMaxResults(1);
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineFullTreeQuery($categoryId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $this->addSubTreeCondition($queryBuilder, $categoryId ?: $this->getRootCategoryId());

        return $queryBuilder;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSubcategoriesQuery($categoryId)
    {
        $queryBuilder = $this->createQueryBuilder();

        if ($categoryId) {
            $queryBuilder
                ->innerJoin('c.parent', 'cparent')
                ->andWhere('cparent.category_id = :parentId')
                ->setParameter('parentId', $categoryId);

        } else {
            $queryBuilder
                ->andWhere('c.parent IS NULL');
        }

        return $queryBuilder;
    }

    /**
     * Define the Doctrine query
     *
     * @param \XLite\Model\Category $category Category
     * @param boolean               $hasSelf  Flag to include itself OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSiblingsQuery(\XLite\Model\Category $category, $hasSelf = false)
    {
        $result = $this->defineSubcategoriesQuery($category->getParentId());

        if (!$hasSelf) {
            $result
                ->andWhere('c.category_id <> :category_id')
                ->setParameter('category_id', $category->getCategoryId());
        }

        return $result;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSubtreeQuery($categoryId)
    {
        return $this->defineFullTreeQuery($categoryId)
            ->andWhere('c.category_id <> :category_id')
            ->setParameter('category_id', $categoryId);
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCategoryPathQuery($categoryId)
    {
        $queryBuilder = $this->createQueryBuilder();
        $category = $this->getCategory($categoryId);

        if ($category) {
            $this->addSubTreeCondition($queryBuilder, $categoryId, 'lpos', 1, $category->getLpos());

            $this->addSubTreeCondition(
                $queryBuilder,
                $categoryId,
                'rpos',
                $category->getRpos(),
                $this->getMaxRightPos()
            );

        } else {
            // :TODO: - throw exception
        }

        return $queryBuilder;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCategoryDepthQuery($categoryId)
    {
        return $this->defineCategoryPathQuery($categoryId)
            ->select('COUNT(c.category_id) - 1')
            ->setMaxResults(1);
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $productId Product Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineSearchByProductIdQuery($productId)
    {
        return $this->createQueryBuilder()
            ->innerJoin('c.categoryProducts', 'cp')
            ->innerJoin('cp.product', 'product')
            ->andWhere('product.product_id = :productId')
            ->setParameter('productId', $productId)
            ->addOrderBy('cp.orderby', 'ASC');
    }

    /**
     * Define the Doctrine query
     *
     * @param string  $index        Field name
     * @param integer $relatedIndex Related index value
     * @param integer $offset       Increment OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineUpdateIndexQuery($index, $relatedIndex, $offset = 2)
    {
        $expr = new \Doctrine\ORM\Query\Expr();

        return $this->createPureQueryBuilder('c', false)
            ->update($this->_entityName, 'c')
            ->set('c.' . $index, 'c.' . $index . ' + :offset')
            ->andWhere($expr->gt('c.' . $index, ':relatedIndex'))
            ->setParameters(
                array(
                    'offset'       => $offset,
                    'relatedIndex' => $relatedIndex,
                )
            );
    }

    /**
     * Adds additional condition to the query for checking if category is enabled
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
        if ($this->getEnabledCondition()) {
            $queryBuilder
                ->andWhere(($alias ?: $this->getDefaultAlias()) . '.enabled = :enabled')
                ->setParameter('enabled', true);
        }
    }

    /**
     * Adds additional condition to the query to order categories
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addOrderByCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $queryBuilder
            ->addOrderBy(($alias ?: $this->getDefaultAlias()) . '.pos', 'ASC')
            ->addOrderBy(($alias ?: $this->getDefaultAlias()) . '.lpos', 'ASC');
    }

    /**
     * Adds additional condition to the query to order categories
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function addExcludeRootCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $alias = $alias ?: $this->getDefaultAlias();

        $queryBuilder
            ->andWhere($alias . '.category_id <> :rootId')
            ->setParameter('rootId', $this->getRootCategoryId());
    }

    /**
     * Return maximum index in the "nested set" tree
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getMaxRightPos()
    {
        if (!isset($this->maxRightPos)) {
            $this->maxRightPos = $this->defineMaxRightPosQuery()->getSingleScalarResult();
        }

        return $this->maxRightPos;
    }

    /**
     * Prepare data for a new category node
     *
     * @param \XLite\Model\Category $entity Category object
     * @param \XLite\Model\Category $parent Parent category object OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareNewCategoryData(\XLite\Model\Category $entity, \XLite\Model\Category $parent = null)
    {
        if (!isset($parent)) {
            $parent = $this->getCategory($entity->getParentId());
        }

        if (isset($parent)) {
            $entity->setLpos($parent->getLpos() + 1);
            $entity->setRpos($parent->getLpos() + 2);
            $entity->setDepth($parent->getDepth() + 1);

        } else {
            // :TODO: - rework - add support last root category
            $entity->setLpos(1);
            $entity->setRpos(2);
        }

        $entity->setParent($parent);
    }

    /**
     * Prepare data for a the "updateQuickFlags()" method
     *
     * @param integer $scAll     The "subcategories_count_all" flag value
     * @param integer $scEnabled The "subcategories_count_enabled" flag value
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareQuickFlags($scAll, $scEnabled)
    {
        return array(
            'subcategories_count_all'     => $scAll,
            'subcategories_count_enabled' => $scEnabled,
        );
    }

    /**
     * Prepare passed ID
     * NOTE: see E:0038835 (external BT)
     *
     * @param mixed $categoryId Category ID
     *
     * @return integer|void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function prepareCategoryId($categoryId)
    {
        return abs(intval($categoryId)) ?: null;
    }

    /**
     * Update quick flags for a category
     *
     * @param \XLite\Model\Category $entity Category
     * @param array                 $flags  Flags to set
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function updateQuickFlags(\XLite\Model\Category $entity, array $flags)
    {
        $quickFlags = $entity->getQuickFlags();

        if ($quickFlags) {
            foreach ($flags as $name => $delta) {
                $name = \Includes\Utils\Converter::convertToPascalCase($name);
                $quickFlags->{'set' . $name}($quickFlags->{'get' . $name}() + $delta);
            }
        }

        // Do not change to $this->update() or $this->performUpdate():
        // it will cause the unfinite recursion
        parent::performUpdate($entity);
    }

    /**
     * Insert single entity
     *
     * @param \XLite\Model\AEntity|array $entity Data to insert OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performInsert($entity = null)
    {
        $entity   = parent::performInsert($entity);
        $parentID = $entity->getParentId();

        if (empty($parentID)) {
            // Insert root category
            $this->prepareNewCategoryData($entity);

        } else {
            // Get parent for non-root category
            $parent = $this->getCategory($parentID);

            if ($parent) {
                // Update indexes in the nested set
                $this->defineUpdateIndexQuery('lpos', $parent->getLpos())->execute();
                $this->defineUpdateIndexQuery('rpos', $parent->getLpos())->execute();

                // Create record in DB
                $this->prepareNewCategoryData($entity, $parent);

            } else {
                \Includes\ErrorHandler::fireError(__METHOD__ . ': category #' . $parentID . ' not found');
            }
        }

        // Update quick flags
        if (isset($parent)) {
            $this->updateQuickFlags($parent, $this->prepareQuickFlags(1, $entity->getEnabled() ? 1 : -1));
        }

        return $entity;
    }

    /**
     * Update single entity
     *
     * @param \XLite\Model\AEntity $entity Entity to use
     * @param array                $data   Data to save OPTIONAL
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function performUpdate(\XLite\Model\AEntity $entity, array $data = array())
    {
        if (isset($data['enabled']) && $entity->getParent() && ($entity->getEnabled() xor ((bool) $data['enabled']))) {
            $this->updateQuickFlags($entity->getParent(), $this->prepareQuickFlags(0, $entity->getEnabled() ? -1 : 1));
        }

        parent::performUpdate($entity, $data);
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
        // Update quick flags
        $this->updateQuickFlags($entity->getParent(), $this->prepareQuickFlags(-1, $entity->getEnabled() ? -1 : 0));

        parent::performDelete($entity);
    }

    /**
     * Assemble regular fields from record
     *
     * @param array $record  Record
     * @param array $regular Regular fields info OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleRegularFieldsFromRecord(array $record, array $regular = array())
    {
        if (!isset($record['lpos'])) {
            $record['lpos'] = 1;
        }

        if (!isset($record['rpos'])) {
            $record['rpos'] = 2;
        }

        return parent::assembleRegularFieldsFromRecord($record, $regular);
    }

    /**
     * Link loaded entity to parent object
     *
     * @param \XLite\Model\AEntity $entity      Loaded entity
     * @param \XLite\Model\AEntity $parent      Entity parent callback
     * @param array                $parentAssoc Entity mapped propery method
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function linkLoadedEntity(\XLite\Model\AEntity $entity, \XLite\Model\AEntity $parent, array $parentAssoc)
    {
        parent::linkLoadedEntity($entity, $parent, $parentAssoc);

        if ($parent instanceof \XLite\Model\Category) {
            $quickFlags = new \XLite\Model\Category\QuickFlags();
            $entity->setQuickFlags($quickFlags);
            $quickFlags->setCategory($entity);

            // Update indexes in the nested set
            if (isset($parent)) {
                $this->defineUpdateIndexQuery('lpos', $parent->getRpos() - 1)->execute();
                $this->defineUpdateIndexQuery('rpos', $parent->getRpos() - 1)->execute();

                $entity->setLpos($parent->getRpos());
                $entity->setRpos($parent->getRpos() + 1);

                $parent->setRpos($parent->getRpos() + 2);

            } else {
                $rpos = $this->getMaxRightPos();

                $entity->setLpos($rpos + 1);
                $entity->setRpos($rpos + 2);
            }
        }
    }

    /**
     * Assemble associations from record
     *
     * @param array $record Record
     * @param array $assocs Associations info OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function assembleAssociationsFromRecord(array $record, array $assocs = array())
    {
        if (!isset($record['quickFlags'])) {
            $record['quickFlags'] = array();
        }

        return parent::assembleAssociationsFromRecord($record, $assocs);
    }

    /**
     * Get detailed foreign keys
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function getDetailedForeignKeys()
    {
        $list = parent::getDetailedForeignKeys();

        $list[] = array(
            'fields'          => array('parent_id'),
            'referenceRepo'   => 'XLite\Model\Category',
            'referenceFields' => array('category_id'),
            'delete'          => 'SET NULL',
        );

        return $list;
    }
}
