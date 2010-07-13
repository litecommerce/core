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

namespace XLite\Model;

/**
 * Module
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 * @Entity (repositoryClass="XLite\Model\Repo\Module")
 * @Table (name="modules")
 */
class Module extends AEntity
{
    /**
     * Module types
     */

    const MODULE_UNKNOWN   = 0;
    const MODULE_PAYMENT   = 1;
    const MODULE_SHIPPING  = 2;
    const MODULE_SKIN      = 3;
    const MODULE_CONNECTOR = 4;
    const MODULE_GENERAL   = 5;
    const MODULE_3RD_PARTY = 6;


    /**
     * Module id 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Id
     * @GeneratedValue (strategy="AUTO")
     * @Column (type="integer")
     */
    protected $module_id;

    /**
     * Name 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="64")
     */
    protected $name = '';

    /**
     * Enabled 
     * 
     * @var    boolean
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="boolean")
     */
    protected $enabled = false;

    /**
     * Dependencies 
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1024")
     */
    protected $dependencies = '';

    /**
     * Mutual modules list
     * 
     * @var    string
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="string", length="1024")
     */
    protected $mutual_modules = '';

    /**
     * Type 
     * 
     * @var    integer
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     * @Column (type="integer")
     */
    protected $type = self::MODULE_GENERAL;
    
    /**
     * Overlay a template
     *
     * @param string $oldTemplate template to overlay
     * @param string $newTemplate module-specific template
     *
     * @return void
     * @since  1.0
     */
    protected function addLayout($oldTemplate, $newTemplate)
    {
        \XLite\Model\Layout::getInstance()->addLayout($oldTemplate, $newTemplate);
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @access public
     * @since  1.0
     */
    public function getSettingsFormLink()
    {
        $link = $this->__call('getSettingsForm');

        return is_null($link)
            ? \XLite\Core\Converter::buildURL('module', '', array('page' => $this->getName()), 'admin.php')
            : $link;
    }

    /**
     * Get module Main class name 
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getMainClassName()
    {
        return '\XLite\Module\\' . $this->getName() . '\Main';
    }

    /**
     * Include module Main class 
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function includeMainClass()
    {
        $class = $this->getMainClassName();

        if (!\XLite\Core\Operator::isClassExists($class)) {
            require_once LC_CLASSES_DIR . str_replace('\\', LC_DS, $class) . '.php';
        }
    }

    /**
     * Set mutual modules list
     * 
     * @param mixed $modules Modules list (string or array)
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function setMutualModules($modules)
    {
        $this->mutual_modules = is_string($modules)
            ? $modules
            : implode(',', $modules);
    }

    /**
     * It's possible to call methods of certain module directly
     * 
     * @param string $method method name
     * @param array  $args   call arguments
     *  
     * @return mixed
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __call($method, array $args = array())
    {
        $this->includeMainClass();
        $class = $this->getMainClassName();

        return (\XLite\Core\Operator::isClassExists($class) && method_exists($class, $method))
            ? call_user_func_array(array($class, $method), $args)
            : parent::__call($method, $args);

    }
}
