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
 * DB-based configuration registry
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Config extends \XLite\Model\Repo\Base\I18n
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
     * List of options which are not allowed 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $disabledOptions = array();


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

        $list['category'] = array(
            self::ATTRS_CACHE_CELL => array('category')
        );

        return $list;
    }

    /**
     * Remove option from the "black list" 
     * 
     * @param string $category option category
     * @param string $name     option name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function enableOption($category, $name)
    {
        unset($this->disabledOptions[$category][array_search($name, $this->disabledOptions[$category])]);
    }

    /**
     * Add option to the "black list" 
     * 
     * @param string $category option category
     * @param string $name     option name
     *  
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function disableOption($category, $name)
    {
        if (!isset($this->disabledOptions[$category])) {
            $this->disabledOptions[$category] = array();
        }

        $this->disabledOptions[$category][] = $name;
    }

    /**
     * Return query (and its params) which is used to filter options 
     * 
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function getOptionsAvailabilityCondition()
    {
        $conditions = array();
        $params = array();

        foreach ($this->disabledOptions as $category => $options) {

            $condition = 'c.category = :category' . $category;
            $params['category' . $category] = $category;

            list($keys, $options) = \XLite\Core\Database::prepareArray($options, $category);
            $condition .= ' AND c.name IN (' . implode(',', $keys) . ')';
            $params += $options;

            $conditions[] = 'NOT (' . $condition . ')';
        }

        return array(empty($conditions) ? null : '(' . implode(') AND (', $conditions) . ')', $params);
    }

    /**
     * Add "filter" condition to the query builder
     * 
     * @param \Doctrine\ORM\QueryBuilder $qb current query builder
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareOptionsAvailabilityCondition(\Doctrine\ORM\QueryBuilder $qb)
    {
        list($condition, $params) = $this->getOptionsAvailabilityCondition();

        return isset($condition) 
            ? $qb->andWhere($condition)->setParameters($qb->getParameters() + $params) 
            : $qb;
    }


    /**
     * Create a new QueryBuilder instance that is prepopulated for this entity name
     *
     * @param string $alias Table alias
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createQueryBuilder($alias = null)
    {
        return $this->prepareOptionsAvailabilityCondition(parent::createQueryBuilder($alias));
    }

    /**
     * Get the list of options of the specified category
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getByCategory($category, $force = false, $doNotProcess = false)
    {
        $data = null;

        if (!$force) {
            $data = $this->getFromCache('category', array('category' => $category));
        }
        
        if (!isset($data)) {
            $data = $this->defineByCategoryQuery($category)->getQuery()->getResult();
            if (!$doNotProcess) {
                $data = $this->processOptions($data);
                $this->saveToCache($data, 'category', array('category' => $category));
            }
        }

        return $data;
    }

    /**
     * Define query builder for getByCategory()
     *
     * @param string $category Category name
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByCategoryQuery($category)
    {
        return $this->createQueryBuilder()
            ->andWhere('c.category = :category')
            ->setParameter('category', $category);
    }

    /**
     * Find all visible settings by category name
     * 
     * @param string $category Category name
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function findByCategoryAndVisible($category)
    {
        return $this->defineByCategoryAndVisibleQuery($category)->getQuery()->getResult();
    }

    /**
     * Define query for findByCategoryAndVisible() method
     * 
     * @param string $category Category name
     *  
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByCategoryAndVisibleQuery($category)
    {
        return $this->createQueryBuilder()
            ->andWhere('c.category = :category AND c.type != :empty')
            ->setParameter('category', $category)
            ->setParameter('empty', '');
    }

    /**
     * Get the list of all options
     * 
     * @param boolean $force Do not use cache
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getAllOptions($force = false)
    {
        $data = null;

        if (!$force) {
            $data = $this->getFromCache('all');
        }
        
        if (!isset($data)) {
            $data = $this->defineAllOptionsQuery()->getQuery()->getResult();
            $data = $this->detachList($data);
            $data = $this->processOptions($data);
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for getAllOptions()
     *
     * @return \Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllOptionsQuery()
    {
        return $this->createQueryBuilder();
    }

    /**
     * Check (and modify) option name and value
     * 
     * @param string &$category option category
     * @param string &$name     option name
     * @param mixed  &$value    option value
     *  
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkNameAndValue(&$category, &$name, &$value)
    {
        return true;
    }

    /**
     * Preprocess options and transform its to the hierarchy of \XLite\Core\CommonCell objects
     * 
     * @param array $data Array of options data gathered from the database
     *  
     * @return \XLite\Core\CommonCell
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processOptions($data)
    {

        $config = new \XLite\Core\CommonCell();

        foreach ($data as $option) {
        
            $category = $option->category;
            $name     = $option->name;
            $type     = $option->type;
            $value    = $option->value;

            if (!isset($config->$category)) {
                $config->$category = new \XLite\Core\CommonCell();
            }

            if ('checkbox' === $type) {
                $value = ('Y' == $value);

            } elseif ('serialized' === $type) {
                $value = unserialize($value);
            }

            if ($this->checkNameAndValue($category, $name, $value)) {
                $config->$category->$name = $value;
            }
        }

        // Add human readable store country and state names for Company options
        if (isset($config->Company)) {
            $config->Company->locationCountry = \XLite\Core\Database::getRepo('XLite\Model\Country')
                ->findDetached($config->Company->location_country);

            $config->Company->locationState = \XLite\Core\Database::getRepo('XLite\Model\State')
                ->findById($config->Company->location_state, $config->Company->location_custom_state);
        }

        // Add human readable default country name for General options
        if (isset($config->General)) {
            $config->General->defaultCountry = \XLite\Core\Database::getRepo('XLite\Model\Country')
                ->findDetached($config->General->default_country);

            // Get default language object
            if (isset($config->General->default_language)) {
                $config->General->defaultLanguage = \XLite\Core\Database::getRepo('XLite\Model\Language')
                    ->findOneByCode($config->General->default_language);
            }

            if (!isset($config->General->defaultLanguage)) {
                $config->General->defaultLanguage = \XLite\Core\Database::getRepo('XLite\Model\Language')
                    ->getDefaultLanguage();
            }

            if ($config->General->defaultLanguage) {
                $config->General->defaultLanguage->detach();
            }

            // Type cast
            $config->General->minimal_order_amount = doubleval($config->General->minimal_order_amount);
            $config->General->maximal_order_amount = doubleval($config->General->maximal_order_amount);
        }

        return $config;
    }

    /**
     * Check if option type is a valid 
     * 
     * @param string $optionType Option type
     *  
     * @return boolean 
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isValidOptionType($optionType)
    {
        return in_array(
            $optionType, 
            array(
                '',
                'text',
                'textarea',
                'checkbox',
                'country',
                'state',
                'select',
                'serialized',
                'separator'
            )
        );
    }

    /**
     * Create new option / Update option value
     * 
     * @param array $data Option data in the following format
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createOption($data)
    {
        // Array of allowed fields and flag required/optional
        $fields = array(
            'category' => 1,
            'name'     => 1,
            'value'    => 1,
            'type'     => 0,
            'orderby'  => 0,
        );

        $errorFields = array();

        foreach ($fields as $field => $required) {
            if (isset($data[$field])) {
                $fields[$field] = $data[$field];
            
            } elseif ($required) {
                $errorFields[] = $field;
            }
        }

        if (!empty($errorFields)) {
            throw new \Exception(
                'createOptions() failed: The following required fields are missed: ' . 
                implode(', ', $errorFields)
            );
        }

        if (isset($fields['type']) && !$this->isValidOptionType($fields['type'])) {
            throw new \Exception('createOptions() failed: Wrong option type: ' . $type);
        }

        $option = $this->findOneBy(array('name' => $fields['name'], 'category' => $fields['category']));

        // Existing option: unset key fields
        if ($option) {
            $option->setValue($fields['value']);

        } else {
            // Create a new option
            $option = new \XLite\Model\Config();
            $option->map($fields);
        }

        \XLite\Core\Database::getEM()->persist($option);
        \XLite\Core\Database::getEM()->flush();
    }
}
