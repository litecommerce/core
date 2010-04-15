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
 * @subpackage View
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
class XLite_Module_GiftCertificates_View_CEcardSelect extends XLite_View_Abstract
{	
    public $params = array('gcid');
    public $ecards = null;

    function getECards()
    {
        if (is_null($this->ecards)) {
			$eCard = new XLite_Module_GiftCertificates_Model_ECard();
            $this->ecards = $eCard->findAll("enabled = 1");
        }
        return $this->ecards;
    }

    function getGC()
    {
        if (is_null($this->gc)) {
            $this->gc = new XLite_Module_GiftCertificates_Model_GiftCertificate($this->get("gcid"));
        }
        return $this->gc;
    }

	protected function getDefaultTemplate()
	{
		if ($this->xlite->GiftCertificates_wysiwyg_work) {
			return "modules/GiftCertificates/ecards.tpl";
		}

		if ($this->xlite->is("adminZone")) {
			return "modules/GiftCertificates/ecard_select.tpl";
		} else {
			return "modules/GiftCertificates/ecards.tpl";
		}
	}

}
