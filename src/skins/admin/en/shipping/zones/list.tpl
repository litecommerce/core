{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<script type="text/javascript">
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

{t(#Use this section to define shipping zones.#)}

<hr />

<br />

{if:isZonesDefined()}

<script type="text/javascript">
//<![CDATA[

checkboxes_form = 'zonesform';
checkboxes = new Array({foreach:getShippingZones(),k,v}{if:!k=0},{end:}'to_delete[{v.getZoneId()}]'{end:});

lbl_no_items_have_been_selected = '{t(#No items have been selected#)}';
lbl_delete_confirmation = '{t(#Are you sure you wish to delete the selected zones?#)}';

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

  <table class="data-table" width="500">

  <tbody FOREACH="getShippingZones(),k,zn" class="{getRowClass(k,#dialog-box#,#highlight#)}">

    <tr>
      <td><input type="checkbox" name="to_delete[{zn.getZoneId()}]"{if:zn.getZoneId()=1} disabled="disabled"{end:}} /></td>
      <td style="width:100%;"><a href="admin.php?target=shipping_zones&zoneid={zn.getZoneId()}">{zn.getZoneName()}</a></td>
    </tr>

  </tbody>

  <tr>
  <td colspan="2">
  <br />
  <widget class="\XLite\View\Button\Regular" IF="isZonesDefined()" label="Delete selected" jsCode="deleteZones();" />
  </td>
</tr>

  {/if}

  </table>

</form>

<br />
<br />

<widget class="\XLite\View\Button\Regular" label="Add zone" jsCode="self.location='admin.php?target=shipping_zones&mode=add';" />
