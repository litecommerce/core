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
