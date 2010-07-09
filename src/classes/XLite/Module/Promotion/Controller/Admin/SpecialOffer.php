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
class XLite_Module_Promotion_Controller_Admin_SpecialOffer extends XLite_Controller_Admin_AAdmin
{
    public $params = array('target', "offer_id", "mode");
    public $bonusAllCountries = 1;
    public $countries = null;

    function getCountries()
    {
        if (is_null($this->countries)) {
            $c = new XLite_Model_Country();
            $this->countries = $c->findAll();
        }
        return $this->countries;
    }

    function hasMemberships()
    {
        $memberships = $this->config->Memberships->memberships;
        return !empty($memberships);
    }

    function getSpecialOffer()
    {
        if (!isset($this->specialOffer) || is_null($this->specialOffer)) {
            if (!$this->get('offer_id')) {
                // default special offer
                $this->specialOffer = new XLite_Module_Promotion_Model_SpecialOffer();
                // default values
                $this->specialOffer->set('conditionType', 'productAmount');
                $this->specialOffer->set('bonusType', 'discounts');
            } else {
                $this->specialOffer = new XLite_Module_Promotion_Model_SpecialOffer($this->get('offer_id'));
            }
        }
        return $this->specialOffer;
    }

    function fillForm()
    {
        // default form values
        $this->set('properties', $this->getComplex('specialOffer.properties'));

        parent::fillForm();
        
    }

    function init()
    {
        parent::init();
        $this->product = $this->getComplex('specialOffer.product');
        $this->category = $this->getComplex('specialOffer.category');
        $this->products = $this->getComplex('specialOffer.products');
        $this->bonusProducts = $this->getComplex('specialOffer.bonusProducts');
        $this->bonusPrices = $this->getComplex('specialOffer.bonusPrices');
        $this->bonusCategory = $this->getComplex('specialOffer.bonusCategory');
        $this->conditionType = $this->getComplex('specialOffer.conditionType');
        $this->bonusType = $this->getComplex('specialOffer.bonusType');
    }

    function isSelectedMembership($selected_membership)
    {
        $membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
        $memberships = $membership->findAll("offer_id = " . $this->get('offer_id'));
        foreach ($memberships as $membership_) 
            if ($selected_membership == $membership_->get('membership')) return true;
        return false;
    }
    /**
    * Submit the first form (special offer type)
    */
    
    function action_update1()
    {
        if ($_POST['conditionType'] == "eachNth") {
            $_POST['bonusType'] = "specialPrices";
        }
        $this->setComplex('specialOffer.properties', $_POST);
        // if a new offer, adds one
        if (!$this->getSpecialOffer()->isPersistent) {
            $this->getSpecialOffer()->create();
            $this->set('offer_id',  $this->getSpecialOffer()->get('offer_id'));
        } else {
            $this->getSpecialOffer()->update();
        }
        $this->set('mode', "details");
    }

