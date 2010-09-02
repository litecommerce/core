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

namespace XLite\Module\USPS\Controller\Admin;

/**
 * USPS administrative controller
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Usps extends \XLite\Controller\Admin\AAdmin
{
    /**
     * Controller parameters
     * 
     * @var    array
     * @access public
     * @see    ____var_see____
     * @since  3.0.0
     */
    public $params = array('target', 'updated');

    public $settings;

    public $error = '';

    public $updated = false;

    public $testResult = false; // this is a test request

    // test data
    //     public $ounces = 1;
    //     public $destinationCountry = 'United Kingdom (Great Britain)';
    //     public $ZipDestination = '73003';

    public $page = 'usps';

    public $mailtypes = array(
        'Package'                  => 'Package',
        'Postcards or Aerogrammes' => 'Postcards or Aerogrammes',
        'Matter for the Blind'     => 'Matter for the Blind',
        'Envelope'                 => 'Envelope'
    );

    public $containers_express = array(
        'NONE'               => 'None',
        'FLAT RATE ENVELOPE' => 'Express Mail Flat Rate Envelope',
    );

    public $containers_priority = array(
        'NONE'               => 'None',
        'FLAT RATE ENVELOPE' => 'Priority Mail Flat Rate Envelope',
        'FLAT RATE BOX'      => 'Priority Mail Flat Rate Box',
        'RECTANGULAR'        => 'Priority Mail Rectangular (Large)',
        'NONRECTANGULAR'     => 'Priority Mail Non Rectangular (Large)',
    );

    public $fcmailtypes = array(
        'LETTER' => 'Letter',
        'FLAT'   => 'Flat',
        'PARCEL' => 'Parcel',
    );

    /**
     * Constructor
     * 
     * @param array $params Parameters
     *  
     * @return void
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function __construct(array $params)
    {
        parent::__construct($params);

        $usps = new \XLite\Module\USPS\Model\Shipping\Usps();
        $this->settings = $usps->getOptions();
    }

    /**
     * Update settings
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionUpdate()
    {
        $usps = new \XLite\Module\USPS\Model\Shipping\Usps();
        $usps->setOptions((object)\XLite\Core\Request::getInstance()->getData());
        $this->set('updated', '1');
    }

    /**
     * Get weight (ounces) for test request
     * 
     * @return integer
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getOunces()
    {
        return isset(\XLite\Core\Request::getInstance()->ounces)
            ? \XLite\Core\Request::getInstance()->ounces
            : 1;
    }
    
    /**
     * Get destination country for test request
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getDestinationCountry()
    {
        return isset(\XLite\Core\Request::getInstance()->destinationCountry)
            ? \XLite\Core\Request::getInstance()->destinationCountry
            : 'United Kingdom (Great Britain)';
    }
    
    /**
     * Get destination zipcode for test request
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZipDestination()
    {
        return isset(\XLite\Core\Request::getInstance()->ZipDestination)
            ? \XLite\Core\Request::getInstance()->ZipDestination
            : '73003';
    }

    /**
     * Get origination zipcode for test request
     * 
     * @return string
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getZipOrigination()
    {
        return $this->config->Company->location_zipcode;
    }
 
    /**
     * Test international request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionIntTest()
    {
        $usps = new \XLite\Module\USPS\Model\Shipping\Usps();
        $this->set('properties', \XLite\Core\Request::getInstance()->getData());
        $this->rates = $usps->getInternationalRatesQuery(
            $this->getOunces(),
            $this->getDestinationCountry(),
            $usps->getOptions()
        );

        $this->testResult = true;
        $this->valid = false;
    }

    /**
     * test national request
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionNatTest()
    {
        $usps = new \XLite\Module\USPS\Model\Shipping\Usps();
        $this->set('properties', \XLite\Core\Request::getInstance()->getData());

        $this->rates = $usps->getNationalRatesQuery(
            $this->getOunces(),
            $this->getZipOrigination(),
            $this->getZipDestination(),
            $usps->getOptions()
        );

        $this->testResult = true;
        $this->valid = false;
    }

    /**
     * Clear USPS cache 
     * 
     * @return void
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function doActionClearCache()
    {
        $usps = new \XLite\Module\USPS\Model\Shipping\Usps();
        $usps->cleanCache();
    }

}
