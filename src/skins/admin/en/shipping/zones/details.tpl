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
<!--

var msg_err_zone_rename='{t(#Zone name cannot be empty, please specify it#)}';

function onZoneSubmit()
{
  if (document.forms['zoneform'].elements['zone_name'].value == '') {
    alert(msg_err_zone_rename);
    return false;

  } else {
    saveSelects(new Array('zone_countries','zone_states'));
    document.forms['zoneform'].submit();
  }
}

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

-->
</script>

{t(#Use this section to define shipping zones.#)}

<span id="notes_url" style="display:"><a href="javascript:ShowNotes();" class="navigation-path" onclick="this.blur()"><b>{t(#How to define shipping zones#)} &gt;&gt;&gt;</b></a></span>

<span id="notes_body" style="display: none"><br /><br />
{t(#Select a country or a state from a list, specify the zone#)}
</span>

<hr />

<br /><br />

<form action="admin.php" method="post" name="zoneform">

  <input type="hidden" name="target" value="shipping_zones" />
  <input type="hidden" name="action" value="update" IF="!mode=#add#" />
  <input type="hidden" name="action" value="create" IF="mode=#add#" />
  <input type="hidden" name="zoneid" value="{zone.getZoneId()}" />

  <table cellpadding="3" cellspacing="1" width="700">

    <tr>
      <td colspan="3">
        {t(#Zone name#)}:
        <input type="text" size="50" name="zone_name" value="{zone.getZoneName()}" />
        &nbsp;&nbsp;
        <widget class="\XLite\View\Button\Regular" IF="!mode=#add#" label="Update" jsCode="onZoneSubmit();" />
        <widget class="\XLite\View\Button\Regular" IF="mode=#add#" label="Create" jsCode="onZoneSubmit();" />
        <br /><br />
      </td>
    </tr>

    <tbody IF="zone.getIsDefault()=0">

    {* Countries *}

	  <tr class="dialog-box">
	  	<td class="admin-head" colspan="3"><br />{t(#Countries#)}<hr /></td>
	  </tr>

    <tr>
      <td style="width:45%;" align="center">{t(#Set#)}</td>
      <td style="width:10%;">&nbsp;</td>
      <td style="width:45%;" align="center">{t(#Unset#)}</td>
    </tr>

    <tr>
      <td>
        <input type="hidden" id="zone_countries_store" name="zone_countries_store" value="" />
        <select id="zone_countries" multiple="multiple" size="15" style="width: 100%;">
          <option FOREACH="zone.getZoneCountries(),cn" value="{cn.getCode()}">{cn.getCountry()}</option>
          <option value="">&nbsp;</option>
        </select>

        <script type="text/javascript">
        //<![CDATA[
        normalizeSelect('zone_countries');
        //]]>
        </script>

      </td>
      <td align="center">
        <widget class="\XLite\View\Button\Regular" label="&lt;&lt;" jsCode="moveSelect(document.getElementById('zone_countries'), document.getElementById('rest_countries'), 'R');" />
        <br /><br />
        <widget class="\XLite\View\Button\Regular" label="&gt;&gt;" jsCode="moveSelect(document.getElementById('zone_countries'), document.getElementById('rest_countries'), 'L');" />
      </td>
      <td>
        <select id="rest_countries" multiple="multiple" size="15" style="width: 100%;">
          <option FOREACH="zone.getZoneCountries(1),cn" value="{cn.getCode()}">{cn.getCountry()}</option>
        </select>
      </td>
    </tr>

    {* States *}

	  <tr class="dialog-box">
	  	<td class="admin-head" colspan="3"><br />{t(#States#)}<hr /></td>
	  </tr>

    <tr>
      <td style="width:45%;" align="center">{t(#Set#)}</td>
      <td style="width:10%;">&nbsp;</td>
      <td style="width:45%;" align="center">{t(#Unset#)}</td>
    </tr>

    <tr>
      <td>
        <input type="hidden" id="zone_states_store" name="zone_states_store" value="" />
        <select id="zone_states" multiple="multiple" size="15" style="width: 100%;">
          <option FOREACH="zone.getZoneStates(),st" value="{st.country.getCode()}_{st.getCode()}">{st.country.getCountry()}: {st.getState()}</option>
          <option value="">&nbsp;</option>
        </select>

        <script type="text/javascript">
        //<![CDATA[
        normalizeSelect('zone_states');
        //]]>
        </script>

      </td>
      <td align="center">
        <widget class="\XLite\View\Button\Regular" label="&lt;&lt;" jsCode="moveSelect(document.getElementById('zone_states'), document.getElementById('rest_states'), 'R');" />
        <br /><br />
        <widget class="\XLite\View\Button\Regular" label="&gt;&gt;" jsCode="moveSelect(document.getElementById('zone_states'), document.getElementById('rest_states'), 'L');" />
      </td>
      <td>
        <select id="rest_states" multiple="multiple" size="15" style="width: 100%;">
          <option FOREACH="zone.getZoneStates(1),st" value="{st.country.getCode()}_{st.getCode()}">{st.country.getCountry()}: {st.getState()}</option>
        </select>
      </td>
    </tr>

    {* City masks *}
    {* TODO - disabled till design review 
	  <tr class="dialog-box">
	  	<td class="admin-head" colspan="3">{t(#City masks#)}<hr /></td>
	  </tr>

    <tr>
      <td style="width:45%;" align="center">{t(#Set#)}</td>
      <td style="width:10%;">&nbsp;</td>
      <td style="width:45%;">{t(#Examples#)}:</td>
    </tr>

    <tr>
      <td>
<textarea cols="40" rows="3" style="width: 100%;" name="zone_cities">
{foreach:zone.getZoneCities(),ct}
{ct}

{end:}
</textarea>
      </td>
      <td align="center">&nbsp;</td>
      <td>{t(#Examples#)}</td>
    </tr>
    *}

    {* Zip code masks *}

	  <tr class="dialog-box">
	  	<td class="admin-head" colspan="3">{t(#Zip/postal code masks#)}<hr /></td>
	  </tr>

    <tr>
      <td style="width:45%;" align="center">{t(#Set#)}</td>
      <td style="width:10%;">&nbsp;</td>
      <td style="width:45%;">{t(#Examples#)}:</td>
    </tr>

    <tr>
      <td>
<textarea cols="40" rows="3" style="width: 100%;" name="zone_zipcodes">
{foreach:zone.getZoneZipCodes(),zp}
{zp}

{end:}
</textarea>
      </td>
      <td align="center">&nbsp;</td>
      <td>2204%<br />38245<br />23%</td>
    </tr>


    {* Address masks *}
    {* TODO - disabled until design review

	  <tr class="dialog-box">
	  	<td class="admin-head" colspan="3">{t(#Address masks#)}<hr /></td>
	  </tr>

    <tr>
      <td style="width:45%;" align="center">{t(#Set#)}</td>
      <td style="width:10%;">&nbsp;</td>
      <td style="width:45%;">{t(#Examples#)}:</td>
    </tr>

    <tr>
      <td>
<textarea cols="40" rows="3" style="width: 100%;" name="zone_addresses">
{foreach:zone.getZoneAddresses(),addr}
{addr}

{end:}
</textarea>
      </td>
      <td align="center">&nbsp;</td>
      <td>{t(#Examples#)}</td>
    </tr>
    *}

    <tr>
      <td colspan="3"><br /><br /><hr />
        <widget class="\XLite\View\Button\Regular" jsCode="onZoneSubmit();" label="Save zone details" />
      </td>
    </tr>

    </tbody>

    <tbody IF="zone.getIsDefault()=1">

      <tr>
        <td colspan="3"><br />{t(#This is a default zone which covers all addresses. It's impossible to edit this zone's countries, states etc#)}</td>
      </tr>

    </tbody>

  </table>

</form>
