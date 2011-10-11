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

namespace XLite\Model\Repo\Base;

/**
 * Storage abstract repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
abstract class Storage extends \XLite\Model\Repo\ARepo
{
    /**
     * Get storage name
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    abstract public function getStorageName();

    /**
     * Get file system images storage root path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    abstract public function getFileSystemRoot();

    /**
     * Get web images storage root path
     *
     * @return string
     * @see    ____func_see____
     * @since  1.0.10
     */
    abstract public function getWebRoot();

    /**
     * Check - store remote image into local file system or not
     *
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.10
     */
    public function isStoreRemote()
    {
        return false;
    }

    /**
     * Get allowed file system root list
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function getAllowedFileSystemRoots()
    {
        return array();
    }

    // {{{ Remove cross-repository files

    /**
     * Has one or more entity with specified path
     * 
     * @param string                    $path   Path
     * @param \XLite\Model\Base\Storage $entity Exclude entity
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function findOneByFullPath($path, \XLite\Model\Base\Storage $entity)
    {
        $id = ($this->getEntityName() == get_class($entity) || is_subclass_of($entity, $this->getEntityName()))
            ? $entity->getId()
            : null;

        $found = 0 < intval($this->defineFindOneByFullPathQuery($path, true, $id)->getSingleScalarResult());
        if (!$found) {
            $root = $this->getFileSystemRoot();
            if (0 == strncmp($root, $path, strlen($root))) {
                $path = substr($path, strlen($root));
                $found = 0 < intval($this->defineFindOneByFullPathQuery($path, false, $id)->getSingleScalarResult());
            }
        }

        return $found;
    }

    /**
     * Find storages by full path 
     * 
     * @param string                    $path   Path
     * @param \XLite\Model\Base\Storage $entity Exclude path
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function findByFullPath($path, \XLite\Model\Base\Storage $entity)
    {
        $id = ($this->getEntityName() == get_class($entity) || is_subclass_of($entity, $this->getEntityName()))
            ? $entity->getId()
            : null;

        $absolute = $this->defineFindByFullPathQuery($path, true, $id)->getResult();
        $root = $this->getFileSystemRoot();
        if (0 == strncmp($root, $path, strlen($root))) {
            $path = substr($path, strlen($root));
            $relative = $this->defineFindByFullPathQuery($path, false, $id)->getResult();

        } else {
            $relative = array();
        }

        return array_merge($absolute, $relative);
    }

    /**
     * Check - allow remove path or not
     * 
     * @param string                    $path   Path
     * @param \XLite\Model\Base\Storage $entity Exclude entity
     *  
     * @return boolean
     * @see    ____func_see____
     * @since  1.0.11
     */
    public function allowRemovePath($path, \XLite\Model\Base\Storage $entity)
    {
        $result = true;

        foreach ($this->defineStorageRepositories() as $class) {
            if (\XLite\Core\Database::getRepo($class)->findOneByFullPath($path, $entity)) {
                $result = false;
                break;
            }
        }

        return $result;
    }

    /**
     * Define all storage-based repositories classes list
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function defineStorageRepositories()
    {
        return array(
            'XLite\Model\Image\Product\Image',
            'XLite\Model\Image\Category\Image',
        );
    }

    /**
     * Define query for findOneByFull() method
     * 
     * @param string  $path     Path
     * @param boolean $absolute Absolute path flag
     * @param integer $id       Excluding entity id OPTIONAL
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function defineFindOneByFullPathQuery($path, $absolute, $id = null)
    {
        $idColumn = $this->_class->identifier[0];

        $qb = $this->createQueryBuilder('s')
            ->select('COUNT(s.' . $idColumn . ')')
            ->andWhere('s.path = :path AND s.storageType = :stype')
            ->setParameter('path', $path)
            ->setParameter(
                'stype',
                $absolute ? \XLite\Model\Base\Storage::STORAGE_ABSOLUTE : \XLite\Model\Base\Storage::STORAGE_RELATIVE
            );

        if ($id) {
            $qb->andWhere('s.' . $idColumn . ' != :id')->setParameter('id', $id);
        }

        return $qb;
    }

    /**
     * Define query for findByFullPath() method
     *
     * @param string  $path     Path
     * @param boolean $absolute Absolute path flag
     * @param integer $id       Excluding entity id OPTIONAL
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.11
     */
    protected function defineFindByFullPathQuery($path, $absolute, $id = null)
    {
        $qb = $this->createQueryBuilder('s')
            ->andWhere('s.path = :path AND s.storageType = :stype')
            ->setParameter('path', $path)
            ->setParameter(
                'stype',
                $absolute ? \XLite\Model\Base\Storage::STORAGE_ABSOLUTE : \XLite\Model\Base\Storage::STORAGE_RELATIVE
            );

        if ($id) {
            $qb->andWhere('s.id != :id')->setParameter('id', $id);
        }

        return $qb;
    }

    // }}}

}
