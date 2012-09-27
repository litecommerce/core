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

namespace XLite\Model\Repo\Base;

/**
 * Translations-owner abstract reporitory
 *
 */
abstract class I18n extends \XLite\Model\Repo\ARepo
{
    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias OPTIONAL
     * @param string $code  Language code OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function createQueryBuilder($alias = null, $code = null)
    {
        return $this->addLanguageQuery(parent::createQueryBuilder($alias), $alias, $code);
    }

    /**
     * Add language subquery with language code relation
     *
     * @param \Doctrine\ORM\QueryBuilder $queryBuilder Query builder
     * @param string                     $alias        Main model alias OPTIONAL
     * @param string                     $code         Language code OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    protected function addLanguageQuery(\Doctrine\ORM\QueryBuilder $queryBuilder, $alias = null, $code = null, $translationsAlias = 'translations')
    {
        if (!isset($alias)) {
            $alias = $this->getMainAlias($queryBuilder);
        }

        if (!isset($code)) {
            $code = !\XLite::isCacheBuilding() && \XLite\Core\Session::getInstance()->getLanguage()
                ? \XLite\Core\Session::getInstance()->getLanguage()->getCode()
                : 'en';
        }

        $queryBuilder
            ->leftJoin(
                $alias . '.translations',
                $translationsAlias,
                \Doctrine\ORM\Query\Expr\Join::WITH,
                $translationsAlias . '.code = :lng'
            )
            ->setParameter('lng', $code);

        return $queryBuilder;
    }
}
