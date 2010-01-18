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
    const MODULE_GENERAL   = 4;
    const MODULE_3RD_PARTY = 5;


    /**
     * Module properties 
     * 
     * @var    array
     * @access protected
     * @since  1.0
     */
    protected $fields = array(
        'module_id'    => 0,
        'name'         => '',
        'description'  => '',
        'enabled'      => 0,
        'version'      => '',
        'dependencies' => '', 
        'type'         => self::MODULE_UNKNOWN,
        'access_date'  => 0
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
     * @access protected
     * @since  1.0
     */
    protected $defaultOrder = 'module_id';

    /**
     * Required LiteCommerce version to install this mod 
     * 
     * @var    string
     * @access protected
     * @since  1.0
     */
    protected $minVer = '3.0';

    /**
     * Determines if we need to show setting form for this module
     * 
     * @var    bool
     * @access protected
     * @since  1.0
     */
    protected $showSettingsForm = false;


    /**
     * Show service message
     * 
     * @param string $message text to display
     *  
     * @return void
     * @access protected
     * @since  1.0
     */
    protected function printMessage($message)
    {
        echo $message . '\n';
        func_flush();
    }

    /**
     * Show link for return 
     * 
     * @return void
     * @access protected
     * @since  1.0
     */
    protected function addLink()
    {
        $this->printMessage('<a href="admin.php?target=modules">Click here to return to admin zone</a>');
    }

    /**
     * Custom approach to determine current area 
     * 
     * @return string
     * @access protected
     * @since  1.0
     */
    protected function getZone()
    {
        if (is_null($this->zone)) {
            $this->zone = XLite::getInstance()->getOptions(array('skin_details', 'skin'));
        }

        return $this->zone;
    }

    /**
     * Show the failure message
     * 
     * @return void
     * @access protected
     * @since  1.0
     */
    protected function failure()
    {
        $this->printMessage('</pre><p><b><font color=red>Failed to install module ' . $this->get('name') . '</font></b><br><br>');
        $this->addLink();
    }

    /**
     * Called from install() when installation is complete.
     * Inserts a module row in the xilte_modules table and show the success message
     * 
     * @return void
     * @access protected
     * @since  1.0
     */
    protected function success()
    {
        $this->isExists() ? $this->update() : $this->create();
        $this->printMessage('</pre><p><b>Module ' . $this->get('name') . ' has been installed.</b><br><br>');
        $this->addLink();
    }

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
     * clearModuleType. FIXME - to delete? 
     * 
     * @param mixed $moduleType module type
     *  
     * @return void
     * @access public
     * @since  1.0
     */
    public function clearModuleType($moduleType = null)
    {
        $this->xlite->_paymentMethodRegistered = 0;
        $this->xlite->_shippingMethodRegistered = 0;

        if (isset($moduleType)) {
            $this->set('type', $moduleType);
        }
    }

    /**
     * Check if current module depends on a passed one
     *
     * @param string $moduleName module to check
     *
     * @return bool
     * @access public
     * @since  1.0
     */
    public function isDependsOn($moduleName)
    {
        return in_array($moduleName, $this->getDependencies());
    }

    /**
     * Return link to settings form
     *
     * @return string
     * @access public
     * @since  1.0
     */
    public function getSettingsForm()
    {
        return $this->showSettingsForm ? 'admin.php?target=module&page=' . $this->get('name') : '';
    }

    /**
     * Install module
     * 
     * @return void
     * @access public
     * @since  1.0
     */
    public function install()
    {
        echo '<pre>';

        // check module version
        $version = $this->get('minVer');
        $name    = $this->get('name');

        if (0 > version_compare($this->config->get('Version')->get('version'), $version)) {

            $this->printMessage(
                $name . 'module can be installed only on LiteCommerce version ' . $version
                . ' or higher.<br>Please upgrade your shopping cart to verions ' . $version . '<br>'
            );
            $this->failure();

        } else {

            // execute PHP install code
            @include LC_MODULES_DIR . $name . LC_DS . 'install.php';
    
            // execute SQL install code
            $sql = LC_MODULES_DIR . $name . LC_DS . 'install.sql';

            if (is_readable($sql) && !query_upload($sql, $this->db->connection, true)) {
                $this->failure();
            } else {
                // execute PHP post-install code
                @include LC_MODULES_DIR . $name . LC_DS . 'post-install.php';
                $this->success();
            }
        }
    }

    /**
     * Uninstall module
     * 
     * @return void
     * @access public
     * @since  1.0
     */
    public function uninstall()
    {
        // Disable module first
        $this->set('enabled', 0);
        $this->update();

        $name = $this->get('name');

        // execute SQL uninstall code
        $sql = LC_MODULES_DIR . $name . LC_DS . 'uninstall.sql';

        if (is_readable($sql) && !query_upload($sql, $this->db->connection, true)) {
            $this->failure();
        } else {
            $zone = $this->getZone();
            $skinFolders = array(
                array('admin', 'en', 'modules'),
                array('admin', 'en', 'images', 'modules'),
                array($zone,   'en', 'modules'),
                array($zone,   'en', 'images', 'modules'),
                array('mail',  'en', 'modules'),
            );

            foreach ($skinFolders as $folder) {
                unlinkRecursive(LC_SKINS_DIR . implode(LC_DS, $folder) . LC_DS . $name);
            }
            unlinkRecursive(LC_MODULES_DIR . $name);

            $this->delete();
            $this->printMessage('The module $name has been successfully uninstalled<br>');
            $this->addLink();
        }
    }

	public function __call($name, array $arguments)
	{
		// TODO - call the certain module function
		echo $name;die;
	}
}

