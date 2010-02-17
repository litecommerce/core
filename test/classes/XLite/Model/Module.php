<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  'COPYRIGHT' |
| FILE PROVIDED WITH THIS DISTRIBUTION.  THE AGREEMENT TEXT  IS ALSO AVAILABLE |
| AT THE FOLLOWING URLs:                                                       |
|                                                                              |
| FOR LITECOMMERCE                                                             |
| http://www.litecommerce.com/software_license_agreement.html                  |
|                                                                              |
| FOR LITECOMMERCE ASP EDITION                                                 |
| http://www.litecommerce.com/software_license_agreement_asp.html              |
|                                                                              |
| THIS  AGREEMENT EXPRESSES THE TERMS AND CONDITIONS ON WHICH YOU MAY USE THIS |
| SOFTWARE PROGRAM AND ASSOCIATED DOCUMENTATION THAT CREATIVE DEVELOPMENT, LLC |
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as 'THE |
| AUTHOR')  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE 'SOFTWARE'). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  'YOU')  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
| LICENSE AGREEMENT. IF YOU ARE NOT WILLING TO BE BOUND BY THIS AGREEMENT,  DO |
| NOT  INSTALL  OR USE THE SOFTWARE. VARIOUS COPYRIGHTS AND OTHER INTELLECTUAL |
| PROPERTY  RIGHTS PROTECT THE SOFTWARE. THIS AGREEMENT IS A LICENSE AGREEMENT |
| THAT  GIVES YOU LIMITED RIGHTS TO USE THE SOFTWARE AND NOT AN AGREEMENT  FOR |
| SALE  OR  FOR TRANSFER OF TITLE. THE AUTHOR RETAINS ALL RIGHTS NOT EXPRESSLY |
| GRANTED  BY  THIS AGREEMENT.                                                 |
|                                                                              |
| The Initial Developer of the Original Code is Creative Development LLC       |
| Portions created by Creative Development LLC are Copyright (C) 2003 Creative |
| Development LLC. All Rights Reserved.                                        |
+------------------------------------------------------------------------------+
*/


/**
 * Module model. Works with database
 * 
 * @package    Lite Commerce
 * @subpackage Model
 * @since      1.0
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

