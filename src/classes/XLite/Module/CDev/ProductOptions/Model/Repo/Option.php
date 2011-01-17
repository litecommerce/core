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

namespace XLite\Module\CDev\ProductOptions\Model\Repo;

/**
 * Option repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Option extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     *
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'orderby';

    /**
     * Find one by record 
     * 
     * @param array                $data   Record
     * @param \XLite\Model\AEntity $parent Parent model
     *  
     * @return \XLite\Model\AEntity|void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
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
            try {
                $entity = $this->defineOneBySkuAndNameQuery($data['sku'], $data['group'], $data['name'])
                    ->getQuery()
                    ->getSingleResult();

            } catch (\Doctrine\ORM\NoResultException $e) {
                $entity = null;
            }
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
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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
