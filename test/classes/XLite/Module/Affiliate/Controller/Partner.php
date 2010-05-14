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
 * @subpackage Controller
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
class XLite_Module_Affiliate_Controller_Partner extends XLite_Controller_Abstract
{
    public $template = "modules/Affiliate/main.tpl";

    protected $shopLayout = null;


    /**
     * Add the base part of the location path
     * 
     * @return void
     * @access protected
     * @since  3.0.0
     */
    protected function addBaseLocation()
    {
        parent::addBaseLocation();

        if ('partner' !== XLite_Core_Request::getInstance()->target) {
            $this->locationPath->addNode(new XLite_Model_Location('Partner zone', $this->buildURL('partner')));
        }
    }



    function init()
    {
        parent::init();
        if ($_SERVER['REQUEST_METHOD'] == "GET" && $this->get('target') != "banner" && $this->get('target') != "product_banner" && !$this->xlite->is('adminZone') && isset($_GET['partner']) && (!isset($_COOKIE['PartnerID']) || (isset($_COOKIE['PartnerID']) && $_COOKIE['PartnerID'] != $_GET['partner']))) {
            $stats = new XLite_Module_Affiliate_Model_BannerStats();
            $stats->logClick();
            // issue a partner cookie
            if ($this->getComplex('config.Affiliate.partner_cookie_lifetime')) {
                // store for "lifetime" days
                $expire = time() + $this->getComplex('config.Affiliate.partner_cookie_lifetime') * 3600 * 24;
                $domain = func_parse_host(XLite::getInstance()->getOptions(array('host_details', 'http_host')));
                setcookie('PartnerID', $_GET['partner'], $expire, "/", $domain);
                setcookie('PartnerClick', $stats->get('stat_id'), $expire, "/", $domain);
            }
            $this->session->set('PartnerID', $_GET['partner']);
            $this->session->set('PartnerClick', $stats->get('stat_id'));
        }
    }

    protected function redirect($url = null)
    {
        if ($this->get('mode') == "access_denied") {
            $this->set('mode', "accessDenied");
        }

        parent::redirect($url);
    }

    function getShopLayout()
    {
        if (is_null($this->shopLayout)) {
            $this->shopLayout = XLite_Model_Layout::getInstance();
        }
        return $this->shopLayout;
    }

    function getRowClass($idx, $class1, $class2 = null)
    {
        $classMethods = array_map('strtolower', get_class_methods($this));
        $isNewRC = in_array('isoddrow', $classMethods);
        if ($isNewRC) {
            return parent::getRowClass($idx, $class1, $class2);
        } else {
            return ($idx % 2 == 0) ? $class1 : $class2;
        }
    }

    function getShopUrl($url, $secure = false, $pure_url = false)
    {
        $url = parent::getShopUrl($url, $secure, $pure_url);
        if ($pure_url) {
            $sid = $this->session->getName() . "=" . $this->session->getID();
            if (strpos($url, $sid) !== false) {
                if (strpos($url, $sid . "&") !== false) {
                    $sid = $sid . "&";
                }
                $url = str_replace($sid, "", $url);
                $lastSymbol = substr($url, strlen($url)-1, 1);
                if ($lastSymbol == "?" || $lastSymbol == "&") {
                    $url = substr($url, 0, strlen($url)-1);
                }
            }
        }
        return $url;
    }

    function fillForm()
    {
        if (!isset($this->startDate)) {
            $date = getdate(time());
            $this->set('startDate', mktime(0,0,0,$date['mon'],1,$date['year']));
        }
        parent::fillForm();
    }

    function getAccessLevel()
    {
        return $this->auth->get('partnerAccessLevel');
    }

    function getSecure()
    {
        return $this->getComplex('config.Security.customer_security');
    }
}
