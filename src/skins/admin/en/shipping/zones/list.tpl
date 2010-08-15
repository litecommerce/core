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

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[

function visibleBox(id, status)
{
	    var Element = document.getElementById(id);
	    if (Element) {
	        Element.style.display = ((status) ? "" : "none");
	    }
}
function ShowNotes()
{
	    visibleBox("notes_url", false);
	    visibleBox("notes_body", true);
}

//]]>
</script>

Use this section to define shipping zones.

<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="NavigationPath" onClick="this.blur()"><b>How to define shipping zones &gt;&gt;&gt;</b></a></span>

<span id="notes_body" style="display: none"><br /><br />
Select a country or a state from a list, specify the zone where the country or state should be listed and click on the 'Apply' button. To select more than one country/state, hold down the CTRL key while making a selection. A zone can contain either countries or states. You cannot include both states and countries into the same zone.  
</span>

<hr />

{if:isZonesDefined()}

<script type="text/javascript" language="JavaScript 1.2">
//<![CDATA[

checkboxes_form = 'zonesform';
checkboxes = new Array({foreach:getShippingZones(),k,v}{if:!k=0},{end:}'to_delete[{v.getZoneId()}]'{end:});

lbl_no_items_have_been_selected = 'No items have been selected';
lbl_delete_confirmation = 'Are you sure you wish to delete the selected zones?';

function deleteZones()
{
  if (checkMarks(document.forms['zonesform'], new RegExp('to_delete\\[[0-9]+\\]', 'gi'))) {
    if (confirm(lbl_delete_confirmation)) {
      document.forms['zonesform'].submit();
    }
  }
}

//]]>
</script>

{end:}

<form action="admin.php" method="post" name="zonesform">

  <input type="hidden" name="target" value="shipping_zones" />
  <input type="hidden" name="action" value="delete" />

  <table cellpadding="3" cellspacing="1">

  <tbody FOREACH="getShippingZones(),k,zn">

    <tr>
      <td><input type="checkbox" name="to_delete[{zn.getZoneId()}]"{if:k=0} disabled="disabled"{end:}} /></td>
      <td>{if:k=0}{zn.getZoneName()} (all addresses){else:}<a href="admin.php?target=shipping_zones&zoneid={zn.getZoneId()}">{zn.getZoneName()}</a><span IF="!zn.hasZoneElements()"> (empty zone)</span>{end:}</td>
    </tr>

  </tbody>

  <tr>
  <td colspan="2">
  <br />
  <input type="button" value="Delete selected" onclick="javascript: deleteZones();" IF="isZonesDefined()" />
  </td>
</tr>

  {/if}

  </table>

</form>

<br />
<br />

<input type="button" value="Add zone" onclick="javascript: self.location='admin.php?target=shipping_zones&mode=add';" />

