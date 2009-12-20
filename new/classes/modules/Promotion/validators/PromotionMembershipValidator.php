<?php

class CPromotionMembershipValidator extends CRequiredValidator
{
	var $template = "common/required_validator.tpl";

	function isValid()
	{
       if (!parent::isValid()) {
            return false;
        }

		if($_POST['action']=="update2") {
				$specialOffer =& func_new("SpecialOffer",$_POST['offer_id']);
				if ($specialOffer->get("conditionType") == "hasMembership")
				{
					 $result =  !empty($_POST[$this->get("field")]);
				} else $result = true;
		} else $result = true; 
		return $result;
	}
}
?>
