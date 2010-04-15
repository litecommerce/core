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
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class XLite_Model_Module extends XLite_Model_Abstract
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
     * Module properties 
     * 
     * @var    array
     * @access protected
     * @since  1.0
     */
    protected $fields = array(
        'module_id'      => 0,
        'name'           => '',
        'enabled'        => 0,
        'dependencies'   => '', 
        'mutual_modules' => '',
    );
    
    /**
     * Contains alias for modules SQL database table 
     * 
     * @var    string
     * @access protected
     * @since  1.0
     */
    protected $alias = 'modules';

    /**
     * SQL table primary keys
     * 
     * @var    array
     * @access protected
     * @since  1.0
     */
    protected $primaryKey = array('name');

    /**
     * Default SQL ORDER clause 
     * 
     * @var    string
     * @access public
     * @since  1.0
     */
    public $defaultOrder = 'enabled DESC, name';


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
        XLite_Model_Layout::getInstance()->addLayout($oldTemplate, $newTemplate);
    }


    /**
     * Constructor
     * 
     * @param string $name module name
     *
     * @return void
     * @access public
     * @since  1.0
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        is_null($name) || $this->read();
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
		return is_null($link = $this->__call('getSettingsForm')) ? 'admin.php?target=module&page=' . $this->get('name') : $link;
    }

	public function __call($method, array $args = array())
	{
		if (!@class_exists($className = 'XLite_Module_' . $this->get('name') . '_Main')) {
			require_once LC_MODULES_DIR . $this->get('name') . LC_DS . 'Main.php' ;
		}

		return call_user_func_array(array($className, $method), $args);
	}
}
