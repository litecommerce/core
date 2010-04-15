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
class XLite_Module_GiftCertificates_Controller_Admin_GiftCertificateEcard extends XLite_Controller_Admin_Abstract
{	
    public $params = array("target", "ecard_id");	
    protected $ecard = null;	
    public $returnUrl = "admin.php?target=gift_certificate_ecards";
    
    function getECard()
    {
        if (is_null($this->ecard)) {
            if (!$this->get("ecard_id")) {
                $this->ecard = new XLite_Module_GiftCertificates_Model_ECard();
                $this->ecard->set("enabled", 1);
            } else {
                $this->ecard = new XLite_Module_GiftCertificates_Model_ECard($this->get("ecard_id"));
            }
        }
        return $this->ecard;
    }
    
    function action_update()
    {
        if (!isset($_POST["enabled"])) {
            $_POST["enabled"] = 0; // checkbox
        }
        if (!empty($_POST["new_template"])) {
            $_POST["template"] = $_POST["new_template"];
        }

		$this->getECard()->set('properties', $_POST);
		$this->getECard()->isPersistent ? $this->getECard()->update() : $this->getECard()->create();

        $this->action_images();
    }

    function action_images()
    {
        $tn = $this->getECard()->get('thumbnail');
        $tn->handleRequest();
            
        $img = $this->getECard()->get('image');
        $img->handleRequest();
    }

}
