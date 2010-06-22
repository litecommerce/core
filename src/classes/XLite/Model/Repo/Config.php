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
        
        if (is_null($data)) {
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
     * @return Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineByCategoryQuery($category)
    {
        $qb = XLite_Core_Database::getQB()
            ->select(array('c'))
            ->from('XLite_Model_Config', 'c')
            ->where('c.category = :category')
            ->setParameter('category', $category);

        return $this->assignDefaultOrderBy($qb, 'c');
    }

    /**
     * Get the list of all options
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
        
        if (is_null($data)) {
            $data = $this->defineAllOptionsQuery()->getQuery()->getResult();
            $data = $this->processOptions($data);
            $this->saveToCache($data, 'all');
        }

        return $data;
    }

    /**
     * Define query builder for getAllOptions()
     *
     * @return Doctrine\ORM\QueryBuilder
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function defineAllOptionsQuery()
    {
        $qb = XLite_Core_Database::getQB()
            ->select(array('c'))
            ->from('XLite_Model_Config', 'c');
     
        return $this->assignDefaultOrderBy($qb, 'c');
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

            if (is_null($config->$category)) {
                $config->$category = new XLite_Core_CommonCell();
            }

            if ('checkbox' == $type) {
                $value = ('Y' == $value ? true : false);

            } elseif ('serialized' == $type) {
                $value = unserialize($value);
            }

            $config->$category->$name = $value;
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
        }

        // Prepare memberships array
        // TODO: Need to be moved to the separate model
        if (isset($config->Memberships)) {

            $config->Memberships->memberships = array();

            if (isset($config->Memberships->membershipsCollection) && is_array($config->Memberships->membershipsCollection)) {
                foreach ($config->Memberships->membershipsCollection as $membership) {
                    $config->Memberships->memberships[] = $membership['membership'];
                }

            } else {
                $config->Memberships->membershipsCollection = array();
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
    public function createOption($category, $name, $value, $type = null, $option_name = null, $option_comment = null, $orderby = null) 
    {
        $data = array('value' => $value);

        $optional_fields = array('type', 'option_name', 'option_comment', 'orderby');

        foreach ($optional_fields as $field) {
            if (!is_null($$field)) {
                $data[$field] = $$field;
            }
        }

        if (isset($data['type']) && !$this->isValidOptionType($data['type'])) {
            throw new Exception('Wrong type of option: ' . $type);
        }

        $option = $this->findOneBy(array('name' => $name, 'category' => $category));

        // Create a new option
        if (!$option) {
            $data['name'] = $name;
            $data['category'] = $category;
            $option = new XLite_Model_Config();
        }

        $option->map($data);
        XLite_Core_Database::getEM()->persist($option);
        XLite_Core_Database::getEM()->flush();
    }
}
