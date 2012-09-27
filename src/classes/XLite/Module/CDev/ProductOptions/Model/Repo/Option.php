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

namespace XLite\Module\CDev\ProductOptions\Model\Repo;

/**
 * Option repository
 *
 */
class Option extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'orderby';

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
        $entity = parent::findOneByRecord($data, $parent);
        if (
            !$entity
            && isset($data['name'])
            && $data['name']
            && isset($data['group'])
            && $data['group']
            && isset($data['sku'])
            && $data['sku']
        ) {
            $entity = $this->defineOneBySkuAndNameQuery($data['sku'], $data['group'], $data['name'])->getSingleResult();
        }

        return $entity;
    }

    /**
     * Define find query (by Product SKU and Option group name and option name)
     *
     * @param string $sku   Product SKU
     * @param string $group Option group name (any language)
     * @param string $name  Option name (any language)
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\Option|void
     */
    protected function defineOneBySkuAndNameQuery($sku, $group, $name)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.group', 'ogroup')
            ->innerJoin('ogroup.product', 'product')
            ->innerJoin('ogroup.translations', 'gtranslations')
            ->andWhere('product.sku = :sku AND gtranslations.name = :groupName AND translations.name = :name')
            ->setParameter('sku', $sku)
            ->setParameter('groupName', $group)
            ->setParameter('name', $name);
    }
}
