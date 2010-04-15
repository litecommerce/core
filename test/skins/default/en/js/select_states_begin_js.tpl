{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
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
