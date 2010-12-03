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

namespace XLite\Module\CDev\Promotion\Controller\Admin;

define('DEFAULT_DC_EXPIRATION', 3600 * 24 * 7); // a week
define('DIALOG_SORT_MODE_ALL', 0);
define('DIALOG_SORT_MODE_ACTIVE', 1);
define('DIALOG_SORT_MODE_DISABLED', 2);
define('DIALOG_SORT_MODE_USED', 3);

/**
 * ____description____
 * 
 * @package XLite
 * @see     ____class_see____
 * @since   3.0.0
 */
class DiscountCoupons extends \XLite\Controller\Admin\AAdmin
{
    public $couponExists = false;

    function init()
    {
        $this->params[] = "pageID";

        if (!isset($_REQUEST['sort_mode'])) {
            // restore current filter
            $sm = $this->session->get('coupon_search_mode');
            if (is_array($sm) && (!empty($sm))) {
                $_REQUEST['sort_mode'] = $sm;
            }
        }

        parent::init();
    }

    function fillForm() 
    {
    	if (!isset($this->sort_mode)) {
            $this->sort_mode = array(0=>true);
        }
        
        // default coupon properties for add form
        $this->set('properties', array(
            "coupon" => $this->generateCouponCode(),
            "times"  => 1,
            "status" => "A",
            "discount" => "0.00",
            "type"     => "absolute",
            "applyTo"  => "total",
            "expire"   => time() + DEFAULT_DC_EXPIRATION,
            "minamount"=> "0.00"));
        parent::fillForm();
        // save current filter
        $this->session->set('coupon_search_mode', $this->sort_mode);
    }

    function isSortSelected($sortMode)
    {
        $sortMode = intval($sortMode);
        if (isset($this->sort_mode) && is_array($this->sort_mode) && isset($this->sort_mode[$sortMode]) && $this->sort_mode[$sortMode]) {
            return true;
        }
        return false;
    }

    function prepareSortConditions()
    {
        $sortConditions = array();

        if (!$this->isSortSelected(DIALOG_SORT_MODE_ALL)) {
            $sortConditionsRules = array
            (
                DIALOG_SORT_MODE_ACTIVE		=> "status='A'",
                DIALOG_SORT_MODE_DISABLED	=> "status='D'",
                DIALOG_SORT_MODE_USED		=> "status='U'",
            );
            foreach ($sortConditionsRules as $rule => $ruleCond) {
                if ($this->isSortSelected($rule)) {
                    $sortConditions[] = $ruleCond;
                }
            }
        }

        return $sortConditions;
    }

    function getCouponsNumber()
    {
    	$this->getCoupons();
        $couponsNumber = 0;
        if (is_array($this->_couponsArray)) {
            $couponsNumber = count($this->_couponsArray);
        }

        return $couponsNumber;
    }

    function getCoupons() 
    {
    	if (isset($this->_couponsArray)) {
    		return $this->_couponsArray;
    	}

        $dc = new \XLite\Module\CDev\Promotion\Model\DiscountCoupon();

        $condition = array("order_id='0'");
        $sortConditions = $this->prepareSortConditions();
        if (count($sortConditions) > 0) {
            $sortConditions = "(" . implode(' OR ', $sortConditions) . ")";
            $condition[] = $sortConditions;
        }

        $condition = implode(' AND ', $condition);

        $dc->fetchKeysOnly = true;
        $dc->fetchObjIdxOnly = true;

        $coupons = $dc->findAll($condition);

        $this->_couponsArray = $coupons;
        return $coupons;
    }

    function generateCouponCode() {
        return generate_code();
    }

    function _action_postprocess()
    {
        if (!$this->isSortSelected(DIALOG_SORT_MODE_ALL)) {
            $sortConditionsRules = array
            (
                DIALOG_SORT_MODE_ACTIVE		=> "sort_mode%5B1%5D",
                DIALOG_SORT_MODE_DISABLED	=> "sort_mode%5B2%5D",
                DIALOG_SORT_MODE_USED		=> "sort_mode%5B3%5D",
            );
            foreach ($sortConditionsRules as $rule => $ruleCond) {
                if ($this->isSortSelected($rule)) {
                    $this->params[] = $ruleCond;
                    $this->setComplex($ruleCond, $rule);
                }
            }
        }
    }

    function action_add() 
    {
        $dc = new \XLite\Module\CDev\Promotion\Model\DiscountCoupon();
        if ($dc->find("coupon='" . $this->get('coupon') . "' AND order_id='0'")) {
            $this->valid = false;
            $this->couponExists = true;
        } else {
            $_POST['discount'] = abs($_POST['discount']);
            $dc->set('properties', $_POST);
            $dc->set('expire', $this->get('expire'));
            $dc->create();
        }

        $this->_action_postprocess();
    }

    function action_update() 
    {
        if (isset($_POST['status'])) {
            foreach ($_POST['status'] as $coupon_id => $status) {
                $dc = new \XLite\Module\CDev\Promotion\Model\DiscountCoupon($coupon_id);
                $dc->set('status', $status);
                $dc->update();
            }
        }

        $this->_action_postprocess();
    }

    function action_delete() 
    {
        $dc = new \XLite\Module\CDev\Promotion\Model\DiscountCoupon($this->get('coupon_id'));
        $dc->delete();

        $this->_action_postprocess();
    }

    function isOddRow($row)
    {
        return (($row % 2) == 0) ? true : false;
    }

    function getRowClass($row,$odd_css_class, $even_css_class = null)
    {
        return ($this->isOddRow($row)) ? $odd_css_class : $even_css_class;
    }

    function canShowChildren($dc)
    {
        if (!$dc->getChildrenCount()) return false;
        if ($this->xlite->config->Promotion->auto_expand_coupon_orders) return true;
        if ($dc->get('coupon_id') != $this->get('children_coupon_id')) return false;
        return true;
    }
}
