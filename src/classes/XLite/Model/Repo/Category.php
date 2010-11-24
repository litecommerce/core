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
 * Category repository class
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Category extends \XLite\Model\Repo\Base\I18n
{
    /**
     * ID of the root pseudo-category 
     */
    const CATEGORY_ID_ROOT = 1;


    /**
     * Define the Doctrine query
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFullTreeQuery($categoryId)
    {
        if (!isset($categoryId)) {
            $categoryId = $this->getRootCategoryId();
        }

        $queryBuilder = $this->createQueryBuilder();
        $this->addSubTreeCondition($queryBuilder, $categoryId);
        
        return $queryBuilder;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSubcategoriesQuery($categoryId)
    {
        return $this->createQueryBuilder()
            ->andWhere('c.parent_id = :parentId')
            ->setParameter('parentId', $categoryId);
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSiblingsQuery($categoryId)
    {
        $parentId = $this->find($categoryId)->getParent()->getCategoryId();

        return $this->defineSubcategoriesQuery($parentId)
            ->andWhere('c.category_id <> :category_id')
            ->setParameter('category_id', $categoryId);
            
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
            // TODO - add throw exception
        }

        return $queryBuilder;
    }

    /**
     * Define the Doctrine query
     *
     * @param integer $categoryId Category Id
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineSearchByProductIdQuery($productId)
    {
        return $this->createQueryBuilder()
            ->innerJoin('c.categoryProducts', 'cp')
            ->andWhere('cp.product_id = :productId')
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineUpdateIndexQuery($index, $relatedIndex, $offset = 2)
    {
        $expr = new \Doctrine\ORM\Query\Expr();

        return $this->createPureQueryBuilder('c', false)
            ->update($this->_entityName, 'c')
            ->set('c.' . $index, 'c.' . $index . ' + :offset')
            ->andWhere($expr->gt('c.' . $index, ':relatedIndex'))
            ->setParameters(array('offset' => $offset, 'relatedIndex' => $relatedIndex));
    }


    /**
     * Adds additional condition to the query for checking if category is enabled
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addEnabledCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        if ($this->getEnabledCondition()) {
            $alias = $alias ?: $this->getDefaultAlias();
            $queryBuilder->andWhere($alias . '.enabled = :enabled')
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addOrderByCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $queryBuilder
            ->addOrderBy(($alias ?: $this->getDefaultAlias()) . '.lpos', 'ASC');
    }

    /**
     * Adds additional condition to the query to order categories
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder object
     * @param string                     $alias        Entity alias OPTIONAL
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addExcludeRootCondition(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null)
    {
        $alias = $alias ?: $this->getDefaultAlias();
        $queryBuilder->andWhere($alias . '.category_id <> :rootId')
            ->setParameter('rootId', $this->getRootCategoryId());
    }

    /**
     * Return maximum index in the "nested set" tree
     * 
     * @return integer 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMaxRightPos()
    {
        return $this->defineMaxRightPosQuery()
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Prepare data for a new category node
     *
     * @param array                 $data   Category properties
     * @param \XLite\Model\Category $parent Parent category object
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareNewCategoryData(array $data, \XLite\Model\Category $parent = null)
    {
        if (!isset($parent)) {
            $parent = $this->getCategory($data['parent_id']);
        }

        return array(
            'lpos'   => $parent->getLpos() + 1,
            'rpos'   => $parent->getLpos() + 2,
            'parent' => $parent,
        ) + $data;
    }

    /**
     * Prepare data for a the "updateQuickFlags()" method
     *
     * @param integer $scAll     The "subcategories_count_all" flag value
     * @param integer $scEnabled The "subcategories_count_enabled" flag value
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareQuickFlags($scAll, $scEnabled)
    {
        return array(
            'subcategories_count_all'     => $scAll,
            'subcategories_count_enabled' => $scEnabled,
        );
    }

    /**
     * Update quick flags for a category
     * 
     * @param \XLite\Model\Category $entity Category
     * @param array                 $flags  Flags to set
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function updateQuickFlags(\XLite\Model\Category $entity, array $flags)
    {
        foreach ($flags as $name => $delta) {
            $entity->getQuickFlags()->$name += $delta;
        }

        // Do not change to $this->update() or $this->performUpdate():
        // it will cause the unfinite recursion
        parent::performUpdate($entity);
    }


    /**
     * Insert single entity
     *
     * @param array $data Data to save
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performInsert(array $data = array())
    {
        $entity = null;

        // Get parent
        $parent = $this->getCategory($data['parent_id']);
        if ($parent) {

            // Update indexes in the nested set
            $this->defineUpdateIndexQuery('lpos', $parent->getLpos())->getQuery()->execute();
            $this->defineUpdateIndexQuery('rpos', $parent->getLpos())->getQuery()->execute();

            // Create record in DB
            $entity = parent::performInsert($this->prepareNewCategoryData($data, $parent));

            // Update quick flags
            $this->updateQuickFlags($parent, $this->prepareQuickFlags(1, $entity->getEnabled() ? 1 : -1));

        } else {
            // TODO - add throw excception
        }

        return $entity;
    }

    /**
     * Update single entity
     *
     * @param \XLite\Model\AEntity $entity Entity to use
     * @param array                $data   Data to save
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performUpdate(\XLite\Model\AEntity $entity, array $data = array())
    {
        // Update quick flags (if needed)
        if (isset($data['enabled']) && ($entity->getEnabled() xor ((bool) $data['enabled']))) {
            $enabled = $entity->getEnabled() ? -1 : 1;
            $this->updateQuickFlags($entity->getParent(), $this->prepareQuickFlags(0, $enabled));
        }

        parent::performUpdate($entity, $data);
    }

    /**
     * Delete single entity
     *
     * @param \XLite\Model\AEntity $entity Entity to detach
     *
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function performDelete(\XLite\Model\AEntity $entity)
    {
        // Update quick flags
        $this->updateQuickFlags($entity->getParent(), $this->prepareQuickFlags(-1, $entity->getEnabled() ? -1 : 0));
    
        parent::performDelete($entity);
    }


    /**
     * Return the reserved ID of root category
     *
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getRootCategoryId()
    {
        return self::CATEGORY_ID_ROOT;
    }

    /**
     * Return the ctegory enabled condition
     * 
     * @return boolean 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createQueryBuilder($alias = null, $excludeRoot = true)
    {
        $qb = parent::createQueryBuilder($alias);

        $this->addEnabledCondition($qb, $alias);
        $this->addOrderByCondition($qb, $alias);

        if ($excludeRoot) {
            $this->addExcludeRootCondition($qb, $alias);
        }

        return $qb;
    }

    /**
     * find() with cache
     * 
     * @param integer $categoryId Category ID
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory($categoryId)
    {
        return $this->find(intval($categoryId));
    }

    /**
     * Return full list of categories
     *
     * @param integer $rootId ID of the subtree root OPTIONAL
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories($rootId = null)
    {
        return $this->defineFullTreeQuery($rootId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return list of subcategories (one level)
     *
     * @param integer $rootId ID of the subtree root
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubcategories($rootId)
    {
        return $this->defineSubcategoriesQuery($rootId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return list of categories on the same level
     *
     * @param integer $categoryId Category Id
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSiblings($categoryId)
    {
        return $this->defineSiblingsQuery($categoryId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Return categories subtree
     *
     * @param integer $categoryId Category Id
     *
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubtree($categoryId)
    {
        return $this->defineSubtreeQuery($categoryId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get categories path from root to the specified category 
     * 
     * @param integer $categoryId Category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryPath($categoryId)
    {
        return $this->defineCategoryPathQuery($categoryId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get depth of the category path
     * 
     * @param integer $categoryId Category Id
     *  
     * @return integer 
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryDepth($categoryId)
    {
        return $this->defineCategoryDepthQuery($categoryId)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Get categories list by product ID
     * 
     * @param integer $productId Product ID
     *  
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByProductId($productId)
    {
        return $this->defineSearchByProductIdQuery($productId)
            ->getQuery()
            ->getResult();
    }

    /**
     * Create new DB entry.
     * This function is used to create new QuickFlags entry
     * 
     * @param array $data Entity properties
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function insert(array $data)
    {
        $entity = parent::insert($data);

        // Create new record for the QuickFlags model
        \XLite\Core\Database::getRepo('\XLite\Model\Category\QuickFlags')->insert(
            array('category_id' => $entity->getCategoryId())
        );

        return $entity;
    }

    /**
     * Wrapper. Use this function instead of the native "delete...()"
     * 
     * @param integer $categoryId  ID of category to delete
     * @param boolean $onlySubtree Flag OPTIONAL
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCategory($categoryId, $onlySubtree = false)
    {
        // Find category by ID
        $entity = $this->getCategory($categoryId);

        // Save some variables
        $right = $entity->getRpos() - ($onlySubtree ? 1 : 0);
        $width = $right - $entity->getLpos() + ($onlySubtree ? 0 : 1);

        $onlySubtree 
            ? $this->deleteInBatch($this->getSubtree($entity->getCategoryId())) 
            : $this->delete($entity);

        // Update indexes in the nested set
        $this->defineUpdateIndexQuery('lpos', $right, -$width)->getQuery()->execute();
        $this->defineUpdateIndexQuery('rpos', $right, -$width)->getQuery()->execute();
    }


    /**
     * Add the conditions for the current subtree
     *
     * NOTE: function is public since it's needed to the Product model repository
     *
     * @param \Doctrine\ORM\QueryBuilder $qb         Query builder to modify
     * @param integer                    $categoryId Current category ID
     * @param string                     $field      Name of the field to use OPTIONAL
     * @param integer                    $lpos       Left position OPTIONAL
     * @param integer                    $rpos       Right position OPTIONAL
     *
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addSubTreeCondition(\Doctrine\ORM\QueryBuilder $qb, $categoryId, $field = 'lpos', $lpos = null, $rpos = null)
    {
        $category = $this->getCategory($categoryId);

        if ($category) {
            $lpos = $lpos ?: $category->getLpos();
            $rpos = $rpos ?: $category->getRpos();

            $qb->andWhere($qb->expr()->between('c.' . $field, $lpos, $rpos));
        }

        return isset($category);
    }
}
