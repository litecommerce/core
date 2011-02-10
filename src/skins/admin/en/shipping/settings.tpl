{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping settings management page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<form action="admin.php" name="shipping_settings" method="post">
  
  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="update" />

  <div IF="config.Shipping.shipping_enabled=#Y#">
    {t(#Shipping is enabled#)} <br /><br />
    <input type="hidden" name="shipping_enabled" value="N" />
    <widget class="\XLite\View\Button\Submit" label="Disable shipping" />
    <br /><br /><hr />
  </div>

  <div IF="!config.Shipping.shipping_enabled=#Y#">
    {t(#Shipping is disabled#)} <br /><br />
    <input type="hidden" name="shipping_enabled" value="Y" />
    <widget class="\XLite\View\Button\Submit" label="Enable shipping" />
  </div>

</form>

<form action="admin.php" name="shipping_settings" method="post">
  
  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="update" />

  <table cellSpacing="2" cellpadding="2" width="700" IF="config.Shipping.shipping_enabled=#Y#">

    <tbody FOREACH="getOptions(),option">

      <tr IF="option.type=#separator#">
        <td colspan="2" class="admin-title">{option.option_name:h}</td>
      </tr>

      <tr IF="!option.type=#separator#">
        <td align="left" width="50%">{option.option_name:h}: </td>
        <td width="50%">

        {if:option.type=#checkbox#}
          <input id="{option.name}" type="checkbox" name="{option.name}" checked="{option.value=#Y#}" />
        {end:}

        {if:option.type=#text#}
          <input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="30" />
        {end:}

        {if:option.type=#country#"}
          <widget class="\XLite\View\CountrySelect" field="{option.name}" country="{option.value}" fieldId="{option.name}_select" />
        {end:}

        {if:option.type=#state#"}
          <widget class="\XLite\View\StateSelect" field="{option.name}" state="{getStateById(option.value)}" fieldId="{option.name}_select" isLinked=1 />
        {end:}

        </td>
      </tr>
    
    </tbody>

    <tr>
      <td align="right"><br /><widget class="\XLite\View\Button\Submit" label="Submit" /></td>
    <tr>

  </table>

</form>


