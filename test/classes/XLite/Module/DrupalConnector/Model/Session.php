<?php
/*
+------------------------------------------------------------------------------+
| LiteCommerce                                                                 |
| Copyright (c) 2003-2009 Creative Development <info@creativedevelopment.biz>  |
| All rights reserved.                                                         |
+------------------------------------------------------------------------------+
| PLEASE READ  THE FULL TEXT OF SOFTWARE LICENSE AGREEMENT IN THE  "COPYRIGHT" |
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
| REGISTERED IN ULYANOVSK, RUSSIAN FEDERATION (hereinafter referred to as "THE |
| AUTHOR")  IS  FURNISHING  OR MAKING AVAILABLE TO  YOU  WITH  THIS  AGREEMENT |
| (COLLECTIVELY,  THE "SOFTWARE"). PLEASE REVIEW THE TERMS AND  CONDITIONS  OF |
| THIS LICENSE AGREEMENT CAREFULLY BEFORE INSTALLING OR USING THE SOFTWARE. BY |
| INSTALLING,  COPYING OR OTHERWISE USING THE SOFTWARE, YOU AND  YOUR  COMPANY |
| (COLLECTIVELY,  "YOU")  ARE ACCEPTING AND AGREEING  TO  THE  TERMS  OF  THIS |
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

/* vim: set expandtab tabstop=4 softtabstop=4 shiftwidth=4: */

/**
*
* @package DrupalConnector
* @access  public
* @version SVN $Id$
*/
class XLite_Module_DrupalConnector_Model_Session extends XLite_Model_Session implements XLite_Base_IDecorator
{
    /**
     * Constructor
     * 
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    public function __construct()
    {
		parent::__construct();

		if (defined('LC_CONNECTOR_INITIALIZED')) {
			$this->options['https_host'] = $_SERVER['HTTP_HOST'];
			$this->options['http_host']  = $_SERVER['HTTP_HOST'];

            $url = parse_url($_SERVER['REQUEST_URI']);

            $this->options['web_dir']    = $url['path'];
            $this->options['web_dir_wo_slash'] = preg_replace('/\/$/Ss', '', $this->options['web_dir']);
		}
    }

    /**
     * Return pointer to the single instance of current class
     *
     * @param string $className name of derived class
     *
     * @return XLite_Base_Singleton
     * @access protected
     * @see    ____func_see____
     * @since  3.0
     */
    protected static function _getInstance($className)
    {
		return parent::_getInstance($className . '_' . LC_SESSION_TYPE);
    }

	/**
	 * Destructor
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function __destruct()
	{
		$this->writeClose();
	}
}
