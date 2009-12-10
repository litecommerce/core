function initCountries()
{
	var elm = document.getElementById("billing_country_select");
	if (elm)
	{
    	populateStates(elm,"billing",true);
	}
	elm = document.getElementById("shipping_country_select");
	if (elm)
	{
    	populateStates(elm,"shipping",true);
	}
	elm = document.getElementById("contactus_country_select");
    if (elm)
    {
        populateStates(elm,"contactus",true);
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
    elm = document.getElementById('contactus_state_select');
    if (elm)
    {
        changeState(elm, "contactus");
    }
}

initCountries();
initStates();
