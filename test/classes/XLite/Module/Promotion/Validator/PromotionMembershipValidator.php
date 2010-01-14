<?php

class XLite_Module_Promotion_Validator_PromotionMembershipValidator extends XLite_Validator_RequiredValidator
{	
	public $template = "common/required_validator.tpl";

	function isValid()
	{
       if (!parent::isValid()) {
            return false;
        }

		if($_POST['action']=="update2") {
				$specialOffer = new XLite_Module_Promotion_Model_SpecialOffer($_POST['offer_id']);
				if ($specialOffer->get("conditionType") == "hasMembership")
				{
					 $result =  !empty($_POST[$this->get("field")]);
				} else $result = true;
		} else $result = true; 
		return $result;
	}
}
?>
