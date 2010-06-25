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

/**
 * Language repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_Language extends XLite_Model_Repo_Base_I18n
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

        $list['all'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );

        $list['active'] = array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('active'),
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
        return $this->assignQueryCache($this->defineAllLanguagesQuery()->getQuery(), 'all')->getResult();
    }

    /**
     * Define query builder for findAllLanguages()
     * 
     * @return Doctrine\ORM\QueryBuilder
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
        return $this->assignQueryCache(
            $this->defineActiveLanguagesQuery()->getQuery(),
            'active',
            array('active' => true)
        )->getResult();
    }

    /**
     * Define query builder for findAllLanguages()
     * 
     * @return Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineActiveLanguagesQuery()
    {
        return $this->createQueryBuilder()
            ->where('l.active = :true')
            ->setParameter('true', true);
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
            $this->getDefaultInterfaceLanguage(),
            $this->getDefaultLanguage(),
        );

        return array_unique($query);
    }

    /**
     * Get default interface language 
     * 
     * @return string Language code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultInterfaceLanguage()
    {
        return 'en';
    }

    /**
     * Get global default language 
     * 
     * @return string Language code
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getDefaultLanguage()
    {
        return 'en';
    }
}

