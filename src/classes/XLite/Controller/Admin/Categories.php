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
 * @subpackage Controller
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Controller\Admin;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Categories extends Catalog
{
    /**
     * getModelObject
     *
     * @return \XLite\Model\AModel
     * @access protected
     * @since  3.0.0
     */
    protected function getModelObject()
    {
        return $this->getCategory();
    }


    /**
     * category 
     * 
     * @var    mixed
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $category = array();

    public function init() {
        if ('delete' == \XLite\Core\Request::getInstance()->mode) {
            $deleteMode = 'delete' . ('1' == \XLite\Core\Request::getInstance()->subcats ? '_subcats' : '');
            $this->set('deleteMode', $deleteMode);
        }
    }

    /**
     * Get categories list
     * 
     * @param int $categoryId Category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategories($categoryId = null)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategories($categoryId ?: $this->getCategoryId());
    }

    /**
     * Get subcategories list
     * 
     * @param int $categoryId Category Id
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getSubcategories($categoryId)
    {
        $categories = $this->getCategories($categoryId);

        array_shift($categories);

        return $categories;
    }

    /**
     * Get category data
     * 
     * @param int $categoryId Category Id
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCategory($categoryId = null)
    {
        if (!isset($this->category[$categoryId])) {
            $this->category[$categoryId] = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategory($categoryId ?: $this->getCategoryId());
        }

        return $this->category[$categoryId];
    }

    /**
     * Get parent category 
     * 
     * @param int $categoryId Category Id
     *  
     * @return \XLite\Model\Category
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getParentCategory($categoryId = null)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($categoryId ?: $this->getCategoryId());
    }

    public function isCategoryLeafNode($categoryId = null)
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Category')->isCategoryLeafNode($categoryId ?: $this->getCategoryId());
    }

    /**
     * Get all memberships
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMemberships()
    {
        return \XLite\Core\Database::getRepo('XLite\Model\Membership')->findAllMemberships();
    }

    public function isRootCategories($categories)
    {
        $result = false;

        if (!empty($categories) && is_array($categories)) {

            $counter = 0;

            foreach($categories as $category) {

                $counter += (1 == $category->getDepth() ? 1 : 0);

                if ($counter > 1) {
                    $result = true;
                    break;
                }
            }
        }

        return $result;
    }

    /**
     * 'delete' action: Delete category and all subcategories
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_delete()
    {
        $this->deleteCategories(false);
    }

    /**
     * 'delete_subcats' action: Delete subcategories only
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_delete_subcats()
    {
        $this->deleteCategories(true);
    }

    /**
     * Delete subcategories method 
     * 
     * @param bool $subcategoriesOnly Delete subcategories only flag
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function deleteCategories($subcategoriesOnly = false)
    {
        $categoryId = $this->getCategoryId();

        if ($subcategoriesOnly) {
            $redirectParam = '&category_id=' . $categoryId;

        } else {
            $parentCategory = \XLite\Core\Database::getRepo('XLite\Model\Category')->getParentCategory($categoryId);
            $redirectParam = (!is_null($parentCategory->getCategoryId()) ? '&category_id=' . $parentCategory->getCategoryId() : '');
        }

        \XLite\Core\Database::getRepo('XLite\Model\Category')->deleteCategory($categoryId, $subcategoriesOnly);

        $this->redirect('admin.php?target=categories' . $redirectParam);
    }

    /**
     * 'move_after' action: Move category after other category
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_move_after()
    {
        $categoryId = $this->getCategoryId();
        $move2CategoryId = \XLite\Core\Request::getInstance()->moveTo;

        \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $move2CategoryId);

        $this->redirect('admin.php?target=categories');
    }

    /**
     * 'move_as_child' action: Make category child of other category
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function action_move_as_child()
    {
        $categoryId = $this->getCategoryId();
        $move2CategoryId = \XLite\Core\Request::getInstance()->moveTo;

        \XLite\Core\Database::getRepo('XLite\Model\Category')->moveNode($categoryId, $move2CategoryId, true);

        $this->redirect('admin.php?target=categories');
    }

    /**
     * Prepare location path from category path 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLocationPath()
    {
        $result = array();

        $categoryPath = \XLite\Core\Database::getRepo('XLite\Model\Category')->getCategoryPath($this->getCategoryId());

        if (is_array($categoryPath)) {
            
            foreach ($categoryPath as $category) {
                $result[$category->name] = 'admin.php?target=categories&category_id=' . $category->getCategoryId();
            }
        }

        return $result;
    }


}
