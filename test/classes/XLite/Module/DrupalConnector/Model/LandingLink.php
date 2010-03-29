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
* Class Landing link provides access to landing links to LC from another systems
*
* @package DrupalConnector
* @version SVN $Id$
*/
class XLite_Module_DrupalConnector_Model_LandingLink extends XLite_Model_Abstract
{
	/**
	 * Record TTL (seconds)
     */
	const TTL = 60;

    /**
     * Link id validation pattern (regular expression)
     */
	const ID_PATTERN = '/^[a-f0-9]{32}$/Ss';

	public static $_removed = false;

    public $fields = array(
            'link_id'    => '',
            'session_id' => '',
            'expiry'     => 0,
        );

    public $primaryKey = array('link_id');
    public $alias = 'landing_links';
    public $defaultOrder = 'expiry';

	/**
	 * Constructor
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function __construct()
	{
		parent::__construct();

		$this->removeExpired();
	}

	/**
	 * Create link
	 * 
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function create()
	{
		mt_srand();

		$this->setProperties(
			array(
				'link_id'    => md5(mt_rand(0, time())),
				'session_id' => $this->xlite->session->getID(),
				'expiry'     => time() + self::TTL,
			)
		);

		return parent::create();
	}

	/**
	 * Get link 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	public function getLink()
	{
		$link = null;

		if ($this->isExists() && $this->get('link_id')) {
			$options = XLite::getInstance()->getOptions('host_details');

			$link = 'http://' . $options['http_host'] . $options['web_dir'];
			if (substr($link, -1) != '/') {
				$link .= '/';
			}

			$link .= 'cart.php?target=cmsconnector&action=landing&id=' . $this->get('link_id');
		}

		return $link;
	}

	/**
	 * Remove expired links
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0
	 */
	protected function removeExpired()
	{
		if (!self::$_removed) {
			$query = 'DELETE FROM ' . $this->getTable() . ' WHERE expiry < ' . time();
			$this->db->query($query);

			self::$_removed = true;
		}
	}
}

// WARNING :
// Please ensure that you have no whitespaces / empty lines below this message.
// Adding a whitespace or an empty line below this line will cause a PHP error.
?>
