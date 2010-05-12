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
class XLite_Module_Egoods_Model_Product extends XLite_Model_Product implements XLite_Base_IDecorator
{
    public $egoodsNumber = null;

    public function __construct($p_id = null)
    {
        $this->fields['egood_free_for_memberships'] = '';
        parent::__construct($p_id);
    }
    
    function getEgoodsNumber()
    {
        if (!isset($this->egoodsNumber)) {
            $this->getEgoods();
        }

        return $this->egoodsNumber;
    }

    function getEgoods()
    {
        if (!isset($this->egoods)) {
            $df = new XLite_Module_Egoods_Model_DownloadableFile();
            $this->egoods = $df->findAll('product_id=' . $this->get('product_id'));
            $this->egoodsNumber = (is_array($this->egoods)) ? count($this->egoods) : 0;
        }
        return $this->egoods;
    }

    function getLinkDeliveryFiles()
    {
        $files = array();
        if (!$this->is('egood')) {
            return (object)$files;
        }
        $egoods = $this->get('egoods');
        for ($i = 0; $i < count($egoods); $i ++) {
            if ($egoods[$i]->get('delivery') == 'L') {
                $file = array();
                $file['name'] = basename($egoods[$i]->get('data'));
                $links = $egoods[$i]->get('activeLinks');
                foreach($links as $key=>$link) {
                    $file['links'][] = $this->xlite->getShopUrl('cart.php?target=download&action=download&acc=') . $link->get('access_key');
                }
                
                $files []= $file;
            }
        }
        return (object)$files;
    }

    function getValidLinkDeliveryFiles()
    {
        $files = $this->get('linkDeliveryFiles');
        $valid = array();
        foreach ($files as $key=>$file) {
            if (count($file['links']) > 0) {
                $valid []= $file;
            }
        }
        return $valid;
    }

    function hasValidLinks()
    {
        $valid = $this->get('validLinkDeliveryFiles');
        return (empty($valid)) ? false : true;
    }

    function getMailDeliveryFiles()
    {
        $files = array();
        $egoods = $this->get('egoods');
        for ($i = 0; $i < count($egoods); $i ++) {
            if ($egoods[$i]->get('delivery') == 'M') {
                $files [] = $egoods[$i];
            }
        }
        return $files;
    }

    function isEgood()
    {
        if (count($this->get('egoods')) == 0) {
            return false;
        }
        return true;
    }

    function isPin()
    {
        if ($this->get('pin_type') != '' && $this->get('pin_type') != 'N') {
            return true;
        }
        return false;
    }

    function getPin_type()
    {
        return $this->getComplex('pinSettings.pin_type');
    }

    function isFreeForMembership($membership)
    {
        if (($membership == '') || (!$this->is('egood'))) {
            return false;
        }
        $free_for_memberships = split(',', $this->get('egood_free_for_memberships'));
        return in_array($membership, $free_for_memberships);
    }

    function getPinSettings()
    {
        if (!isset($this->pin_settings)) {
            $this->pin_settings = new XLite_Module_Egoods_Model_PinSettings();
            if (!$this->pin_settings->find('product_id=' . $this->get('product_id'))) {
                $this->pin_settings->set('product_id', $this->get('product_id'));
            }
        }
        return $this->pin_settings;
    }

    function createLink($file_id)
    {
        $dl = new XLite_Module_Egoods_Model_DownloadableLink(md5(microtime()));
        $dl->set('file_id', $file_id);
        $dl->set('exp_time', mktime(0, 0, 0, 
                date("n", time()), 
                date("j", time()) + $this->getComplex('xlite.config.Egoods.exp_days'), 
                date("Y", time())
        ));
        
        $dl->set('available_downloads', $this->getComplex('xlite.config.Egoods.exp_downloads'));
        $dl->set('expire_on', $this->getComplex('xlite.config.Egoods.link_expires'));
        $dl->set('link_type', 'A');
        $dl->create();
        return $dl->get('access_key');
    }

    function createLinks()
    {
        $acc = array();
        $df = new XLite_Module_Egoods_Model_DownloadableFile();
        $files = $df->findAll("product_id=" . $this->get('product_id'));
        for ($i = 0; $i < count($files); $i++) {
            if ($files[$i]->get('delivery') == 'L') {
                $acc []= $this->createLink($files[$i]->get('file_id'));
            }
        }
        return $acc;
    }

    function filter()
    {
        if ($this->xlite->is('adminZone')) {
            return parent::filter();
        }
        $pin = new XLite_Module_Egoods_Model_PinCode();
        $avail_amount = $pin->getFreePinCount($this->get('product_id'));
        if ($this->is('pin') && $avail_amount < 1) {
            return false;
        }
        return parent::filter();
    }
}
