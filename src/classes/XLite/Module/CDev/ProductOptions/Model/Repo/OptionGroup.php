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
 * Option group repository
 *
 */
class OptionGroup extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     *
     * @var string
     */
    protected $defaultOrderBy = 'orderby';


    /**
     * Get option group types
     *
     * @return array
     */
    public function getOptionGroupTypes()
    {
        return array(
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE => array(
                'name'  => 'Options group',
                'views' => array(
                    \XLite\Module\CDev\ProductOptions\Model\OptionGroup::SELECT_VISIBLE => array(
                        'name' => 'Select box',
                    ),
                    \XLite\Module\CDev\ProductOptions\Model\OptionGroup::RADIO_VISIBLE => array(
                        'name' => 'Radio buttons list',
                    ),
                ),
            ),
            \XLite\Module\CDev\ProductOptions\Model\OptionGroup::TEXT_TYPE => array(
                'name' => 'Text option',
                'views' => array(
                    \XLite\Module\CDev\ProductOptions\Model\OptionGroup::TEXTAREA_VISIBLE => array(
                        'name' => 'Text area',
                    ),
                    \XLite\Module\CDev\ProductOptions\Model\OptionGroup::INPUT_VISIBLE => array(
                        'name' => 'Input box',
                    ),
                ),
            ),
        );
    }


    // {{{ findActiveByProductId

    /**
     * Find all active option groups by product id
     *
     * @param integer $productId Product id
     *
     * @return array
     */
    public function findActiveByProductId($productId)
    {
        $data = $this->defineActiveByProductIdQuery(intval($productId))->getResult();
        $data = $this->postprocessActiveByProductId($data, intval($productId));

        return $data;
    }

    /**
     * Define query for findActiveByProductId() method
     *
     * @param integer $productId Product id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineActiveByProductIdQuery($productId)
    {
        return $this->createQueryBuilder()
            ->addSelect('options')
            ->leftJoin('o.options', 'options', 'WITH', 'options.enabled = :true')
            ->innerJoin('o.product', 'p', 'WITH', 'p.product_id = :productId')
            ->andWhere('o.enabled = :true')
            ->setParameter('productId', $productId)
            ->setParameter('true', true);
    }

    /**
     * Postprocessing for findActiveByProductId() method
     *
     * @param array   $data      Data
     * @param integer $productId Product id
     *
     * @return array
     */
    protected function postprocessActiveByProductId(array $data, $productId)
    {
        foreach ($data as $i => $item) {
            if ($item->getType() == \XLite\Module\CDev\ProductOptions\Model\OptionGroup::GROUP_TYPE) {
                foreach ($item->getOptions() as $option) {
                    if (!$option->getEnabled()) {
                        $item->getOptions()->removeElement($option);
                    }
                }

                if (0 == $item->getOptions()->count()) {
                    unset($data[$i]);
                }
            }
        }

        return $data;
    }

    // }}}

    // {{{ findOneByGroupIdAndProductId

    /**
     * Find one group by group id and product id
     *
     * @param integer $groupId   Option group id
     * @param integer $productId Product id
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroup|void
     */
    public function findOneByGroupIdAndProductId($groupId, $productId)
    {
        return $this->defineOneByGroupIdAndProductIdQuery($groupId, $productId)->getSingleResult();
    }

    /**
     * Define query for findOneByGroupIdAndProductId() method
     *
     * @param integer $groupId   Option group id
     * @param integer $productId Product id
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function defineOneByGroupIdAndProductIdQuery($groupId, $productId)
    {
        return $this->createQueryBuilder()
            ->innerJoin('o.product', 'p', 'WITH', 'p.product_id = :productId')
            ->andWhere('o.group_id = :groupId')
            ->setParameter('groupId', $groupId)
            ->setParameter('productId', $productId)
            ->setMaxResults(1);
    }

    // }}}

    // {{{ findOneByRecord

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
            && isset($data['sku'])
            && $data['sku']
        ) {
            $entity = $this->defineOneBySkuAndNameQuery($data['sku'], $data['name'])->getSingleResult();
        }

        return $entity;
    }

    /**
     * Define find query (by Product SKU and group name)
     *
     * @param string $sku  Product SKU
     * @param string $name Option group name (any language)
     *
     * @return \XLite\Module\CDev\ProductOptions\Model\OptionGroup|void
     */
    protected function defineOneBySkuAndNameQuery($sku, $name)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.product', 'product')
            ->andWhere('product.sku = :sku AND translations.name = :name')
            ->setParameter('sku', $sku)
            ->setParameter('name', $name);
    }

    // }}}
}
