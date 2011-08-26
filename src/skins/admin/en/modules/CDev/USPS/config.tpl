{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * USPS settings page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<div class="admin-title">U.S.P.S. settings</div>

<br />

<form action="admin.php" method="post">

  <input type="hidden" name="target" value="usps" />
  <input type="hidden" name="action" value="update" />

  <table cellpadding="2" cellspacing="1">

    <tr>
      <td colspan="2"><br /><b>Authentication options</b></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;User ID:</td>
      <td><input type="text" name="userid" value="{config.CDev.USPS.userid:r}" size="15" /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;U.S.P.S. API server:</td>
      <td>
        <table cellspacing="1" cellpadding="0" border="0">
          <tr>
            <td>http://</td>
            <td><input type="text" name="server_name" value="{config.CDev.USPS.server_name:r}" size="30" /></td>
            <td>/</td>
            <td>
              <select name="server_path">
              {foreach:getServerPathOptions(),opkey,opval}
                <option value="{opkey}" selected="{isSelected(config.CDev.USPS.server_path,opkey)}">{opval}</option>
              {end:}
              </select>
            </td>
            <td>?API=...</td>
          </tr>
        </table>
      </td>
    </tr>

    <tr>
      <td colspan="2"><br /><b>Common options</b></td>
    </tr>

    <tr>
      <td valign="middle">&nbsp;&nbsp;&nbsp;&nbsp;Package dimensions (inches):</td>
      <td>
        <table cellspacing="1" cellpadding="0" border="0">
          <tbody>
            <tr>
              <td>Length</td>
              <td></td>
              <td>Width</td>
              <td></td>
              <td>Height</td>
            </tr>
            <tr>
              <td><input type="text" size="6" name="length" value="{config.CDev.USPS.length:r}" /></td>
              <td>&nbsp;x&nbsp;</td>
              <td><input type="text" size="6" name="width" value="{config.CDev.USPS.width:r}" /></td>
              <td>&nbsp;x&nbsp;</td>
              <td><input type="text" size="6" name="height" value="{config.CDev.USPS.height:r}" /></td>
            </tr>
          </tbody>
        </table>
      </td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Girth (inches):</td>
      <td><input type="text" name="girth" value="{config.CDev.USPS.girth:r}" size="8" /></td>
    </tr>

    <tr>
      <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;(required for large size and container is non-rectangular or variable)<br /><br /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Currency rate:</td>
      <td><input type="text" name="currency_rate" value="{config.CDev.USPS.currency_rate:r}" size="8" /></td>
    </tr>

    <tr>
      <td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;(specify rate X, where 1 USD = X in shop currency)</td>
    </tr>

    <tr>
      <td colspan="2"><br /><b>Domestic U.S.P.S.</b></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Container:</td>
      <td>
        <select name="container">
        {foreach:getContainerOptions(),opkey,opval}          
          <option value="{opkey}" selected="{isSelected(config.CDev.USPS.container,opkey)}">{opval}</option>
        {end:}
        </select>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Package Size (length + girth, inches):</td>
      <td>
        <select name="package_size">
        {foreach:getPackageSizeOptions(),opkey,opval}          
          <option value="{opkey}" selected="{isSelected(config.CDev.USPS.package_size,opkey)}">{opval}</option>
        {end:}
        </select>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Machinable:</td>
      <td><input type="checkbox" name="machinable" value="Y"{if:config.CDev.USPS.machinable=#Y#} checked="checked"{end:} /></td>
    </tr>

    <tr>
      <td colspan="2"><br /><b>International U.S.P.S.</b></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Mail type:</td>
      <td>
        <select name="mail_type">
        {foreach:getMailTypeOptions(),opkey,opval}          
          <option value="{opkey}" selected="{isSelected(config.CDev.USPS.mail_type,opkey)}">{opval}</option>
        {end:}
        </select>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Container:</td>
      <td>
        <select name="container_intl">
        {foreach:getContainerIntlOptions(),opkey,opval}          
          <option value="{opkey}" selected="{isSelected(config.CDev.USPS.container_intl,opkey)}">{opval}</option>
        {end:}
        </select>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Get commercial base postage:</td>
      <td><input type="checkbox" name="commercial" value="Y"{if:config.CDev.USPS.commercial=#Y#} checked="checked"{end:} /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;Global Express Guarantee (GXG):</td>
      <td><input type="checkbox" name="gxg" value="Y"{if:config.CDev.USPS.gxg=#Y#} checked="checked"{end:} /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;GXG destination is a post office box:</td>
      <td><input type="checkbox" name="gxg_pobox" value="Y"{if:config.CDev.USPS.gxg_pobox=#Y#} checked="checked"{end:} /></td>
    </tr>

    <tr>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;GXG package contains a gift:</td>
      <td><input type="checkbox" name="gxg_gift" value="Y"{if:config.CDev.USPS.gxg_gift=#Y#} checked="checked"{end:} /></td>
    </tr>

    <tr>
      <td colspan="2"><br /><widget class="\XLite\View\Button\Submit" label="Save" /></TD>
    </tr>

  </table>

</form>
