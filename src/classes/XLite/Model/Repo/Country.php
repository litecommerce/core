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
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.0
 */

namespace XLite\Model\Repo;

/**
 * Country repository
 * 
 * @see   ____class_see____
 * @since 1.0.0
 */
class Country extends \XLite\Model\Repo\ARepo
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
    protected $defaultOrderBy = 'country';

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

        $list['all'] = array(
            self::RELATION_CACHE_CELL => array(
                '\XLite\Model\State',
            ),
        );
        $list['enabled'] = array(
            self::RELATION_CACHE_CELL => array(
                '\XLite\Model\State',
            ),
        );
        $list['states'] = array(
            self::RELATION_CACHE_CELL => array(
                '\XLite\Model\State',
            ),
        );

        return $list;
    }

    // }}}

    // {{{ findAllEnabled

    /**
     * Find all enabled countries 
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAllEnabled()
    {
        $data = $this->getFromCache('enabled');
        if (!isset($data)) {
            $data = $this->defineAllEnabledQuery()->getResult();
            $this->saveToCache($data, 'enabled');
        }

        return $data;
    }

    /**
     * Define query builder for findAllEnabled()
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAllEnabledQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('s')
            ->leftJoin('c.states', 's')
            ->andWhere('c.enabled = :enable')
            ->setParameter('enable', true);
    }

    // }}}

    // {{{

    /**
     * Find all countries 
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findAllCountries()
    {
        $data = $this->getFromCache('all');
        if (!isset($data)) {
            $data = $this->defineAllCountriesQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllCountries()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineAllCountriesQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('s')
            ->leftJoin('c.states', 's');
    }

    // }}}

    // {{{ findCountriesStates

    /**
     * Get hash array (key - enabled country code, value - empty array)
     * 
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    public function findCountriesStates()
    {
        $data = $this->getFromCache('states');

        if (!isset($data)) {

            $data = $this->defineCountriesStatesQuery()->getResult();
            $data = $this->postprocessCountriesStates($data);

            $this->saveToCache($data, 'states');
        }

        return $data;
    }

    /**
     * Define query builder for findCountriesStates()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function defineCountriesStatesQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('s')
            ->leftJoin('c.states', 's')
            ->where('c.enabled = :enabled')
            ->addOrderBy('s.state', 'ASC')
            ->setParameter('enabled', true);
    }

    /**
     * Postprocess enabled dump countries 
     * 
     * @param array $data Countries
     *  
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    protected function postprocessCountriesStates(array $data)
    {
        $result = array();

        foreach ($data as $row) {
            if (0 < count($row->getStates())) {
                $code = $row->getCode();
                $result[$code] = array();

                foreach ($row->getStates() as $state) {
                    $result[$code][$state->getStateId()] = $state->getState();
                }
            }
        }

        return $result;
    }

    // }}}
}
