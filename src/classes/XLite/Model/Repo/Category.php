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
     * className
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $className = '\XLite\Model\Category';

    /**
     * cachePrefix 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $cachePrefix = 'Category';

    /**
     * Flag to ignore cache when gathering data
     * 
     * @var    bool
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $ignoreCache = false;

    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();

        $list[$this->cachePrefix . '_Details'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->cachePrefix . '_FullTree'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->cachePrefix . '_FullTreeHash'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->cachePrefix . '_NodePath'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->cachePrefix . '_ByCleanUrl'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('clean_url')
        );

        $list[$this->cachePrefix . '_LeafNodes'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL
        );

        $list[$this->cachePrefix . '_MaxRightPos'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL
        );

        return $list;
    }

    /**
     * Clean all cache cells
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function cleanCache()
    {
        if (\XLite\Core\Database::isCacheEnabled()) {
    
            $keys = array(
                '_Details',
                '_FullTree',
                '_FullTreeHash',
                '_NodePath',
                '_LeafNodes',
                '_MaxRightPos'
            );

            foreach ($keys as $key) {
                $this->deleteCache($this->cachePrefix . $key);
            }
        }
    }

    /**
     * Adds additional condition to the query for checking if category is enabled
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb    Query builder object
     * @param string                     $alias Entity alias
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function addEnabledCondition($qb, $alias = 'c')
    {
        if (!\XLite::getInstance()->isAdminZone()) {
            $qb->andWhere($alias . '.enabled = 1');
        }

        return $qb;
    }

    /**
     * Get the category details
     * 
     * @param integer $categoryId Node Id
     *  
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNode($categoryId)
    {
        $categoryId = intval($categoryId);

        $data = ($this->ignoreCache) ?
            null :
            $this->getFromCache($this->cachePrefix . '_Details', array('category_id' => $categoryId));

        if (!isset($data)) {

            $data = $this->defineNodeQuery($categoryId)->getQuery()->getResult();

            if (!empty($data)) {
                $data = array_shift($data);

                if (isset($data)) {
                    $this->saveToCache($data, $this->cachePrefix . '_Details', array('category_id' => $categoryId));
                }
            } else {
                $data = null;
            }
        }

        return $data;
    }

    /**
     * Defines the query for node details selection
     * 
     * @param int $categoryId Node Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineNodeQuery($categoryId)
    {
        $categoryId = intval($categoryId);

        $qb = $this->createQueryBuilder('c')
            ->addSelect('m', 'i')
            ->leftJoin('c.membership', 'm')
            ->leftJoin('c.image', 'i')
            ->andWhere('c.category_id = :categoryId')
            ->setMaxResults(1)
            ->setParameter('categoryId', $categoryId);

        return $this->addEnabledCondition($qb, 'c');
    }

    /**
     * Get the categories tree
     * 
     * @param int $categoryId Node Id
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFullTree($categoryId = 0)
    {
        $categoryId = intval($categoryId);

        $data = ($this->ignoreCache) ?
            null :
            $this->getFromCache($this->cachePrefix . '_FullTree', array('category_id' => $categoryId));

        if (!isset($data)) {

            $validParent = true;

            if (0 < $categoryId) {

                $parentCategory = $this->getNode($categoryId);

                if (!isset($parentCategory)) {
                    $validParent = false;
                }
            }

            if ($validParent) {

                $data = $this->defineFullTreeQuery($categoryId)->getQuery()->getResult();

                $right = array($this->getMaxRightPos());
                $dataTmp = array();

                // Calculate categorys depth
                foreach ($data as $id => $nd) {

                    $dataTmp[$id] = $nd = $nd[0];
                    $dataTmp[$id]->setProductsCount($data[$id]['products_count']);

                    if (count($right) > 0) {
                        while ($right[count($right) - 1] < $nd->getRpos()) {
                            array_pop($right);
                        }
                    }

                    $dataTmp[$id]->setDepth(count($right));

                    $right[] = $nd->getRpos();
                }

                $data = $dataTmp;

            } else {
                // The specified category does not exists
                $data = array();
            }

            $this->saveToCache($data, $this->cachePrefix . '_FullTree', array('category_id' => $categoryId));
        }

        return $data;
    }

    /**
     * Defines the query for categories tree selection
     * 
     * @param int $categoryId Node Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineFullTreeQuery($categoryId)
    {
        $categoryId = intval($categoryId);

        if (0 < $categoryId) {
            $category = $this->getNode($categoryId);
        }

        $qb = $this->createQueryBuilder('c')
            ->addSelect('count(p.product_id) as products_count')
            ->leftJoin('c.category_products', 'p')
            ->groupBy('c.category_id')
            ->orderBy('c.lpos');

        if (isset($category) && $category instanceof $this->className) {
            $qb->andWhere($qb->expr()->between('c.lpos', $category->getLpos(), $category->getRpos()));
        }

        return $this->addEnabledCondition($qb, 'c');
    }

    /**
     * Get category from hash 
     * 
     * @param int $categoryId Category Id
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryFromHash($categoryId)
    {
        $categoryId = intval($categoryId);

        $hash = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_FullTreeHash');

        $data = $this->getFullTree();

        $result = null;

        // Build hash if it isn't built yet
        if (!isset($hash) && is_array($data)) {

            $hash = array();

            foreach ($data as $index => $category) {
                $hash[$category->getCategoryId()] = $index;
            }

            $this->saveToCache($hash, $this->cachePrefix . '_FullTreeHash');
        }

        // Gathering needed category object from hash
        if (isset($hash) && isset($hash[$categoryId]) && isset($data[$hash[$categoryId]])) {
            $result = $data[$hash[$categoryId]];
        }

        return $result;
    }

    /**
     * Get the array of category ancestors
     * 
     * @param mixed $categoryId Node identifier
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodePath($categoryId)
    {
        $categoryId = intval($categoryId);

        $data = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_NodePath', array('category_id' => $categoryId));

        if (!isset($data)) {

            if (0 < $categoryId) {

                $category = $this->getNode($categoryId);

                if (isset($category)) {
                    $data = $this->defineNodePathQuery($categoryId)->getQuery()->getResult();
                }
            }

            if (!isset($data)) {
                $data = array();
            }

            $this->saveToCache($data, $this->cachePrefix . '_NodePath', array('category_id' => $categoryId));

        }

        return $data;
    }

    /**
     * Defines the query for category path selection
     * 
     * @param int $categoryId Node Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineNodePathQuery($categoryId)
    {
        $categoryId = intval($categoryId);

        $category = $this->getNode($categoryId);

        if (isset($category) && $category instanceof $this->className) {

            $qb = $this->createQueryBuilder('n');

            $qb ->andWhere($qb->expr()->lte('n.lpos', $category->getLpos()))
                ->andWhere($qb->expr()->gte('n.rpos', $category->getRpos()));

            $qb->orderby('n.lpos');
        }

        return $qb;
    }

    /**
     * Get the leaf nodes of tree
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getLeafNodes()
    {
        $data = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_LeafNodes');

        if (!isset($data)) {

            $data = $this->defineLeafNodesQuery()->getQuery()->getResult();

            $this->saveToCache($data, $this->cachePrefix . '_LeafNodes');

        }

        return $data;
    }

    /**
     * Defines the query for leaf nodes selection
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLeafNodesQuery()
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.rpos = n.lpos+1');
    }

    /**
     * Get the maximum right position in the tree
     * 
     * @return int
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getMaxRightPos()
    {
        $data = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_MaxRightPos');

        if (!isset($data)) {

            $qb = \XLite\Core\Database::getQB();
            $qb ->select('max(n.rpos) as maxrpos')
                ->from($this->_entityName, 'n');

            $data = $qb->getQuery()->getSingleScalarResult();

            if (isset($data)) {
                $this->saveToCache($data, $this->cachePrefix . '_MaxRightPos');
            
            } else {
                $data = 0;
            }
        }

        return $data;
    }

    /**
     * Add node into the tree before or after specified node on the same level
     * 
     * @param int  $categoryId  Node Id
     * @param bool $placeBefore Flag: true - place new node before specified, false - place after
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addSibling($categoryId, $placeBefore = true)
    {
        $this->ignoreCache = true;

        $errorMsg = null;

        $result = null;

        $categoryId = intval($categoryId);

        $parentCategory = $this->getNode($categoryId);

        if ($parentCategory instanceof $this->className) {

            if ($placeBefore) {
                $relatedIndex = $parentCategory->getLpos();
                $equalFlag = true;
                $newLpos = $relatedIndex;
                $newRpos = $relatedIndex + 1;

            } else {
                $relatedIndex = $parentCategory->getRpos();
                $equalFlag = false;
                $newLpos = $relatedIndex + 1;
                $newRpos = $relatedIndex + 2;
            }

            // Increase lpos field for parent and all right nodes
            $qb = $this->defineUpdateIndexesQuery('lpos', 2, $relatedIndex, $equalFlag);
            $qb->getQuery()->execute();

            // Increase rpos field for parent and all right nodes
            $qb = $this->defineUpdateIndexesQuery('rpos', 2, $relatedIndex, $equalFlag);
            $qb->getQuery()->execute();

            // Create an empty category node in the database
            $result = $this->createNode($newLpos, $newRpos, $errorMsg);

        } else {
            $errorMsg = 'The specified category is not exist';
        }

        if (isset($errorMsg)) {
            \XLite\Core\TopMessage::getInstance()->add(
                $errorMsg,
                \XLite\Core\TopMessage::ERROR
            );
        }

        $this->ignoreCache = false;

        return $result;
    }

    /**
     * Add node into the tree as a child of specified node 
     * 
     * @param int $categoryId Node Id
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChild($categoryId = 0)
    {
        $this->ignoreCache = true;

        $errorMsg = null;

        $result = null;

        $skipUpdate = false;

        $categoryId = intval($categoryId);

        // If category_id is 0 then suppose that parent is root
        if (0 == $categoryId) {
            // get lpos as 0 and rpos as a max(rpos)
            $parentLpos = 0;
            $parentRpos = $this->getMaxRightPos();

            if (0 == $parentRpos) {
                $skipUpdate = true;
            }

        } else {

            // Check if category exists
            $parentCategory = $this->getNode($categoryId);

            if (isset($parentCategory) && $parentCategory instanceof $this->className) {
                // get lpos and rpos from parent category
                $parentLpos = $parentCategory->getLpos();
                $parentRpos = $parentCategory->getRpos();
            
            } else {
                // Category does not exist, return error
                $errorMsg = 'The specified parent category is not exist';
            }
        }


        if (!isset($errorMsg)) {

            if (!$skipUpdate) {

                // Increase lpos field for all right nodes
                $qb = $this->defineUpdateIndexesQuery('lpos', 2, $parentLpos);
                $qb->getQuery()->execute();

                // Increase rpos field for parent and all right nodes
                $qb = $this->defineUpdateIndexesQuery('rpos', 2, $parentLpos);
                $qb->getQuery()->execute();
            }

            // Create an empty category node in the database
            $result = $this->createNode($parentLpos + 1, $parentLpos + 2, $errorMsg);
        }
               
        if (isset($errorMsg)) {
            \XLite\Core\TopMessage::getInstance()->add(
                $errorMsg,
                \XLite\Core\TopMessage::ERROR
            );
        }

        $this->ignoreCache = false;

        return $result;
    }

    /**
     * defineUpdateIndexesQuery 
     * 
     * @param string $index        Index to update: 'lpos' or 'rpos'
     * @param int    $offset       Offset value
     * @param int    $relatedIndex Related index value
     * @param bool   $orEqualFlag  Flag for condition function choosing
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineUpdateIndexesQuery($index, $offset, $relatedIndex, $orEqualFlag = false)
    {
        $gtFunc = 'gt' . ($orEqualFlag ? 'e' : '');
        $alias = 'c';
        $column = $alias . '.' . $index;

        $qb = \XLite\Core\Database::getQB();
        $qb ->update('XLite\Model\Category', $alias)
            ->set($column, $column . ' + :offset')
            ->andWhere(
                // $gtFunc: 'gt' or 'gte'
                $qb->expr()->$gtFunc($column, ':relatedIndex')
            )
            ->setParameters(
                array(
                    'offset'     => $offset,
                    'relatedIndex' => $relatedIndex
                )
            );

        return $qb;
    }

    /**
     * Create a node of category in the database
     * 
     * @param int    $lpos      lpos value
     * @param int    $rpos      rpos value
     * @param string &$errorMsg Variable for error message
     *  
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function createNode($lpos, $rpos, &$errorMsg)
    {
        $node = new \XLite\Model\Category();
        $node->setLpos($lpos);
        $node->setRpos($rpos);

        \XLite\Core\Database::getEM()->persist($node);
        \XLite\Core\Database::getEM()->flush();

        if ($node->getCategoryId() > 0) {
            $result = $node;

        } else {
            $result = null;
            $errorMsg = 'Error of a category creation: object could not be created in the database.';
        }
        
        return $result;
    }

    /**
     * Move node and its subnodes within tree
     * 
     * @param int  $nodeId        Source node Id
     * @param int  $destNodeId    Destination node Id
     * @param bool $attachAsChild Trigger: add as a child or place after destination node
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function moveNode($nodeId, $destNodeId, $attachAsChild = false)
    {
        $this->ignoreCache = true;

        $errorMsg = '';

        $nodeId = intval($nodeId);
        $destNodeId = intval($destNodeId);

        // Get source node data
        $srcNode = $this->getNode($nodeId);

        if ($srcNode->getCategoryId() > 0) {

            $src = $dst = array();

            $src['lpos'] = $srcNode->getLpos();
            $src['rpos'] = $srcNode->getRpos();

            $dst['rpos'] = $dst['lpos'] = 0;

            if (0 < $destNodeId) {
                // Get destination node data
                $destNode = $this->getNode($destNodeId);

                if ($destNode->getCategoryId() > 0) {
                    $dst['lpos'] = $destNode->getLpos();
                    $dst['rpos'] = $destNode->getRpos();
                
                } else {
                    $errorMsg = sprintf('Destination category (%d) specified incorrectly', $destNodeId);
                }
            }

            if (!$attachAsChild && $src['lpos'] - $dst['rpos'] == 1) {
                $errorMsg = sprintf(
                    'Category #%d (%d, %d) is already located after category #%d (%d, %d)', 
                    $nodeId, 
                    $src['lpos'], 
                    $src['rpos'], 
                    $destNodeId, 
                    $dst['lpos'], 
                    $dst['rpos']
                );
            }

            if (empty($errorMsg)) {

                // Mark all nodes within moving subtree as locked
                $qb1 = $this->defineLockNodesQuery($src['lpos'], $src['rpos'], 1);

                // Modify lpos value for affected nodes excluding locked nodes
                $qb2 = \XLite\Core\Database::getQB();
                $qb2->update('XLite\Model\Category', 'c')
                    ->set('c.lpos', 'c.lpos + :offset')
                    ->andwhere('c.locked = 0');

                // Modify rpos value for affected nodes excluding locked nodes
                $qb3 = \XLite\Core\Database::getQB();
                $qb3->update('XLite\Model\Category', 'c')
                    ->set('c.rpos', 'c.rpos + :offset')
                    ->andwhere('c.locked = 0');

                // Offset for applying to the affected nodes excluding nodes within moving subtree
                $adjustmentOffset = ($srcNode->getSubCategoriesCount() + 1) * 2;

                // Attach source node to the destination node as a first child
                if ($attachAsChild) {

                    // Offset for applying to the nodes within moving subtree
                    $nodeOffset = $dst['lpos'] + 1 - $src['lpos'];

                    // If source node the left of destination node...
                    if ($src['lpos'] < $dst['lpos']) {

                        $adjustmentOffset *= -1;
                        $nodeOffset += $adjustmentOffset;

                        $qb2 ->andWhere($qb2->expr()->between('c.lpos', $src['rpos']+1, $dst['lpos']));

                        // If destination node already has children
                        if ($dst['rpos'] - $dst['lpos'] > 1) {
                            $qb3 ->andWhere($qb3->expr()->between('c.rpos', $src['rpos']+1, $dst['lpos']-1));

                        } else {
                            $qb3 ->andWhere($qb3->expr()->between('c.rpos', $src['rpos']+1, $dst['rpos']-1));
                        }

                    } elseif ($src['rpos'] < $dst['rpos']) {
                        // If source node is within destination node's subtree...
                        $qb2 ->andWhere($qb2->expr()->between('c.lpos', $dst['lpos']+1, $src['lpos']-1));
                        $qb3 ->andWhere($qb3->expr()->gt('c.rpos', $dst['lpos']));
                        $qb3 ->andWhere($qb3->expr()->lt('c.lpos', $src['lpos']));

                    } else {
                        // If source node is the right of destination node and not in its subtree...

                        // If destination node already has children
                        if ($dst['rpos'] - $dst['lpos'] > 1) {
                            $qb2 ->andWhere($qb2->expr()->between('c.lpos', $dst['lpos']+1, $src['lpos']));
                            $qb3 ->andWhere($qb3->expr()->between('c.rpos', $dst['lpos']+1, $src['lpos']));

                        } else { 
                            $qb2 ->andWhere($qb2->expr()->between('c.lpos', $dst['rpos'], $src['rpos']));
                            $qb3 ->andWhere($qb3->expr()->between('c.rpos', $dst['rpos'], $src['lpos']));
                        }

                    }
                
                } else {
                    // Place source node after the destination node

                    // Offset for applying to the nodes within moving subtree
                    $nodeOffset = $dst['rpos'] + 1 - $src['lpos'];

                    // If source node the left of destination node...
                    if ($src['lpos'] < $dst['lpos']) {

                        $adjustmentOffset *= -1;
                        $nodeOffset += $adjustmentOffset;

                        $qb2 ->andWhere($qb2->expr()->between('c.lpos', $src['rpos']+1, $dst['rpos']));
                        $qb3 ->andWhere($qb3->expr()->between('c.rpos', $src['rpos']+1, $dst['rpos']));

                    } else {
                        // If source node the right of destination node...
                        $qb2 ->andWhere($qb2->expr()->between('c.lpos', $dst['rpos']+1, $src['lpos']-1));
                        $qb3 ->andWhere($qb3->expr()->between('c.rpos', $dst['rpos']+1, $src['lpos']-1));
                    }
                }

                $qb2 ->setParameter('offset', $adjustmentOffset);
                $qb3 ->setParameter('offset', $adjustmentOffset);

                    
                // Remove lock status and change lpos and rpos values of nodes within moving subtree
                $qb4 = $this->defineLockNodesQuery($src['lpos'], $src['rpos'], 0);
                $qb4->set('c.lpos', 'c.lpos + :offset')
                    ->set('c.rpos', 'c.rpos + :offset')
                    ->setParameter('offset', $nodeOffset);

                // Execute queries
                $qb1->getQuery()->execute();
                $qb2->getQuery()->execute();
                $qb3->getQuery()->execute();
                $qb4->getQuery()->execute();

                \XLite\Core\Database::getEM()->flush();
                \XLite\Core\Database::getEM()->clear();

                // Clean common cache
                $this->cleanCache();
            }

        } else {
            // If source node is not exists
            $errorMsg = 'Category for moving is not specified or does not exists';
        }

        // Prepare diagnostic message if operation could not be complete
        if (!empty($errorMsg)) {
            \XLite\Core\TopMessage::getInstance()->add(
                $errorMsg,
                \XLite\Core\TopMessage::ERROR
            );
        }

        $this->ignoreCache = false;

        return empty($errorMsg);
    }

    /**
     * Define query to mark nodes between specified lpos and rpos as locked
     * 
     * @param int $lpos       Left position
     * @param int $rpos       Right position
     * @param int $lockStatus Required lock status
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineLockNodesQuery($lpos, $rpos, $lockStatus = 1)
    {
        $qb = \XLite\Core\Database::getQB();
        $qb ->update('XLite\Model\Category', 'c')
            ->set('c.locked', $lockStatus ? 1 : 0)
            ->andwhere($qb->expr()->gte('c.lpos', ':lpos'))
            ->andWhere($qb->expr()->lte('c.rpos', ':rpos'))
            ->andWhere($qb->expr()->eq('c.locked', $lockStatus ? 0 : 1))
            ->setParameters(
                array(
                    'lpos'   => intval($lpos),
                    'rpos'   => intval($rpos)
                )
            );

        return $qb;
    }

    /**
     * Get category details
     * 
     * @param int $categoryId Category Id
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory($categoryId)
    {
        $categoryId = intval($categoryId);

        $category = $this->getNode($categoryId);

        return $category;
    }

    /**
     * Get the categories tree
     * 
     * @param int $categoryId Parent category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories($categoryId = 0)
    {
        return $this->getFullTree($categoryId);
    }

    /**
     * Get a plain list of subcategories of the specified category 
     * 
     * @param int $categoryId Parent category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoriesPlainList($categoryId = 0)
    {
        $result = array();

        $categoryId = intval($categoryId);

        if (0 < $categoryId) {
            $cat = $this->getNode($categoryId);
        }

        if (!empty($cat)) {
            $depth = $cat->getDepth() + 1;
            $lpos = $cat->getLpos();
            $rpos = $cat->getRpos();

        } else {
            $depth = 1;
            $lpos = 0;
            $rpos = $this->getMaxRightPos()+1;
        }

        $categories = $this->getFullTree($categoryId);

        if (is_array($categories)) {

            foreach ($categories as $category) {
                if ($category->getDepth() == $depth && $category->getLpos() > $lpos && $category->getRpos() < $rpos) {
                    $result[] = $category;
                }
            }
        }

        return $result;
    }

    /**
     * Get categories path from root to the specified category 
     * 
     * @param int $categoryId Category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryPath($categoryId)
    {
        return $this->getNodePath($categoryId);
    }

    /**
     * Get the parent category of a specified category
     * 
     * @param int $categoryId Category Id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentCategory($categoryId)
    {
        $path = $this->getNodePath($categoryId);

        return count($path) > 1 ? $path[count($path)-2] : null;
    }

    /**
     * Get the category Id of a parent category of a specified category 
     * 
     * @param int $categoryId Category Id
     *  
     * @return int
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentCategoryId($categoryId)
    {
        $result = $this->getParentCategory($categoryId);
        
        return isset($result) ? $result->getCategoryId() : null;
    }

    /**
     * Check if specified category is a leaf node of a categories tree
     * 
     * @param int $categoryId Category Id
     *  
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isCategoryLeafNode($categoryId)
    {
        $result = false;

        $leafNodes = $this->getLeafNodes();

        if (is_array($leafNodes)) {
            foreach ($leafNodes as $node) {
                if ($node->getCategoryId() == $categoryId) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Get the categories list that is assigned to the specified product
     * TODO: rewrite this method or move to the Product model
     * Problem: Category::getProductId() method called when updating category (on flush() calling)
     * 
     * @param int $productId Product Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoriesOfProduct($productId)
    {
        $data = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_categories_of_product', array('product_id' => $productId));

        if (!isset($data)) {

            $data = $this->defineCategoriesOfProduct()->getQuery()->getResult();

            if (!empty($data)) {
                $data = array_shift($data);
            }

            $this->saveToCache($data, $this->cachePrefix . '_categories_of_product', array('product_id' => $productId));
        }

        return $data;
    }

    /**
     * Define the query for product categories selection
     * 
     * @param int $productId Product Id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCategoriesOfProduct($productId)
    {
        return $this->createQueryBuilder('c')
            ->leftJoin('c.category_products', 'cp')
            ->andWhere('cp.product_id = :productId')
            ->setParameter('productId', $productId);
    }

    /**
     * Get category by clean_url
     * 
     * @param string $cleanUrl Clean URL value
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryByCleanUrl($cleanUrl)
    {
        $key = base64_encode($cleanUrl);

        $data = ($this->ignoreCache) ? 
            null : 
            $this->getFromCache($this->cachePrefix . '_ByCleanUrl', array('clean_url' => $key));

        if (!isset($data)) {

            $data = $this->defineCategoryByCleanUrl($cleanUrl)->getQuery()->getResult();

            if (!empty($data)) {
                $data = array_shift($data);

            } else {
                $data = null;
            }
            
            $this->saveToCache($data, $this->cachePrefix . '_ByCleanUrl', array('clean_url' => $key));
        }

        return $data;
    }

    /**
     * Define the query for category selection by specified clean Url
     * 
     * @param string $cleanUrl Category clean Url
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCategoryByCleanUrl($cleanUrl)
    {
        $qb = $this->createQueryBuilder('c')
            ->andWhere('c.clean_url = :cleanUrl')
            ->setParameter('cleanUrl', $cleanUrl);

        return $this->addEnabledCondition($qb, 'c');
    }

    /**
     * Delete category and all subcategories
     * 
     * @param int  $categoryId  Category Id
     * @param bool $subcatsOnly Flag: true - delete subcategories only, false - delete specified node and its subcategories
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCategory($categoryId = 0, $subcatsOnly = false)
    {
        $this->ignoreCache = true;

        $categoriesToDelete = $this->getFullTree($categoryId);

        if (!empty($categoriesToDelete)) {

            // Initialize indexes 
            $rpos = 0;
            $lpos = $this->getMaxRightPos();

            // Calculate offset for indexes recalculation
            $offset = (count($categoriesToDelete) - ($subcatsOnly ? 1 : 0)) * 2;

            foreach ($categoriesToDelete as $category) {

                if (!($subcatsOnly && $categoryId == $category->getCategoryId())) {

                    // Calculate left and right indexes of the removed tree
                    $lpos = ($category->getLpos() < $lpos) ? $category->getLpos() : $lpos;
                    $rpos = ($category->getRpos() > $rpos) ? $category->getRpos() : $rpos;

                    \XLite\Core\Database::getEM()->remove($category);
                }
            }

            \XLite\Core\Database::getEM()->flush();

            // If nodes were removed - recalculate indexes 
            if (0 < $rpos) {

                // Decrease lpos fields for all right nodes
                $qb = $this->defineUpdateIndexesQuery('lpos', -$offset, $lpos);
                $qb->getQuery()->execute();
 
                // Decrease rpos fields for all right nodes
                $qb = $this->defineUpdateIndexesQuery('rpos', -$offset, $rpos);
                $qb->getQuery()->execute();

                // Clean common cache
                $this->cleanCache();
            }
        }

        $this->ignoreCache = false;
    }

    /**
     * Get categories list by product ID
     * 
     * @param int $productId product ID
     *  
     * @return \Doctrine\ORM\PersistentCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllByProductId($productId)
    {
        return $this->createQueryBuilder()
            ->innerJoin('c.category_products', 'cp')
            ->andWhere('cp.product_id = :productId')
            ->setParameter('productId', $productId)
            ->addOrderBy('cp.orderby', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Check categories tree integrity 
     * 
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkTreeIntegrity(&$errorData)
    {       
        $result = true;
        $errorData = array();

        $this->ignoreCache = true;

        // Get full tree
        $categories = $this->getFullTree();

        if (!empty($categories)) {

            $hashLpos = $hashRpos = array();
            $maxIndex = count($categories) * 2;

            // Scan categories tree and generate hashes of indexes
            foreach ($categories as $category) {

                $lpos = $category->getLpos();
                $rpos = $category->getRpos();
                $catId = $category->getCategoryId();

                // Fail if on of indexes exceeds the maximum allowed index value
                if ($lpos > $maxIndex || $rpos > $maxIndex) {
                    $result = false;
                    $errorData = array(
                        'category_id' => $catId,
                        'lpos'        => $lpos,
                        'rpos'        => $rpos,
                        'msg'         => 'One of indexes exceeds the maximum allowed index value (' . $maxIndex . ')'
                    );
                    break;
                }

                // Generate hashes
                if (!isset($hashLpos[$lpos])) {
                    $hashLpos[$lpos] = $catId;

                    if (!isset($hashRpos[$rpos])) {
                        $hashRpos[$rpos] = $catId;

                    } else {
                        // Fail if duplicate of rpos found
                        $result = false;
                        $errorData = array(
                            'category_id' => $catId,
                            'lpos'        => $lpos,
                            'rpos'        => $rpos,
                            'msg'         => 'duplicate of rpos found (#' . $hashRpos[$rpos] . ')'
                        );
                        break;
                    }

                } else {
                    // Fail if duplicate of lpos found
                    $result = false;
                    $errorData = array(
                        'category_id' => $catId,
                        'lpos'        => $lpos,
                        'rpos'        => $rpos,
                        'msg'         => 'duplicate of lpos found (#' . $hashLpos[$lpos] . ')'
                    );
                    break;
                }
            }

            // Check if indexes are consistent
            if ($result && count($hashLpos) == count($hashRpos)) {

                $index = 0;

                // Launch index from 0 to max value while hashes are not empty
                while ($result && $index <= $maxIndex && !empty($hashLpos) && !empty($hashRpos)) {

                    $index++;

                    // Empty hashes on index value if hash for this index found
                    if (isset($hashLpos[$index])) {
                        unset($hashLpos[$index]);

                    } elseif (isset($hashRpos[$index])) {
                        unset($hashRpos[$index]);

                    } else {
                        // Fail if index not found in both hashes
                        $result = false;
                        $errorData = array(
                            'index' => $index,
                            'msg'   => 'index not found in lpos neither rpos'
                        );
                    }
                }

            } elseif ($result) {
                // Fail if hashes have different count of elements
                $result = false;
                $errorData = array(
                    'count(lpos)' => count($hashLpos),
                    'count(rpos)' => count($hashRpos),
                    'msg' => 'the number of unique lpos and rpos indexes differs'
                );
            }
        }

        $this->ignoreCache = false;

        return $result;
    }

    //TODO: All methods below must be rewied and refactored



    /* 
        Parse $data due to the following grammar:
    
            NAME_CHAR ::= ( [^/] | "//" | "||")
            CATEGORY_NAME ::= NAME_CHAR CATEGORY_NAME | NAME_CHAR
            CATEGORY_PATH ::= CATEGORY_NAME "/" CATEGORY_PATH | CATEGORY_NAME
        
        If $allowMiltyCategories == true, then

            DATA ::= CATEGORY_PATH "|" DATA | CATEGORY_PATH
        
        If $allowMiltyCategories == false, then

            DATA ::= CATEGORY_PATH

    */
    function parseCategoryField($data, $allowMiltyCategories) 
    {
        $i = 0;
        $state = "S";
        $path = array();
        $list = array();
        $lastSlash = -1;
        $lastDiv = -1;
        $word = "";
        for ($i=0; $i<=strlen($data); $i++) {
            if ($i == strlen($data)) $char = "";
            else $char = $data{$i};
            if ($char == "/") {
                if ($state == "/") {
                    $word .= "/";
                    $state = "S";
                } else {
                    $state = "/";
                }
            } else if ($char == "|") {
                if ($state == "|") {
                    $word .= "|";
                    $state = "S";
                } else {
                    $state = "|";
                }
            } else {
                if ($state == "/") {
                    $path[] = $word;
                    $word = $char;
                    $state = "S";
                } else if ($state == "|" || $char == "") {
                    $path[] = $word;
                    if ($allowMiltyCategories) {
                        $list[] = $path;
                        $path = array();
                    }
                    $word = $char;
                    $state = "S";
                } else {
                    $word .= $char;
                }
            }
        }
        if ($allowMiltyCategories) 
            return $list;
        else
            return $path;
    }

    /* if $categorySet is an array, creates the string in c1|c2|...|cn format
    due to the specification given above. If $categorySet is a single category,
    creates an export string for the single category in format component1/...
    */
    function createCategoryField($categorySet) 
    {
        if (is_array($categorySet)) {
            $paths = array();
            foreach ($categorySet as $category) {
                $paths[] = $this->createCategoryField($category);
            }
            return implode("|", $paths);
        }
        $path = $categorySet->get('path');
        for ($i = 0; $i<count($path); $i++) {
            $path[$i] = str_replace('/', "//", str_replace("|", "||", $path[$i]->get('name')));
        }
        return implode('/', $path);
    }
    
    function createRecursive($name) 
    {
        if (!is_array($name)) {
            $path = $this->parseCategoryField($name, false);
        } else {
            $path = $name;
        }
        $topID = $this->getComplex('topCategory.category_id');
        $category_id = $topID;
        foreach ($path as $n) {
            $category = new \XLite\Model\Category();
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get('category_id');
                continue;
            }
            $category->set('name', $n);
            $category->set('parent', $category_id);
            $category->create();
            $category_id = $category->get('category_id');
        }
        return new \XLite\Model\Category($category_id);
    }

    function findCategory($path) 
    {
        if (!is_array($path)) {
            $path = $this->parseCategoryField($path, false);
        }
        $topID = $this->getComplex('topCategory.category_id');
        $category_id = $topID;
        foreach ($path as $n) {
            $category = new \XLite\Model\Category();
            if ($category->find("name='".addslashes($n)."' AND parent=$category_id")) {
                $category_id = $category->get('category_id');
                continue;
            }
            return null;
        }
        return new \XLite\Model\Category($category_id);
    }

    function filterRule()
    {
        $result = true;

        if ($this->auth->is('logged')) {
            $membership = $this->auth->getComplex('profile.membership');
        } else {
            $membership = '';
        }
        if (!$this->is('enabled') || trim($this->get('name')) == "" || !$this->_compareMembership($this->get('membership'), $membership)) {
            $result = false;
        }

        return $result;
    }

    function filter() 
    {
        $result = parent::filter(); // default
        if ($result && !$this->xlite->is('adminZone')) {
            if ($this->db->cacheEnabled) {
                global $categoriesFiltered;
                if (!isset($categoriesFiltered) || (isset($categoriesFiltered) && !is_array($categoriesFiltered))) {
                    $categoriesFiltered = array();
                }

                $cid = $this->get('category_id');
                if (isset($categoriesFiltered[$cid])) {
                    return $categoriesFiltered[$cid];
                }
            }

            $result = $this->filterRule();
            if ($result) {
                // check parent categories
                $parent = $this->getParentCategory();
                if (isset($parent)) {
                    $result = $result && \XLite\Model\CachingFactory::getObjectFromCallback(
                        __METHOD__ . $parent->get('category_id'), $parent, 'filter'
                    );
                }
            }

            if ($this->db->cacheEnabled) {
                $categoriesFiltered[$cid] = $result;
            }
        }
        return $result;
    }
    
    function _compareMembership($categoryMembership, $userMembership) 
    {
        return $categoryMembership == 'all' || $categoryMembership == '%' || $categoryMembership == '_%' && $userMembership || $categoryMembership == $userMembership;
    }

    function toXML() 
    {
        $id = "category_" . $this->get('category_id');
        $xml = parent::toXML();
        return "<category id=\"$id\">\n$xml\n</category>\n";
    }
    
    function fieldsToXML() 
    {
        $xml = "";
        if ($this->hasImage()) {
            // include image in XML dump
            $image = $this->getImage();
            if ($image->get('source') == "D") {
                $xml .= "<image><![CDATA[".base64_encode($image->get('data'))."]]></image>";
                
            }
        }
        return parent::fieldsToXML() . $xml;
    }
}