    /**
    * Submit the second form (special offer details)
    */
    function action_update2()
    {
        $stayHere = false; // something is added/removed from this page
        if (!isset($_POST['bonusAllProducts'])) {
            $_POST['bonusAllProducts'] = 0; // unchecked checkbox
        } else {
            $_POST['bonusCategory_id'] = 0;
        }
        if (!isset($_POST['allProducts'])) {
            $_POST['allProducts'] = 0; // unchecked checkbox
        } elseif ($_POST['allProducts']) {
            $_POST['product_id'] = 0;
            $_POST['category_id'] = 0;
        }
        $_POST['start_date'] = mktime(0,0,0,$_POST['start_dateMonth'],$_POST['start_dateDay'],$_POST['start_dateYear']);
        $_POST['end_date'] = mktime(23,59,59,$_POST['end_dateMonth'],$_POST['end_dateDay'],$_POST['end_dateYear']);

        if ($_POST['start_date'] <= time()&&$_POST['end_date'] >= time()) $_POST['status'] = 'Available';
        elseif ($_POST['end_date'] < time()) $_POST['status'] = 'Expired';
        else $_POST['status'] = 'Upcoming';
        
        $_POST['status'] == 'Expired' ? $_POST['enabled'] = 0 : $_POST['enabled'] = 1;
        $specialOffer = $this->get('specialOffer');
        $specialOffer->set('properties', $_POST);
        if ($this->get('conditionType') == 'hasMembership')	{
            $membership = new XLite_Module_Promotion_Model_SpecialOfferMembership();
            $memberships = $membership->findAll('offer_id =' . $this->get('offer_id'));
            foreach ($memberships as $membership_) {
                $membership_->delete();
            }
            if (is_array($_POST['customer_memberships']))
            foreach ($_POST['customer_memberships'] as $membership_) {
                $membership->set('offer_id',$this->get('offer_id'));
                $membership->set('membership',$membership_);
                $membership->create();
            }
        }
        if ($this->get('deleteProduct')) {
            $stayHere = true;
            foreach ($this->get('deleteProduct') as $product_id => $checked) {
                $specialOffer->deleteProduct( new XLite_Model_Product($product_id), 'C');
            }
        }
        if ($this->get('deleteBonusProduct')) {
            $stayHere = true;
            foreach ($this->get('deleteBonusProduct') as $product_id=>$checked) {
                $specialOffer->deleteProduct( new XLite_Model_Product($product_id), 'B');
            }
        }
        if ($this->get('bonusAllProducts')) {
            $stayHere = true;
            $so_product = new XLite_Module_Promotion_Model_SpecialOfferProduct();
            $so_products = $so_product->findAll("offer_id='". $specialOffer->get('offer_id') . "' AND type='B'");
            foreach ($so_products as $product) {
                $specialOffer->deleteProduct( new XLite_Model_Product($product->get('product_id')), "B");
            }
        }
        if ($this->get('deleteBonusPrice')) {
            $stayHere = true;
            foreach ($this->get('deleteBonusPrice') as $product_id => $checked) {
                list ($product_id, $category_id) = explode('_', $product_id);
                if ($product_id) {
                    $product = new XLite_Model_Product($product_id);
                } else {
                    $product = null;
                }
                if ($category_id) {
                    $category = new XLite_Model_Category($category_id);
                } else {
                    $category = null;
                }
                $specialOffer->deleteBonusPrice($product, $category);
            }
        }
        if ($this->get('changeBonusPrice')) {
            $stayHere = true;
            foreach ($this->get('changeBonusPrice') as $product_id => $price) {
                list ($product_id, $category_id) = explode('_', $product_id);
                if ($product_id) {
                    $product = new XLite_Model_Product($product_id);
                } else {
                    $product = null;
                }
                if ($category_id) {
                    $category = new XLite_Model_Category($category_id);
                } else {
                    $category = null;
                }
                $specialOffer->changeBonusPrice($product, $category, $price);
            }
        }
        if ($this->get('addBonusProduct_id')) {
            $stayHere = true;
            // add bonus product
            $specialOffer->addProduct( new XLite_Model_Product($this->get('addBonusProduct_id')), 'B');
        }
        if ($this->get('addProduct_id')) {
            $stayHere = true;
            // add product
            $specialOffer->addProduct( new XLite_Model_Product($this->get('addProduct_id')), 'C');
        }
        if ($this->get('addBonusPriceProduct_id') || $this->get('addBonusPriceCategory_id')) {
            $stayHere = true;
            // add bonus price
            if ($this->get('addBonusPriceProduct_id')) {
                $product = new XLite_Model_Product($this->get('addBonusPriceProduct_id'));
            } else {
                $product = null;
            }
            if ($this->get('addBonusPriceCategory_id')) {
                $category = new XLite_Model_Category($this->get('addBonusPriceCategory_id'));
            } else {
                $category = null;
            }
            $specialOffer->addBonusPrice($product, $category, $this->get('addBonusPrice'), $this->get('addBonusType'));
        }
        $specialOffer->update();
        // sometimes, return back to the same page
        if (!$stayHere) {
            //$this->set('returnUrl', "admin.php?target=SpecialOffers");
            // strange behavior !?
        }
    }

}
