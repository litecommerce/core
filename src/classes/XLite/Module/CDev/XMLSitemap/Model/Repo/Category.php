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
 * @since     1.0.12
 */

namespace XLite\Module\CDev\XMLSitemap\Model\Repo;

/**
 * Category repository
 * 
 * @see   ____class_see____
 * @since 1.0.12
 */
abstract class Category extends \XLite\Model\Repo\Category implements \XLite\Base\IDecorator
{
    /**
     * Count categories as sitemaps links 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function countCategoriesAsSitemapsLinks()
    {
        return intval($this->defineCountQuery()->andWhere('c.parent IS NOT NULL')->getSingleScalarResult());
    }

    /**
     * Find one as sitemap link 
     * 
     * @param integer $position Position
     *  
     * @return \XLite\Model\Category
     * @see    ____func_see____
     * @since  1.0.12
     */
    public function  findOneAsSitemapLink($position)
    {
        return $this->createPureQueryBuilder()
            ->andWhere('c.parent IS NOT NULL')
            ->setMaxResults(1)
            ->setFirstResult($position)
            ->getSingleResult();
    }
}

