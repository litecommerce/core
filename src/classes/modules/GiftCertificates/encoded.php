<?php

/**
* @package Module_GiftCertificates
* @access public
* @version $Id: encoded.php,v 1.4 2007/04/12 07:42:46 sheriff Exp $
*/

/* This is an implementation of the order changeGCdebit method. See
* kernel/Order.php in this directory.
*/
function GiftCertificates_changeGCdebit(&$_this, $sign)
{
    // check for module license
    check_module_license("GiftCertificates");

	if (!is_null($_this->get("gc"))) {
		$gc =& $_this->get("gc");
		$gc->set("debit", $gc->get("debit")+$sign*$_this->get("payedByGC"));
		if ($gc->get("debit")<=0) {
			$gc->set("status", "U");
		} else {
			$gc->set("status", "A");
		}
		$gc->update();
	}
}

?>
