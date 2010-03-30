<script language="Javascript" type="text/javascript">

var CountriesStates = new Array();

{foreach:countriesStates,country_code,country}

CountriesStates["{country_code}"] = new Array();

  {if:country.number}
    
    {foreach:country.data,state_code,state}
    
var stateData = new Array();
stateData["state_code"] = "{state_code}";
stateData["state"] = "{state}";
CountriesStates["{country_code}"].push(stateData);

    {end:}

  {end:}

  {end:}

</script>
<script type="text/javascript" language="JavaScript 1.2" src="skins/admin/en/js/select_states_begin.js"></script>
