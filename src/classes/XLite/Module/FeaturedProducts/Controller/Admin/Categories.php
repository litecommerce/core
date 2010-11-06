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
 * @subpackage View
 * @author     Creative Development LLC <info@cdev.ru> 
 * @copyright  Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version    SVN: $Id$
 * @link       http://www.litecommerce.com/
 * @see        ____file_see____
 * @since      3.0.0
 */

namespace XLite\Module\FeaturedProducts\Controller\Admin;

/**
 * \XLite\Module\FeaturedProducts\Controller\Admin\Categories
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Categories extends \XLite\Controller\Admin\Categories implements \XLite\Base\IDecorator
{

    /**
     * FIXME- backward compatibility
     *
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('category_id');

    /**
     * doActionAddFeaturedProducts 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionAddFeaturedProducts()
    {
        if (isset(\XLite\Core\Request::getInstance()->product_ids)) {

            $pids = array_keys(\XLite\Core\Request::getInstance()->product_ids);

            $products = \XLite\Core\Database::getRepo('\XLite\Model\Product')
                ->findByIds($pids);

            if (!$this->categoryId) {
                $this->categoryId = \XLite\Core\Database::getRepo('\XLite\Model\Category')->getRootCategoryId();
            }

            $category = \XLite\Core\Database::getRepo('\XLite\Model\Category')->find($this->categoryId);

            // Retreive existing featured products list of that category

            $existingLinksIds = array();
            $existingLinks = $this->getFeaturedProductsList();
            if ($existingLinks) {
                foreach ($existingLinks as $k => $v) {
                    $existingLinksIds[] = $v->getProduct()->getProductId();
                }
            }

            if ($products) {

                foreach ($products as $product) {

                    if (in_array($product->getProductId(), $existingLinksIds)) {
                        \XLite\Core\TopMessage::getInstance()->add(
                            'The product SKU#"' . $product->getSku() . '" is already set as featured for the category',
                            \XLite\Core\TopMessage::WARNING
                        );
                        continue;
                    }

                    $fp = new \XLite\Module\FeaturedProducts\Model\FeaturedProduct();
                    $fp->setProduct($product);

                    if ($category) {
                        $fp->setCategory($category);
                    }
                    \XLite\Core\Database::getEM()->persist($fp);
                }
            }

            \XLite\Core\Database::getEM()->flush();
        }
    }

    /**
     * Process action 'update_featured_products'
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdateFeaturedProducts()
    {
        // Delete featured products if it was requested
        $toDelete = \XLite\Core\Request::getInstance()->delete;

        if ($toDelete) {

            $records = \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
                ->findByIds(array_keys($toDelete));

            if ($records) {
                foreach ($records as $rec) {
                    \XLite\Core\Database::getEM()->remove($rec);
                }
            }
        }

        // Update order_by of featured products list is it was requested
        $orderbys = \XLite\Core\Request::getInstance()->orderbys;

        if ($orderbys) {
            $records = \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
                ->findByIds(array_keys($orderbys));

            if ($records) {
                foreach ($records as $rec) {
                    $cell = array();
                    $cell['order_by'] = abs(intval($orderbys[$rec->getId()]));
                    $rec->map($cell);
                    \XLite\Core\Database::getEM()->persist($rec);
                }
            }
        }
        \XLite\Core\Database::getEM()->flush();
    }

    /**
     * Get search conditions
     *
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getConditions()
    {
        $searchParams = $this->session->get(\XLite\Module\FeaturedProducts\View\Admin\FeaturedProducts::getSessionCellName());

        if (!is_array($searchParams)) {
            $searchParams = array();
        }

        return $searchParams;
    }

    /**
     * Get search condition parameter by name
     * 
     * @param string $paramName 
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getCondition($paramName)
    {
        $searchParams = $this->getConditions();

        if (isset($searchParams[$paramName])) {
            $return = $searchParams[$paramName];
        }

        return isset($searchParams[$paramName])
            ? $searchParams[$paramName]
            : null;
    }

    /**
     * Get featured products list
     *
     * @return array of \XLite\Module\FeaturedProducts\Model\FeaturedProduct objects
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getFeaturedProductsList()
    {
        return \XLite\Core\Database::getRepo('\XLite\Module\FeaturedProducts\Model\FeaturedProduct')
            ->getFeaturedProducts($this->category_id);
    }

}
