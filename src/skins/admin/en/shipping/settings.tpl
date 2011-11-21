{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping settings management page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

<form action="admin.php" name="shipping_settings" method="post">

  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="update" />

  <table cellSpacing="2" cellpadding="2" width="700">

    <tbody FOREACH="getOptions(),option">

      <tr IF="option.type=#separator#">
        <td colspan="2" class="admin-title">{option.option_name:h}</td>
      </tr>

      <tr IF="!option.type=#separator#">
        <td align="left" width="50%">{option.option_name:h}: </td>
        <td style="width:50%;">

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
      <td align="right"><br /><widget class="\XLite\View\Button\Submit" label="{t(#Submit#)}" /></td>
    <tr>

  </table>

</form>
