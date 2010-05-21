{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Select state
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<select name="{field}"{if:onchange} onchange="{onchange}"{end:}{if:fieldId} id="{fieldId}"{end:} class="field-state">
   <option value="0">Select one..</option>
   <option value="-1" selected="{state=-1}">Other</option>
   <option FOREACH="getStates(),v" value="{v.state_id:r}" selected="{v.state_id=state}">{v.state}</option>
</select>
<script IF="isDefineStates()" type="text/javascript">
var CountriesStates = [];
{foreach:getCountriesStates(),country_code,val}
CountriesStates.{country_code} = [
{foreach:val,state_code,v}
{ state_code: "{state_code}", state: "{v}" },
{end:}
  false
];
{end:}
</script>
<script IF="isLinked" type="text/javascript">
$(document).ready(
  function() {
    $('.field-state[name="{field}"]').each(
      function () {
        new StateSelectorController(this);
      }
    );
  }
);
</script>
