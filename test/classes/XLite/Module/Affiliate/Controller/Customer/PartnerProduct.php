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
class XLite_Module_Affiliate_Controller_Customer_PartnerProduct extends XLite_Module_Affiliate_Controller_Partner
{
    public $params = array('target', 'product_id', 'schema', 'mode', 'backUrl');

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
    
        $this->locationPath->addNode(new XLite_Model_Location('Banners', $this->buildURL('partner_banners')));
        $this->locationPath->addNode(new XLite_Model_Location('Product banners', $this->get('backUrl')));
    }

    /**
     * Common method to determine current location 
     * 
     * @return array
     * @access protected 
     * @since  3.0.0
     */     
    protected function getLocation()
    {
        return $this->getProduct()->get('name');
    }

    function initView()
    {
        parent::initView();
        if (is_null($this->get('update'))) {
            $schema = $this->getComplex('config.Miscellaneous.partner_product_banner');
            foreach ($schema as $param => $value) {
                $this->$param = $value;
            }
        } else {
            // update config values
            $config = new XLite_Model_Config();
            if ($config->find("name='partner_product_banner'")) {
                $schema = $this->getComplex('config.Miscellaneous.partner_product_banner');
                foreach ($schema as $param => $value) {
                    $schema[$param] = $this->$param;
                }
                $config->set("value", serialize($schema));
                $config->update();
            }
        }
    }

    function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = new XLite_Model_Product($this->product_id);
        }
        return $this->product;
    }
}
