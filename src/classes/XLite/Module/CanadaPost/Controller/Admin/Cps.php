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
class XLite_Module_CanadaPost_Controller_Admin_Cps extends XLite_Controller_Admin_ShippingSettings
{
    public $params = array('target', "updated");
    public $page		="cps";
    public $updated 	= false;
    public $testResult = false;
    public $settings;
    public $rates 		= array();

    public function __construct(array $params)  
    {
        parent::__construct($params);

        $cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
        $this->settings = $cps->get('options');
    }
    
    function action_update()  
    {
        $cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
        if (!isset($_POST['test_server'])) {
            $_POST['test_server'] = 0;
        }
        $cps->set('options',(object)$_POST);
        $this->set('updated', true);

    }
    
    function action_test()  
    {
        if (empty($this->weight)) 
            $this->weight = 1;
        if (empty($this->destinationZipcode)) 
            $this->destinationZipcode = $this->config->Company->location_zipcode;
        if (empty($this->destinationCountry)) 
            $this->destinationCountry = $this->config->Company->location_country;
        $state = XLite_Core_Database::getEM()->find('XLite_Model_State', $this->destinationState);
        $state = $state ? $state->code : 'Other';
 
        $this->cps = new XLite_Module_CanadaPost_Model_Shipping_Cps();
        $options = $this->cps->get('options');
        $options->packed == 'Y' ? $packed = "<readyToShip/>" : $packed = "";

        $this->rates = $this->cps->queryRates(
                $options, 
                $this->config->Company->location_zipcode, 
                $this->config->Company->location_country, 
                0, 
                $this->weight,
                "Test ",
                $packed, 
                $this->destinationCity,
                $this->destinationZipcode,
                $state,
                $this->destinationCountry);
        $this->testResult = true;
        $this->valid	  = false;
    }

}
