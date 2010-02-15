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
* Displays the top of main mape.
*
* @package View
* @access public
* @version $Id$
*/
class XLite_View_Pager extends XLite_View
{	
    protected $_data = array();
	protected $_pagesCount = 0;

	protected $_baseObj = null;

    public $template = 'common/pager.tpl';
    public $pageID = 0;
    public $params = array('pageID');
    public $itemsPerPage = 10;

	protected $pages = null;

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
        $this->pageID = XLite_Core_Request::getInstance()->pageID;
    }

	public function __set($name, $value)
	{
		if ('data' == $name) {

            if (0 >= $this->get('itemsPerPage')) {
                $this->_pagesCount = 1;
                $this->_data = array($value);

            } elseif (is_array($value)) {
                $this->_pagesCount = intval(count($value) / $this->get('itemsPerPage'));
                $this->_data = array_slice($value, $this->get('pageID') * $this->get('itemsPerPage'), $this->get('itemsPerPage'));
            }

            unset($value);

        } else {

            $this->$name = $value;
        }
	}

    public function initView()
    {
		parent::initView();

        if ($this->get('pageID') === '') {
            $this->set('pageID', 0);

        } else if ($this->get('pageID') && $this->_pagesCount <= $this->get('pageID')) {
            $this->set('pageID', $this->_pagesCount - 1);
        }
    }
    
    public function getPageData()
    {
		if (!isset($this->_baseObj)) {
            $this->_baseObj = new XLite_Model_Abstract();
        }

        if ($this->_baseObj->isObjectDescriptor(current($this->_data))) {
            foreach ($this->_data as &$object) {
                $object = $this->_baseObj->descriptorToObject($object);
            }
        }

        return $this->_data;
    }

	function getPageUrls()
    {
        $result = array();

        $dialog = $this->getDialog();
        $params = $dialog->get('allParams');

        for ($i = 0; $i < $this->_pagesCount; $i++) {
            $params['pageID'] = $i;
            $result[$i+1] = $dialog->getUrl($params);
        }
        return $result;
    }

    function isMoreThanOnePage()
    {
        return $this->_pagesCount > 1;
    }

    function isCurrentPage($num)
    {
        return $this->get('pageID') + 1 == $num;
    }
}

