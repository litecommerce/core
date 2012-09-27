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
 * Role repository 
 * 
 */
class Role extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Find one role by permisssion code
     *
     * @param string $code Permission code
     *
     * @return \XLite\Model\Role
     */
    public function findOneByPermissionCode($code)
    {
        return $this->defineFindOneByPermissionCodeQuery($code)->getSingleResult();
    }

    /**
     * Find one role by name 
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\Role
     */
    public function findOneByName($name)
    {
        return $this->defineFindOneByNameQuery($name)->getSingleResult();
    }

    /**
     * Find one by record
     *
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model OPTIONAL
     *
     * @return \XLite\Model\AEntity|void
     */
    public function findOneByRecord(array $data, \XLite\Model\AEntity $parent = null)
    {
        $model = parent::findOneByRecord($data, $parent);

        if (!$model && !empty($data['translations'])) {
            foreach ($data['translations'] as $translation) {
               $model = $this->findOneByName($translation['name']) ;
                if ($model) {
                    break;
                }
            }
        }

        return $model;
    }

    /**
     * Find one root-based role
     * 
     * @return \XLite\Model\Role
     */
    public function findOneRoot()
    {
        return $this->defineFindOneRootQuery()->getSingleResult();
    }

    /**
     * Define query for findOneByPermissionCode() method
     *
     * @param string $code Permission code
     *
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindOneByPermissionCodeQuery($code)
    {
        return $this->createQueryBuilder('r')
            ->linkInner('r.permissions')
            ->andWhere('permissions.code = :code')
            ->setParameter('code', $code)
            ->setMaxResults(1);
    }

    /**
     * Define query for findOneByName() method
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindOneByNameQuery($name)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('translations.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1);
    }

    /**
     * Define query for findOneRoot() method
     * 
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     */
    protected function defineFindOneRootQuery()
    {
        return $this->createQueryBuilder('r')
            ->linkInner('r.permissions')
            ->andWhere('permissions.code = :root')
            ->setMaxResults(1)
            ->setParameter('root', \XLite\Model\Role\Permission::ROOT_ACCESS);
    }
}
