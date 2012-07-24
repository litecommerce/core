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

namespace XLite\Module\CDev\AmazonS3Images\Model\Repo\Base;

/**
 * Image abstract repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 *
 * @MappedSuperclass
 */
abstract class Image extends \XLite\Model\Repo\Base\Image implements \XLite\Base\IDecorator
{
    /**
     * Get managed image repositories 
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    public static function getManagedRepositories()
    {
        return array(
            'XLite\Model\Image\Product\Image',
            'XLite\Model\Image\Category\Image',
        );
    }

    /**
     * Count S3 images 
     * 
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function countS3Images()
    {
        return intval($this->defineCountS3ImagesQuery()->getSingleScalarResult());
    }

    /**
     * Count non-S3 images
     *
     * @return integer
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function countNoS3Images()
    {
        return intval($this->defineCountNoS3ImagesQuery()->getSingleScalarResult());
    }

    /**
     * Find S3 images
     *
     * @param integer $limit Limit OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function findS3Images($limit = null)
    {
        return $this->defineFindS3ImagesQuery($limit)->getResult();
    }

    /**
     * Find non-S3 images
     *
     * @param integer $limit Limit OPTIONAL
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.19
     */
    public function findNoS3Images($limit = null)
    {
        return $this->defineFindNoS3ImagesQuery($limit)->getResult();
    }

    /**
     * Define query for countS3Images() method
     * 
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function defineCountS3ImagesQuery()
    {
        $qb = $this->defineCountQuery();
        $alias = $this->getMainAlias($qb);

        return $qb->andWhere($alias . '.storageType = :type')
            ->setParameter('type', \XLite\Model\Base\Image::STORAGE_S3);
    }

    /**
     * Define query for countNoS3Images() method
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function defineCountNoS3ImagesQuery()
    {
        $qb = $this->defineCountQuery();
        $alias = $this->getMainAlias($qb);

        return $qb->andWhere($alias . '.storageType != :type')
            ->setParameter('type', \XLite\Model\Base\Image::STORAGE_S3);
    }

    /**
     * Define query for findS3Images() method
     *
     * @param integer $limit Limit
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function defineFindS3ImagesQuery($limit)
    {
        $qb = $this->createQueryBuilder('i')
            ->andWhere('i.storageType = :type')
            ->setParameter('type', \XLite\Model\Base\Image::STORAGE_S3);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

    /**
     * Define query for findNoS3Images() method
     *
     * @param integer $limit Limit
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function defineFindNoS3ImagesQuery($limit)
    {
        $qb = $this->createQueryBuilder('i')
            ->andWhere('i.storageType != :type')
            ->setParameter('type', \XLite\Model\Base\Image::STORAGE_S3);

        if ($limit) {
            $qb->setMaxResults($limit);
        }

        return $qb;
    }

}
