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
{if:isCustomState()}
  <input type="text" name="{field}"{if:fieldId} id="{fieldId}"{end:} class="{getParam(#className#)} field-state {if:isLinked} linked{end:}" value="{getStateValue()}" />
{else:}
  <select name="{field}"{if:fieldId} id="{fieldId}"{end:} class="{getParam(#className#)} field-state {if:isLinked} linked{end:}">
   <option FOREACH="getStates(),v" value="{v.getStateId():r}" selected="{isStateSelected(v)}">{v.state}</option>
  </select>
{end:}
<script IF="isDefineStates()" type="text/javascript">
{getJSDataDefinitionBlock():h}
</script>
