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

        $list[$this->className . '_Details'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->className . '_FullTree'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->className . '_FullTreeHash'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->className . '_NodePath'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_id')
        );

        $list[$this->className . '_ByCleanUrl'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('clean_url')
        );

        $list[$this->className . '_categories_of_product'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('product_id')
        );

        $list[$this->className . '_LeafNodes'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL
        );

        $list[$this->className . '_MaxRightPos'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL
        );

        return $list;
    }

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
        $data = $this->getFromCache($this->className . '_Details', array('category_id' => $categoryId));

        if (!isset($data)) {

            $data = $this->defineNodeQuery($categoryId)->getQuery()->getResult();

            if (!empty($data)) {
                $data = array_shift($data);
            }

            $this->saveToCache($data, $this->className . '_Details', array('category_id' => $categoryId));
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
        $qb = $this->createQueryBuilder('c')
            ->addSelect('m', 'i')
            ->leftJoin('c.membership', 'm')
            ->leftJoin('c.image', 'i')
            ->where('c.category_id = :categoryId')
            ->setMaxResults(1)
            ->setParameter('categoryId', $categoryId);

        $this->addEnabledCondition($qb, 'c');

        return $qb;
    }

    /**
     * Get the categories tree
     * 
     * @param int $categoryId Node Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getFullTree($categoryId = 0)
    {
        $data = $this->getFromCache($this->className . '_FullTree', array('category_id' => $categoryId));

        if (!isset($data)) {

            $data = $this->defineFullTreeQuery($categoryId)->getQuery()->getResult();

            $right = array($this->getMaxRightPos());
            $dataTmp = array();

            // Calculate categorys depth
            foreach ($data as $id => $nd) {

                $dataTmp[$id] = $nd = $nd[0];
                $dataTmp[$id]->products_count = $data[$id]['products_count'];

                if (count($right) > 0) {
                    while ($right[count($right) - 1] < $nd->rpos) {
                        array_pop($right);
                    }

                }

                $dataTmp[$id]->depth = count($right);

                $right[] = $nd->rpos;

                // Calculate the number of subcategorys
                $dataTmp[$id]->subCategoriesCount = ($nd->rpos - $nd->lpos - 1) / 2;
            }

            $data = $dataTmp;

            $this->saveToCache($data, $this->className . '_FullTree', array('category_id' => $categoryId));
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
        if (!is_null($categoryId)) {
            $category = $this->getNode($categoryId);
        }

        $qb = $this->createQueryBuilder('c')
            ->addSelect('count(p.product_id) as products_count')
            ->leftJoin('c.products', 'p')
            ->groupBy('c.category_id')
            ->orderBy('c.lpos');

        if (isset($category) && $category instanceof $this->className) {
            $qb->where($qb->expr()->between('c.lpos', $category->lpos, $category->rpos));
        }

        $this->addEnabledCondition($qb, 'c');

        return $qb;
    }

    /**
     * Get category from hash 
     * 
     * @param int $categoryId Category Id
     *  
     * @return \XLite\Model\Category
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategoryFromHash($categoryId)
    {
        $hash = $this->getFromCache($this->className . '_FullTreeHash');
        $data = $this->getFullTree();

        // Build hash if it isn't built yet
        if (!isset($hash) && is_array($data)) {

            $hash = array();

            foreach ($data as $index => $category) {
                $hash[$category->category_id] = $index;
            }

            $this->saveToCache($hash, $this->className . '_FullTreeHash');
        }

        if (isset($hash) && isset($hash[$categoryId]) && isset($data[$hash[$categoryId]])) {
            $result = $data[$hash[$categoryId]];
        
        } else {
            $result = new \XLite\Model\Category;
        }

        return $result;
    }

    /**
     * Get the array of category ancestors
     * 
     * @param mixed $categoryId Node identifier
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getNodePath($categoryId)
    {
        $data = $this->getFromCache($this->className . '_NodePath', array('category_id' => $categoryId));

        if (!isset($data) && !is_null($qb = $this->defineNodePathQuery($categoryId))) {

            $data = $qb->getQuery()->getResult();

            $this->saveToCache($data, $this->className . '_NodePath', array('category_id' => $categoryId));

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
        $qb = null;

        if (!is_null($categoryId)) {
            $category = $this->getNode($categoryId);
        }

        if (isset($category) && $category instanceof $this->className) {

            $qb = $this->createQueryBuilder('n');

            $qb->where(
                $qb->expr()->andx(
                    $qb->expr()->lte('n.lpos', $category->lpos),
                    $qb->expr()->gte('n.rpos', $category->rpos)
                )
            );

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
        $data = $this->getFromCache($this->className . '_LeafNodes');

        if (!isset($data)) {

            $data = $this->defineLeafNodesQuery()->getQuery()->getResult();

            $this->saveToCache($data, $this->className . '_LeafNodes');

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
            ->where('n.rpos = n.lpos+1');
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
        $data = $this->getFromCache($this->className . '_MaxRightPos');

        if (!isset($data)) {

            $qb = \XLite\Core\Database::getQB();
            $qb ->select('max(n.rpos) as maxrpos')
                ->from($this->_entityName, 'n');

            $data = $qb->getQuery()->getSingleScalarResult();

            $this->saveToCache($data, $this->className . '_MaxRightPos');
        }

        return $data;
    }

    /**
     * Add node into the tree before specified node
     * 
     * @param int $categoryId Node Id
     *  
     * @return \XLite\Model\Category or false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addBefore($categoryId)
    {
        $result = false;

        $parentCategory = $this->getNode($categoryId);

        if ($parentCategory instanceof $this->className) {

            $parentLpos = $parentCategory->lpos;

            // Increase lpos field for parent and all right nodes
            $qb = \XLite\Core\Database::getQB()
                ->update('XLite\Model\Category', 'n')
                ->set('n.lpos', 'n.lpos + :offset')
                ->where(
                    $qb->expr()->gte('n.lpos', ':parentLpos')
                )
                ->setParameter(
                    array(
                        'offset'     => 2,
                        'parentLpos' => $parentLpos,
                    )
                );

            $qb->getQuery()->execute();

            // Increase rpos field for parent and all right nodes
            $qb = \XLite\Core\Database::getQB()
                ->update('XLite\Model\Category', 'n')
                ->set('n.rpos', 'n.rpos + :offset')
                ->where(
                    $qb->expr()->gte('n.rpos', ':parentLpos')
                )
                ->setParameters(
                    array(
                        'offset'     => 2,
                        'parentLpos' => $parentLpos,
                    )
                );
            $qb->getQuery()->execute();

            $newCategory = new \XLite\Model\Category();
            $newCategory->lpos = $parentLpos;
            $newCategory->rpos = $parentLpos + 1;
            \XLite\Core\Database::getEM()->persist($newCategory);
            \XLite\Core\Database::getEM()->flush();

            $result = $newCategory;

            if (is_null($result->category_id)) {
                \XLite\Core\TopMessage::getInstance()->add(
                    'Error of a new category creation',
                    \XLite\Core\TopMessage::ERROR
                );
            }
        }

        return $result;
    }

    /**
     * Add node into the tree after specified node
     * 
     * @param int $categoryId Node Id
     *  
     * @return \XLite\Model\Category or false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addAfter($categoryId)
    {
        $result = false;

        $parentCategory = $this->getNode($categoryId);

        if ($parentCategory instanceof $this->className) {

            $parentRpos = $parentCategory->rpos;

            // Increase lpos field for all right nodes
            $qb = \XLite\Core\Database::getQB()
                ->update('XLite\Model\Category', 'n')
                ->set('n.lpos', 'n.lpos + :offset')
                ->where(
                    $qb->expr()->gt('n.lpos', ':parentRpos')
                )
                ->setParameters(
                    array(
                        'offset'     => 2,
                        'parentRpos' => $parentRpos
                    )
                );
            $qb->getQuery()->execute();

            // Increase rpos field for all right nodes
            $qb = \XLite\Core\Database::getQB();
            $qb ->update('XLite\Model\Category', 'n')
                ->set('n.rpos', 'n.rpos + :offset')
                ->where(
                    $qb->expr()->gt('n.rpos', ':parentRpos')
                )
                ->setParameters(
                    array(
                        'offset' => 2,
                        'parentRpos' => $parentRpos
                    )
                );
            $qb->getQuery()->execute();

            $newCategory = new \XLite\Model\Category();
            $newCategory->lpos = $parentRpos + 1;
            $newCategory->rpos = $parentRpos + 2;
            \XLite\Core\Database::getEM()->persist($newCategory);
            \XLite\Core\Database::getEM()->flush();

            $result = $newCategory;

            if (is_null($result->category_id)) {
                \XLite\Core\TopMessage::getInstance()->add(
                    'Error of a new category creation',
                    \XLite\Core\TopMessage::ERROR
                );
            }
        }

        return $result;
    }

    /**
     * Add node into the tree as a child of specified node 
     * 
     * @param int $categoryId Node Id
     *  
     * @return \XLite\Model\Category or false
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function addChild($categoryId)
    {
        $result = false;
        $skipUpdate = false;

        if ($this->isCategoryLeafNode($categoryId)) {

            $parentCategory = $this->getNode($categoryId);
        
            if ($parentCategory instanceof $this->className) {
                // Add category as a child of a real category:
                // get lpos and rpos from parent category
                $parentLpos = $parentCategory->lpos;
                $parentRpos = $parentCategory->rpos;
            }

        } else {
            // Add category to the root level:
            // get lpos as 0 and rpos as a max(rpos)
            $parentLpos = 0;
            $parentRpos = $this->getMaxRightPos();

            if (0 === $parentRpos) {
                $skipUpdate = true;
            }
        }

        if (!$skipUpdate) {

            // Increase lpos field for all right nodes
            $qb = \XLite\Core\Database::getQB();
            $qb ->update('XLite\Model\Category', 'n')
                ->set('n.lpos', 'n.lpos + :offset')
                ->where(
                    $qb->expr()->gt('n.lpos', ':parentLpos')
                )
                ->setParameters(
                    array(
                        'offset' => 2,
                        'parentLpos' => $parentLpos
                    )
                );

            $qb->getQuery()->execute();

            // Increase rpos field for parent and all right nodes
            $qb = \XLite\Core\Database::getQB();
            $qb ->update('XLite\Model\Category', 'n')
                ->set('n.rpos', 'n.rpos + :offset')
                ->where(
                    $qb->expr()->gte('n.rpos', ':parentRpos')
                )
                ->setParameters(
                    array(
                        'offset' => 2,
                        'parentRpos' => $parentRpos
                    )
                );
            $qb->getQuery()->execute();
        }

        $newCategory = new \XLite\Model\Category();
        $newCategory->lpos = $parentLpos + 1;
        $newCategory->rpos = $parentLpos + 2;

        \XLite\Core\Database::getEM()->persist($newCategory);
        \XLite\Core\Database::getEM()->flush();

        $result = $newCategory;

        if (is_null($result->category_id)) {
            \XLite\Core\TopMessage::getInstance()->add(
                'Error of a new category creation',
                \XLite\Core\TopMessage::ERROR
            );
        }
    
        return $result;
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
        $category = $this->getNode($categoryId);

        if (empty($category)) {
            $category = new \XLite\Model\Category;    
        }

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
    public function getCategories($categoryId = null)
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
    public function getCategoriesPlainList($categoryId = null)
    {
        $result = array();

        if (!is_null($categoryId)) {
            $cat = $this->getNode($categoryId);
        }

        if (!empty($cat)) {
            $depth = $cat->depth + 1;
            $lpos = $cat->lpos;
            $rpos = $cat->rpos;

        } else {
            $depth = 1;
            $lpos = 0;
            $rpos = $this->getMaxRightPos()+1;
        }

        $categories = $this->getFullTree($categoryId);

        if (is_array($categories)) {

            foreach($categories as $category) {

                if ($category->depth == $depth && $category->lpos > $lpos && $category->rpos < $rpos) {
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
        return (count($path) > 1) ? $path[count($path)-2] : new \XLite\Model\Category();
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
        return $result->category_id;
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
                if ($node->category_id == $categoryId) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * Get the categories list that is assigned to the specified product
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
        $data = $this->getFromCache($this->className . '_categories_of_product', array('product_id' => $productId));

        if (!isset($data)) {

            $data = $this->defineCategoriesOfProduct()->getQuery()->getResult();

            if (!empty($data)) {
                $data = array_shift($data);
            }

            $this->saveToCache($data, $this->className . '_categories_of_product', array('product_id' => $productId));
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
            ->leftJoin('c.products', 'cp')
            ->where('cp.product_id = :productId')
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

        $data = $this->getFromCache($this->className . '_ByCleanUrl', array('clean_url' => $key));

        if (!isset($data)) {

            $data = $this->defineCategoryByCleanUrl($cleanUrl)->getQuery()->getResult();

            if (!empty($data)) {
                $this->saveToCache($data, $this->className . '_ByCleanUrl', array('clean_url' => $key));
            }
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
        return $this->createQueryBuilder('c')
            ->where('c.clean_url = :cleanUrl')
            ->setParameter('cleanUrl', $cleanUrl);
    }

    /**
     * Delete category and all subcategories
     * 
     * @param int $categoryId Category Id
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function deleteCategory($categoryId = 0, $subcatsOnly = false)
    {
        $categoriesToDelete = $this->getFullTree($categoryId);

        if (!empty($categoriesToDelete)) {

            foreach ($categoriesToDelete as $category) {

                if (!($subcatsOnly && $categoryId == $category->category_id)) {
                    \XLite\Core\Database::getEM()->remove($category);
                }
            }

            \XLite\Core\Database::getEM()->flush();
        }
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
