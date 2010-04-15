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
class XLite_Module_Affiliate_Controller_Customer_PartnerBanners extends XLite_Module_Affiliate_Controller_Partner
{	
    public $params = array('target', 'mode', 'category_id');


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

		$this->locationPath->addNode(
			new XLite_Model_Location('Banners', $this->get('mode') ? $this->buildURL('partner_banners') : null)
		);
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
        $location = parent::getLocation();

        switch ($this->get('mode')) {
            case 'home':
                $location = 'Main page banners';
                break;
            case 'affiliate':
                $location = 'Affiliate register link';
                break;
            case 'categories':
                $location = 'Category banners';
                break;
        }

        return $location;
    }

    
    function getBanners()
    {
        if (is_null($this->banners)) {
            $this->banner = new XLite_Module_Affiliate_Model_Banner();
            $this->banners = $this->banner->findAll();
        }
        return $this->banners;
    }

    function getCategory()
    {
        if (is_null($this->category)) {
            $this->category = new XLite_Model_Category($this->get("category_id"));
        }
        return $this->category;
    }
}
