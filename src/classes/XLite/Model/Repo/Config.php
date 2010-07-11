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
 * DB-based configuration registry
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Repo_Config extends XLite_Model_Repo_Base_I18n
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

        $list['all'] = array(
            self::TTL_CACHE_CELL => self::INFINITY_TTL,
        );

        $list['category'] =array(
            self::TTL_CACHE_CELL   => self::INFINITY_TTL,
            self::ATTRS_CACHE_CELL => array('category_name')
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
    protected function getOptionsAbaliabilityCondition()
    {
        $conditions = array();
        $params = array();

        foreach ($this->disabledOptions as $category => $options) {

            $condition = 'c.category = :category' . $category;
            $params['category' . $category] = $category;

            list($keys, $options) = XLite_Core_Database::prepareArray($options, $category);
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
    protected function prepareOptionsAbaliabilityCondition(\Doctrine\ORM\QueryBuilder $qb)
    {
        list($condition, $params) = $this->getOptionsAbaliabilityCondition();

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
        return $this->prepareOptionsAbaliabilityCondition(parent::createQueryBuilder($alias));
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
            $data = $this->getFromCache('category', array('category_name' => $category));
        }
        
        if (!isset($data)) {
            $data = $this->defineByCategoryQuery($category)->getQuery()->getResult();
            if (!$doNotProcess) {
                $data = $this->processOptions($data);
                $this->saveToCache($data, 'category', array('category_name' => $category));
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
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function checkNameAndValue(&$category, &$name, &$value)
    {
        return true;
    }

    /**
     * Preprocess options and transform its to the hierarchy of XLite_Core_CommonCell objects
     * 
     * @param array $data Array of options data gathered from the database
     *  
     * @return XLite_Core_CommonCell
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function processOptions($data)
    {

        $config = new XLite_Core_CommonCell();

        foreach ($data as $option) {
        
            $category = $option->category;
            $name     = $option->name;
            $type     = $option->type;
            $value    = $option->value;

            if (!isset($config->$category)) {
                $config->$category = new XLite_Core_CommonCell();
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
            $config->Company->locationCountry = XLite_Core_Database::getEM()
                ->find('XLite_Model_Country', $config->Company->location_country);

            $config->Company->locationState = XLite_Core_Database::getRepo('XLite_Model_State')
                ->findById($config->Company->location_state, $config->Company->location_custom_state);
        }

        // Add human readable default country name for General options
        if (isset($config->General)) {
            $config->General->defaultCountry = XLite_Core_Database::getEM()
                ->find('XLite_Model_Country', $config->General->default_country);

            // Get default language object
            if (isset($config->General->default_language)) {
                $config->General->defaultLanguage = XLite_Core_Database::getRepo('XLite_Model_Language')
                    ->findOneByCode($config->General->default_language);
            }

            if (!isset($config->General->defaultLanguage)) {
                $config->General->defaultLanguage = XLite_Core_Database::getRepo('XLite_Model_Language')->getDefaultLanguage();
            }

        }

        return $config;
    }

    /**
     * Check if option type is a valid 
     * 
     * @param string $optionType Option type
     *  
     * @return bool
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function isValidOptionType($optionType)
    {
        return in_array($optionType, array(
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
     * @param string  $category       Option category name
     * @param string  $name           Option name
     * @param mixed   $value          Option value
     * @param string  $type           Option type
     * @param string  $option_name    Option comment
     * @param string  $option_comment Option comment
     * @param integer $orderby        Option orderby
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function createOption($data) // $category, $name, $value, $type = null, $option_name = null, $option_comment = null, $orderby = null) 
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
            throw new Exception('createOptions() failed: The following required fields are missed: ' . implode(', ', $errorFields));
        }

        if (isset($fields['type']) && !$this->isValidOptionType($fields['type'])) {
            throw new Exception('createOptions() failed: Wrong option type: ' . $type);
        }

        $option = $this->findOneBy(array('name' => $fields['name'], 'category' => $fields['category']));

        // Existing option: unset key fields
        if ($option) {
            unset($fields['name']);
            unset($fields['category']);

        // Create a new option
        } else {
            $option = new XLite_Model_Config();
        }


        $option->map($fields);
        XLite_Core_Database::getEM()->persist($option);
        XLite_Core_Database::getEM()->flush();
    }
}
