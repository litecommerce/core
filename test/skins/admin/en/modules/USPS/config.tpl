{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * USPS configuration widget
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<script type="text/javascript">
<!--
function processClearCache()
{
  document.usps_config.action.value = 'clear_cache';
  document.usps_config.submit();
}
-->
</script>

<span class="SuccessMessage" IF="updated">USPS settings were successfully saved</span>
<p>
<form action="admin.php" method="POST" name="usps_config">
<input type="hidden" name="target" value="usps">
<input type="hidden" name="action" value="update">

<table border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td colspan=2><h3>Account</h3> You should obtain this account from USPS at <a href="http://www.usps.com/webtools/">http://www.usps.com/webtools/</a></td>
  </tr>
  <tr>
    <td width="30%"><b>User ID:</b></td>
    <td><input type="text" name="userid" size="32" value="{settings.userid:r}"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td width="30%"><b>Server name:</b></td>
    <td><input type="text" name="server" size="64" value="{settings.server:r}"></td>
  </tr>
  <tr>
    <td width="30%"><b>Use HTTPS:</b></td>
    <td>
      <input type="hidden" name="https" value="" />
      <input type="checkbox" name="https" value="Y" checked="{settings.https=#Y#}">
    </td>
  </tr>

  <tr>
    <td colspan=2><hr><br></td>
  </tr>

  <tr>
    <td colspan=2><h3>International USPS</h3></td>
  </tr>
  <tr>
    <td width="30%" valign="top"><b>Type of Mail:</b></td>
    <td>
      <select name="mailtype">
        <option FOREACH="mailtypes,k,v" selected="{isSelected(settings.mailtype,k)}">{v:h}</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Value of content:</b></td>
    <td><input type="text" name="value_of_content" size="10" value="{settings.value_of_content:r}"></td>
  </tr>
  <tr>
    <td colspan=2><hr><br></td>
  </tr>
  <tr>
    <td colspan=2><h3>Domestic USPS</h3></td>
  </tr>
  <tr>
    <td width="30%" valign="top"><b>Container (Express Mail):</b></td>
    <td>
      <select name=container_express>
        <option FOREACH="containers_express,k,v" value="{k:r}" selected="{isSelected(settings.container_express,k)}"> {v:h}</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Container (Priority Mail):</b></td>
    <td>
      <select name=container_priority>
        <option FOREACH="containers_priority,k,v" value="{k:r}" selected="{isSelected(settings.container_priority,k)}"> {v:h}</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Package Size (length + girth, inches):</b></td>
    <td>
  <table border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><input type="radio" name="package_size" value="Regular" checked="{isSelected(settings.package_size,#Regular#)}">Regular (0 &lt; size &lt;= 84)</td>
    </tr>
    <tr>
      <td><input type="radio" name="package_size" value="Large" checked="{isSelected(settings.package_size,#Large#)}">Large (84 &lt; size &lt;= 108)</td>
    </tr>
    <tr>
      <td><input type="radio" name="package_size" value="Oversize" checked="{isSelected(settings.package_size,#Oversize#)}">Oversize (108 &lt; size &lt;= 130)</td>
    </tr>
  </table>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Machinable:</b></td>
    <td>
      <select name=machinable>
        <option selected="{isSelected(settings.machinable,#True#)}" value="True">Yes</option>
        <option selected="{isSelected(settings.machinable,#False#)}" value="False">No</option>
      </select>
<br>
Required for Parcel Post and First Class only. The size, content, and weight of a package can all determine whether a Parcel Post package is machinable or nonmachinable. Based on weight, the calculator will automatically apply the nonmachinable surcharge on Parcel Post packages weighing less than 6 ounces or over 35 pounds.
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Length x Width x Height:</b></td>
    <td>
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td align="left"><input type="text" name="dim_lenght" size="10" value="{settings.dim_lenght:r}"></td>
      <td align="left">&nbsp;x&nbsp;</td>
      <td align="left"><input type="text" name="dim_width" size="10" value="{settings.dim_width:r}"></td>
      <td align="left">&nbsp;x&nbsp;</td>
      <td align="left"><input type="text" name="dim_height" size="10" value="{settings.dim_height:r}"></td>
    </tr>
    <tr>
      <td colspan="5">Required for Large Priority Mail pieces, inches</td>
    </tr>
  </table>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>Girth:</b></td>
    <td><input type="text" name="dim_girth" size="10" value="{settings.dim_girth:r}"><br>Required for Priority Mail Non Rectangular (Large), inches</td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td width="30%" valign="top"><b>First Class Mail Type:</b></td>
    <td>
      <select name="fcmailtype">
        <option FOREACH="fcmailtypes,k,v" value="{k:r}" selected="{isSelected(settings.fcmailtype,k)}">{v:h}</option>
      </select>
    </td>
  </tr>

  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>

  <tr>
    <td colspan=2>
  <table border="0" cellpadding="5" cellspacing="0" WIDTH="100%">
    <tr>
      <td align="left"><input type=submit value="Apply" class="DialogMainButton"></td>
      <td align="right"><input type=button value="Clear cache" OnClick="processClearCache();"></td>
    </tr>
  </table>
    </td>
  </tr>
</table>
</form>
