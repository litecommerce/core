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

namespace XLite\Model\Repo;

/**
 * Language repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Language extends \XLite\Model\Repo\Base\I18n
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'code';

    /**
     * Global default language (cache)
     * 
     * @var    \XLite\Model\Language
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultLanguage = null;

    /**
     * Define cache cells 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
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

    /**
     * Find all languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllLanguages()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllLanguagesQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllLanguages()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllLanguagesQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Find all active languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findActiveLanguages()
    {
        $data = $this->getFromCache('status', array('status' => \XLite\Model\Language::ENABLED));
        if (!isset($data)) {
            $data = $this->defineByStatusQuery(\XLite\Model\Language::ENABLED)->getQuery()->getResult();
            foreach ($data as $item) {
                $this->_em->detach($item);
            }
            $this->saveToCache($data, 'status', array('status' => \XLite\Model\Language::ENABLED));
        }

        return $data;
    }

    /**
     * Define query builder for findActiveLanguages()
     *
     * @param integer $status Status key
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByStatusQuery($status)
    {
        return $this->createQueryBuilder()
            ->andWhere('l.status = :status')
            ->setParameter('status', $status);
    }

    /**
     * Find all added languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAddedLanguages()
    {
        return $this->defineAddedQuery()->getQuery()->getResult();
    }

    /**
     * Define query builder for findAddedLanguages()
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAddedQuery()
    {
        return $this->createQueryBuilder()
            ->andWhere('l.status != :status')
            ->setParameter('status', \XLite\Model\Language::INACTIVE);
    }

    /**
     * Find all inactive languages
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findInactiveLanguages()
    {
        return $this->defineByStatusQuery(\XLite\Model\Language::INACTIVE)->getQuery()->getResult();
    }

    /**
     * Find language one by code 
     * 
     * @param string $code Code
     *  
     * @return \XLite\Model\Language or null
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findOneByCode($code)
    {
        try {
            $language = $this->defineOneByCodeQuery($code)->getQuery()->getSingleResult();

        } catch (\Doctrine\ORM\NoResultException $exception) {
            $language = null;
        }

        return $language;
    }

    /**
     * Define query builder for findOneByCode()
     *
     * @param string $code Language code
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineOneByCodeQuery($code)
    {
        return $this->createQueryBuilder()
            ->andWhere('l.code = :code')
            ->setParameter('code', $code)
            ->setMaxResults(1);
    }


    /**
     * Get languages query 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getLanguagesQuery()
    {
        $query = array(
            \XLite\Core\Config::getInstance()->General->defaultLanguage->code,
            $this->getDefaultLanguage()->code,
        );

        return array_unique($query);
    }

    /**
     * Get global default language 
     * 
     * @return string Language code
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDefaultLanguage()
    {
        if (!isset($this->defaultLanguage)) {
            $this->defaultLanguage = \XLite\Core\Database::getRepo('XLite\Model\Language')
                ->findOneByCode('en');

            if (!$this->defaultLanguage) {
                // TODO - add throw exception
            }
        }

        return $this->defaultLanguage;
    }
}

