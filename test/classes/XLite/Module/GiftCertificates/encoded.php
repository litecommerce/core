<?php

/**
* @package Module_GiftCertificates
* @access public
* @version $Id$
*/

/* This is an implementation of the order changeGCdebit method. See
* kernel/Order.php in this directory.
*/
function GiftCertificates_changeGCdebit(&$_this, $sign)
{
	if (!is_null($_this->get("gc"))) {
		$gc = $_this->get("gc");
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
