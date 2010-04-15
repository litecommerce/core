/* vim: set ts=2 sw=2 sts=2 et: */

/**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 */

function initCountries()
{
	var elm = document.getElementById("billing_country_select");
	if (elm)
	{
	    populateStates(elm,"billing_state",true);
	}

	elm = document.getElementById("shipping_country_select");
	if (elm)
	{
	    populateStates(elm,"shipping_state",true);
	}

	elm = document.getElementById("location_country_select");
	if (elm)
	{
    	populateStates(elm,"location_state",true);
	}
}

function initStates()
{
	var elm = document.getElementById('billing_state_select');
	if (elm)
	{
		changeState(elm, "billing");
	}

	elm = document.getElementById('shipping_state_select');
	if (elm)
	{
		changeState(elm, "shipping");
	}

	var elm = document.getElementById('location_state');
	if (elm)
	{
		changeCompanyState(elm, "custom_location_state_body");
	}
}

initCountries();
initStates();
