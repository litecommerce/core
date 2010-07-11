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
 * Country repository
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_Country extends XLite_Model_Repo_AbstractRepo
{
    /**
     * Default 'order by' field name
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $defaultOrderBy = 'country';

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

        $list['countries_states'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );

        return $list;
    }

    /**
     * Find all countries 
     * 
     * @return \Doctrine\Common\Collections\ArrayCollection
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findAllCountries()
    {
        $data = $this->getFromCache('all');
        if (is_null($data)) {
            $data = $this->defineAllCountriesQuery()->getQuery()->getResult();
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for findAllCountries()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllCountriesQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Get hash array (key - enabled country code, value - empty array)
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findCountriesStates()
    {
        $data = $this->getFromCache('countries_states');
        if (is_null($data)) {
            $data = $this->defineCountriesStatesQuery()->getQuery()->getResult();
            $data = $this->postprocessCountriesStates($data);
            $this->saveToCache($data, 'countries_states');
        }

        return $data;
    }

    /**
     * Define query builder for findCountriesStates()
     * 
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineCountriesStatesQuery()
    {
        return $this->createQueryBuilder()
            ->addSelect('s')
            ->leftJoin('c.states', 's')
            ->where('c.enabled = :enabled')
            ->setParameter('enabled', true);

    }

    /**
     * Postprocess enabled dump countries 
     * 
     * @param array $data Countries
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function postprocessCountriesStates(array $data)
    {
        $result = array();

        foreach ($data as $row) {
            $result[$row->code] = array();

            foreach ($row->states as $state) {
                $result[$row->code][$state->state_id] = $state->state;
            }
        }

        return $result;
    }
}

