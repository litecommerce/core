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

namespace XLite\Module\Promotion\Controller\Customer;

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class Checkout extends \XLite\Controller\Customer\Checkout implements \XLite\Base\IDecorator
{
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
            case 'bonusList':
                $location = 'Bonus list';
                break;
            case 'couponFailed':
                $location = 'Discount coupon failure';
                break;
        }
        
        return $location;
    }

    /**
     * Initialize controller 
     * 
     * @return void
     * @access public
     * @since  3.0.0
     */
    public function init()
    {
        parent::init();

        // TODO - check if there is a more convenient way to do this 
        if (self::CHECKOUT_MODE_ZERO_TOTAL == \XLite\Core\Request::getInstance()->mode) {
            $this->set('skipValidateDiscountCoupon', true);
        }
    }

    function _handleCouponFailed()
    {
        if ($this->session->isRegistered('couponFailed')) {
        	if (isset($_REQUEST['mode']) && $_REQUEST['mode'] == "couponFailed") {
                $dc = new \XLite\Module\Promotion\Model\DiscountCoupon();
                $found = $dc->find("coupon='".$this->session->get('couponFailed')."'");
                if ($found) {
                    $this->set('discountCoupon', $dc);
                }
        	} else {
        		$this->session->set('couponFailed', null);
        	}
        }
    }
    
    function handleRequest()
    {
        if ($this->cart->validateDiscountCoupon() == 'used' && !$this->get('skipValidateDiscountCoupon') && (!isset($_REQUEST['action']) || $_REQUEST['action'] != "return")) {
            //$this->session->set('couponFailed', $this->cart->get('DC'));
            $dc = $this->cart->get('DC');
        	$this->session->set('couponFailed', $dc->get('coupon'));
            $this->cart->set('DC', null); // remove coupon
            $this->updateCart();
            $this->redirect("cart.php?target=checkout&mode=couponFailed");
            return;
        }
        if (!isset($_REQUEST['action']) && !isset($_REQUEST['mode'])) {
            if (!$this->session->isRegistered('bonusListDisplayed') && $this->config->Promotion->showBonusList) {
                if ($this->cart->getBonusList()) {
                	$needRedirect = false;
                    $bonusList = $this->cart->get('bonusList');
                	foreach ($bonusList as $bonus) {
                		$products = $bonus->get('allBonusProducts');
                		if (is_array($products) && count($products) > 0) {
                			$needRedirect = true;
                			break;
                		}
                		$categories = $bonus->get('allBonusCategories');
                		if (is_array($categories) && count($categories) > 0) {
                			$needRedirect = true;
                			break;
                		}
                		if ($bonus->get('bonusType') == "bonusPoints") {
                			$needRedirect = true;
                			break;
                		}
                	}
                	if ($needRedirect) {
                        $this->redirect("cart.php?target=checkout&mode=bonusList");
                        return;
                    }
                }
            }//  else we have already shown the bonus list dialog
        }

        $this->_handleCouponFailed();

        parent::handleRequest();
    }
    
    function getBonusList()
    {
        // collect products & prices
        $this->bonusList = $this->cart->getBonusList();
        $this->session->set('bonusListDisplayed', 1);
        return $this->bonusList;
    }

    /**
    * Format number as interger
    */

    function integer($num)
    {
        return (double) ($num);
    }

    function isSecure()
    {
    	switch($this->mode) {
    		case "couponFailed":
    		return $this->isHTTPS();
    	}
    	return parent::isSecure();
    }

    function isShowBonus(&$bonus)
    {
        return ((bool) $bonus->get('allBonusProducts')
            || (bool) $bonus->get('allBonusCategories')
            || (bool) $bonus->get('bonusAllProducts')
            || $bonus->get('bonusType') == "bonusPoints"
        );
    }
}
