<script language="Javascript" type="text/javascript">
var CountriesStates = new Array();
{foreach:countriesStates,country_code,val}
CountriesStates["{country_code}"] = new Array();
{if:val.number}
{foreach:val.data,state_code,v}
var stateData = new Array();
stateData["state_code"] = "{state_code}";
stateData["state"] = "{v}";
CountriesStates["{country_code}"].push(stateData);
{end:}
{end:}
{end:}
</script>
<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/select_states_begin.js"></script>
