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
 * Module repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Module extends \XLite\Model\Repo\ARepo
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = array(
        'enabled' => 0,
        'name'    => 1,
    );

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

        $list['enabled'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('enabled'),
        );

        return $list;
    }

    /**
     * Find all modules
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllModules()
    {
        return $this->assignQueryCache($this->defineAllModulesQuery()->getQuery(), 'all')
            ->getResult();
    }

    /**
     * Define query builder for findAllModules()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllModulesQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Find all enabled (or disabled) modules
     * 
     * @param boolean $enabled Enabled flag
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllEnabled($enabled = true)
    {
        return $this->assignQueryCache(
            $this->defineAllEnabledQuery($enabled)->getQuery(),
            'enabled',
            array('enabled' => $enabled)
        )
            ->getResult();
    }

    /**
     * Define query builder for findAllEnabled()
     *
     * @param boolean $enabled Enabled flag
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllEnabledQuery($enabled)
    {
        return $this->createQueryBuilder()
            ->andWhere('m.enabled = :enabled')
            ->setParameter('enabled', $enabled);
    }

    /**
     * Find by type 
     * 
     * @param integer $type Type
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByType($type)
    {
        return $this->defineByTypeQuery($type)->getQuery()->getResult();
    }

    /**
     * Define query builder for findByType()
     * 
     * @param integer $type Type
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByTypeQuery($type)
    {
        return $this->createQueryBuilder()
            ->andWhere('m.type = :type')
            ->setParameter('type', $type);
    }
}

