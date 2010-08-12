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

namespace XLite\Model\Repo\Image\Product;

/**
 * Product detailed image
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Detailed extends \XLite\Model\Repo\Base\Image
{
    /**
     * Returns the name of the directory within 'root/images' where images stored
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getStorageName()
    {
        return 'product_detailed_images';
    }

    /**
     * Find all active detailed images by product id
     *
     * @param integer $productId Product id
     *
     * @return \Doctrine\Common\Collection\ArrayCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findActiveByProductId($productId)
    {
        $data = $this->defineActiveByProductIdQuery(intval($productId))
            ->getQuery()
            ->getResult();

        $this->detachList($data);

        return $data;
    }

    /**
     * Define query for findActiveByProductId() method
     * 
     * @param integer $productId Product id
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineActiveByProductIdQuery($productId)
    {
        return $this->createQueryBuilder('i')
            ->addSelect('p')
            ->innerJoin('i.product', 'p', 'WITH', 'p.product_id = :productId')
            ->andWhere('i.enabled = :true')
            ->setParameter('productId', $productId)
            ->setParameter('true', true);
    }
}
