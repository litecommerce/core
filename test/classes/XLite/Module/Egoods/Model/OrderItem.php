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
 * @subpackage Model
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
class XLite_Module_Egoods_Model_OrderItem extends XLite_Model_OrderItem implements XLite_Base_IDecorator
{
    public function __construct()
    {
        $this->fields['pincodes'] = "";
        $this->fields['egoods'] = "";
        parent::__construct();
    }
    
    function isEgood()
    {
        return $this->isComplex('product.egood');
    }

    function isPin()
    {
        return $this->isComplex('product.pin');
    }

    function getPinCodes()
    {
        if (!isset($this->pin_codes)) {
            $this->pin_codes = explode(",", $this->get('pincodes'));
        }
        return $this->pin_codes;
    }

    function createPins()
    {
        require_once LC_MODULES_DIR . 'Egoods' . LC_DS . 'encoded.php';
        $pins = func_moduleEgoods_getPinCodes($this);
        if (is_array($pins)) {
            $this->set('pincodes', implode(',', $pins));
        } else {
            $this->set('pincodes', "");
        }
        $this->update();
    }

    function updateAmount($amount)
    {
        $amount = (int)$amount;

        if ($this->is('egood') && $amount > 0) {
            if ($this->is('pin')) {
                $pin = new XLite_Module_Egoods_Model_PinCode();
    			if ($amount > $pin->getFreePinCount($this->getComplex('product.product_id'))) {
        			$amount = $pin->getFreePinCount($this->getComplex('product.product_id'));
            		if ($amount <= 0) {
                		$amount = 1;
                    }
    			}
            } else {
                $amount = 1;
            }
        }

        parent::updateAmount($amount);
    }

    function isShipped()
    {
        if ($this->is('pin') || $this->is('egood')) {
            return false;
        }
        return parent::isShipped();
    }

    function storeLinks()
    {
        $product = $this->get('product');
        $links = $product->createLinks();
        $this->set('egoods', implode(',', $links));
        $this->update();
    }

    function unStoreLinks()
    {
        $ids = explode(",", $this->get('egoods'));
        $link = new XLite_Module_Egoods_Model_DownloadableLink();
        foreach ($ids as $link_id) {
            $egoods_links = $link->findAll("access_key='$link_id'");
            foreach ($egoods_links as $egoods_link) {
                $egoods_link->delete();
            }
        }
        $this->set('egoods', "");
        $this->update();
    }

    function hasValidLinks()
    {
        return ($this->get('egoods') == '') ? false : true;
    }

    function getEgoods()
    {
        if (!isset($this->_egoods)) {
            $egoods_links = explode(',', $this->get('egoods'));
            foreach ($egoods_links as $link_id) {
                $link = new XLite_Module_Egoods_Model_DownloadableLink($link_id);
                $file = new XLite_Module_Egoods_Model_DownloadableFile($link->get('file_id'));
                $record = array();
                $record['name'] = basename($file->get('data'));
                $record['link'] = $this->xlite->getShopUrl("cart.php?target=download&action=download&acc=") . $link_id;
                $record['expires'] = $link->get('expire_on');
                $record['exp_time'] = $this->getComplex('xlite.config.Egoods.exp_days');
                $record['downloads'] = $link->get('available_downloads');
                $record['delivery'] = $file->get('delivery');
                $this->_egoods []= $record;
            }
        }
        return $this->_egoods;
    }

    function hasValidPins()
    {
        if ($this->get('pincodes') != '') {
            return true;
        }
        return false;
    }
}
