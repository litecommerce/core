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
 * Category repository class
 *
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
     * @var integer
     */
    protected $maxRightPos;

    /**
     * Flush unit-of-work changes after every record loading
     *
     * @var boolean
     */
    protected $flushAfterLoading = true;

    /**
     * Return the reserved ID of root category
     *
     * @return integer
     */
    public function getRootCategoryId()
    {
        return static::CATEGORY_ID_ROOT;
    }

    /**
     * Return the category enabled condition
     *
     * @return boolean
     */
    public function getEnabledCondition()
    {
        return !\XLite::isAdminZone();
    }

    /**
     * Return the category membership condition
     *
     * @return boolean
     */
    public function getMembershipCondition()
    {
        return !\XLite::isAdminZone();
    }

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string  $alias       Table alias OPTIONAL
     * @param string  $code        Language code OPTIONAL
     * @param boolean $excludeRoot Do not include root category into the search result OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($alias = null, $code = null, $excludeRoot = true)
    {
        $queryBuilder = parent::createQueryBuilder($alias, $code);

        $this->addEnabledCondition($queryBuilder, $alias);
        $this->addOrderByCondition($queryBuilder, $alias);
        $this->addMembershipCondition($queryBuilder, $alias);

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
     */
    public function getCategoriesPlainList($rootId = null)
    {
        $rootId = $rootId ?: $this->getRootCategoryId();

        return $this->getCategoriesPlainListChild($rootId);
    }

    /**
     * Return list of subcategories (one level)
     *
     * @param integer $rootId ID of the subtree root
     *
     * @return array
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
     */
    public function findAllByProductId($productId)
    {
        return $this->defineSearchByProductIdQuery($productId)->getResult();
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
     */
    protected function defineFullTreeQuery($categoryId)
    {
        $queryBuilder = $this->createQueryBuilder()
            ->addSelect('translations');

        $this->addSubTreeCondition($queryBuilder, $categoryId ?: $this->getRootCategoryId());

        return $queryBuilder;
    }

    /**
     * Get categories plain list (child)
     *
     * @param integer $categoryId Category id
     *
     * @return array
     */
    protected function getCategoriesPlainListChild($categoryId)
    {
        $list = array();

        foreach ($this->defineSubcategoriesQuery($categoryId)->getArrayResult() as $category) {
            $list[] = $category;
            if ($category['lpos'] > $category['rpod'] + 1) {
                $list = array_merge($list, $this->getCategoriesPlainListChild($category['category_id']));
            }
        }

        return $list;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
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
     * Adds additional condition to the query for checking if category is enabled
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     */
    protected function addMembershipCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $alias = $alias ?: $this->getDefaultAlias();

        if ($this->getMembershipCondition()) {

            $membership = \XLite\Core\Auth::getInstance()->getMembershipId();

            if ($membership) {
                $queryBuilder
                    ->andWhere($alias . '.membership = :membershipId OR ' . $alias . '.membership IS NULL')
                    ->setParameter('membershipId', \XLite\Core\Auth::getInstance()->getMembershipId());
            } else {
                $queryBuilder
                    ->andWhere($alias . '.membership IS NULL');
            }
        }
    }

    /**
     * Adds additional condition to the query to order categories
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
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
     */
    protected function updateQuickFlags(\XLite\Model\Category $entity, array $flags)
    {
        $quickFlags = $entity->getQuickFlags();

        if (!isset($quickFlags)) {
            $quickFlags = new \XLite\Model\Category\QuickFlags();
            $quickFlags->setCategory($entity);
            $entity->setQuickFlags($quickFlags);
        }

        foreach ($flags as $name => $delta) {
            $name = \Includes\Utils\Converter::convertToPascalCase($name);
            $quickFlags->{'set' . $name}($quickFlags->{'get' . $name}() + $delta);
        }
    }

    // {{{ Methods to manage entities

    /**
     * Remove all subcategories
     *
     * @param integer $categoryId Main category
     *
     * @return void
     */
    public function deleteSubcategories($categoryId)
    {
        $this->deleteInBatch($this->getSubtree($categoryId));
    }

    /**
     * Insert single entity
     *
     * @param \XLite\Model\AEntity|array $entity Data to insert OPTIONAL
     *
     * @return void
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
     */
    protected function performDelete(\XLite\Model\AEntity $entity)
    {
        // Update quick flags
        if ($entity->getParent()) {
            $this->updateQuickFlags($entity->getParent(), $this->prepareQuickFlags(-1, $entity->getEnabled() ? -1 : 0));
        }

        // Root category cannot be removed. Only its subtree
        $onlySubtree = ($entity->getCategoryId() == $this->getRootCategoryId());

        // Calculate some variables
        $right = $entity->getRpos() - ($onlySubtree ? 1 : 0);
        $width = $entity->getRpos() - $entity->getLpos() - ($onlySubtree ? 1 : -1);

        // Update indexes in the nested set.
        // FIXME: must not use execute()
        $this->defineUpdateIndexQuery('lpos', $right, -$width)->execute();
        $this->defineUpdateIndexQuery('rpos', $right, -$width)->execute();

        if ($onlySubtree) {
            $this->deleteInBatch($this->getSubtree($entity->getCategoryId()), false);

        } else {
            parent::performDelete($entity);
        }
    }

    // }}}

    /**
     * Assemble regular fields from record
     *
     * @param array $record  Record
     * @param array $regular Regular fields info OPTIONAL
     *
     * @return array
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
