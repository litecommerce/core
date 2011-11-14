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

<p IF="message=#added#"><span class="success-message">{t(#Shipping rate has been added successfully#)}</span></p>

<p IF="message=#add_failed#"><font class="error-message">{t(#Shipping rate cannot be added#)}<br />{t(#Please make sure that <b>"min weight", "min total", "min items", "shipping zone", "shipping method"</b> fields do not overlap with other shipping rates.#)}</p>

<script type="text/javascript">
<!--

function visibleBox(id, status)
{
  var Element = document.getElementById(id);
  if (Element) {
    Element.style.display = ((status) ? '' : 'none');
  }
}

function ShowNotes()
{
  visibleBox('notes_url', false);
  visibleBox('notes_body', true);
}

-->
</script>

{t(#Use this section to define rules for calculating shipping rates.#)}

<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="navigation-path" onclick="this.blur()"><b>{t(#How to define shipping rates#)} &gt;&gt;&gt;</b></a></span>
<span id="notes_body" style="display: none">
<br /><br />
<p align="justify">
  {t(#Shipping rates are comprised of several components (rate types) and are calculated according to the following generic patterns:#)}
</p>
<p align="justify">
  {t(#SHIPPING = flat + ITEMS*per_item + WEIGHT*per_weight + SUBTOTAL*(% of subtotal)/100;#,_ARRAY_(#X#^config.General.weight_unit))}
</p>
<br />
<p align="justify">
  {t(#For real-time shipping methods (USPS, UPS, Intershipper):#)}
</p>
<p align="justify">
  {t(#SHIPPING = raw on-line_rate + flat + ITEMS*per_item + WEIGHT*per_weight + SUBTOTAL*(% of subtotal)/100;#,_ARRAY_(#X#^config.General.weight_unit))}
</p>
<br />
<p align="justify">
  {t(#Based on these patterns, different shipping rate formulas can be defined for different order weight and price ranges and quantities of items. Ranges cannot overlap.#)}
</p>
<br />
<p align="justify">
  {t(#Descriptions for various rate types are provided below:#)}
</p>
<br />

<p align="justify">
<b>{t(#Flat shipping charge (flat)#)}</b><br />
  {t(#This component is added to the shipping rate regardless of the weight, price and number of items ordered.#)}
</p>
<br />
<p align="justify">
<b>{t(#Shipping charge based on percentage of order subtotal (% of subtotal)#)}</b><br />
  {t(#Use this component to adjust shipping rates according to order subtotals.#)}
</p>
<br />
<p align="justify">
<b>{t(#Flat shipping charge per item ordered (per item)#)}</b><br />
  {t(#Use this component to add an extra charge for every item ordered.#)}
</p>
<br />
<p align="justify">
<b>{t(#Flat shipping charge per X ordered (per Y)#,_ARRAY_(#X#^config.General.weight_symbol,#Y#^config.General.weight_unit))}</b><br />
  {t(#Use this component to specify weight-based charges.#)}
</p>
<br />
<p align="justify">
<b>{t(#Raw online rate (on-line rate)#)}</b> <br />
  {t(#This component is automatically calculated by shipping add-on modules and cannot be edited.#)}
</p>
<br />
</span>

<hr />

<form name="charges_methods" method="post" action="admin.php">

  <input type="hidden" name="target" value="shipping_rates" />
  <input type="hidden" name="action" value="change" />

  <table>

    <tr>
      <td colspan="2">{t(#Edit charges for:#)}</td>
    </tr>

    <tr>
      <td>
        <select name="methodid" onchange="document.charges_methods.submit();">
          <option value="">{t(#All shipping methods#)}</option>
          <option FOREACH="getShippingMethods(),m" value="{m.getMethodId()}" selected="{isSelected(m,#method_id#,methodid)}">{m.getName():h}</option>
        </select>
      </td>

      <td>
        <select name="zoneid" onchange="document.charges_methods.submit();">
          <option value="">{t(#All shipping zones#)}</option>
          <option FOREACH="getShippingZones(),k,zn" value="{zn.getZoneId()}" selected="{isSelected(zn,#zone_id#,zoneid)}">{zn.getZoneName()}</option>
        </select>
      </td>
    </tr>

  </table>

</form>

<widget template="shipping/charges_form.tpl" />
