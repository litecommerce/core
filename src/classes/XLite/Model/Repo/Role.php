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
 * @since     1.0.17
 */

namespace XLite\Model\Repo;

/**
 * Role repository 
 * 
 * @see   ____class_see____
 * @since 1.0.17
 */
class Role extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Find one role byN nme 
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\Role
     * @see    ____func_see____
     * @since  1.0.19
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
     * @see    ____func_see____
     * @since  1.0.0
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
     * Define query for findOneByName() method
     * 
     * @param string $name Name
     *  
     * @return \XLite\Model\QueryBuilder\AQueryBuilder
     * @see    ____func_see____
     * @since  1.0.19
     */
    protected function defineFindOneByNameQuery($name)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('translations.name = :name')
            ->setParameter('name', $name)
            ->setMaxResults(1);
    }
}
