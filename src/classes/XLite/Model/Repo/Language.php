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
 * Language repository
 *
 * @see   ____class_see____
 * @since 1.0.0
 */
class Language extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Repository type
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $type = self::TYPE_SERVICE;

    /**
     * Default 'order by' field name
     *
     * @var   string
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $defaultOrderBy = 'code';

    /**
     * Alternative record identifiers
     *
     * @var   array
     * @see   ____var_see____
     * @since 1.0.0
     */
    protected $alternativeIdentifier = array(
        array('code'),
    );

    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias OPTIONAL
     * @param string $code  Language code OPTIONAL
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function createQueryBuilder($alias = null, $code = null)
    {
        if (!isset($code)) {
            $code = \XLite\Base\Superclass::getDefaultLanguage();
        }

        return parent::createQueryBuilder($alias, $code);
    }

    // {{{ defineCacheCells

    /**
     * Define cache cells
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCacheCells()
    {
        $list = parent::defineCacheCells();
        $list['all'] = array();
        $list['status'] = array(
            self::ATTRS_CACHE_CELL => array('status'),
        );

        return $list;
    }

    // }}}

    // {{{ findAllLanguages

    /**
     * Find all languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAllLanguages()
    {
        $data = $this->getFromCache('all');

        if (!isset($data)) {
            $data = $this->defineAllLanguagesQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllLanguages()
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAllLanguagesQuery()
    {
        return $this->createQueryBuilder();
    }

    // }}}

    // {{{ findActiveLanguages

    /**
     * Find all active languages
     * NOTE: do not cache this result in a persistent cache
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findActiveLanguages()
    {
        return $this->defineByStatusQuery(\XLite\Model\Language::ENABLED)->getResult();
    }

    /**
     * Find all inactive languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findInactiveLanguages()
    {
        return $this->defineByStatusQuery(\XLite\Model\Language::INACTIVE)->getResult();
    }

    /**
     * Define query builder for findActiveLanguages()
     *
     * @param integer $status Status key
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineByStatusQuery($status)
    {
        return $this->createQueryBuilder()
            ->andWhere('l.status = :status')
            ->setParameter('status', $status);
    }

    // }}}

    // {{{ findAddedLanguages

    /**
     * Find all added languages
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAddedLanguages()
    {
        return $this->defineAddedQuery()->getResult();
    }

    /**
     * Define query builder for findAddedLanguages()
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAddedQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('l.status != :status')
            ->setParameter('status', \XLite\Model\Language::INACTIVE);
    }

    // }}}
}
