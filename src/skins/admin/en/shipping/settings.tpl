{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Shipping settings management page template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<form action="admin.php" name="shipping_settings" method="post">

  <input type="hidden" name="target" value="{target}" />
  <input type="hidden" name="action" value="update" />

  <table cellSpacing="2" cellpadding="2" width="700">

    <tbody FOREACH="getOptions(),option">

      <tr class="data-row data-row-separator" IF="option.type=#separator#">
        <td colspan="2" class="admin-title">{option.option_name:h}</td>
      </tr>

      <tr class="data-row data-row-option" IF="!option.type=#separator#">

        <td align="left" width="50%">{option.option_name:h}: </td>

        <td style="width:50%;">

        <input IF="option.type=#checkbox#" id="{option.name}" type="checkbox" name="{option.name}" checked="{option.value=#Y#}" />
        <input IF="option.type=#text#" id="{option.name}" type="text" name="{option.name}" value="{option.value}" size="30" />
        <widget
          IF="option.type=#country#"
          class="\XLite\View\FormField\Select\Country"
          fieldOnly=true
          stateSelectorId="anonymous_state_select"
          stateInputId="anonymous_custom_state"
          fieldName="{option.name}"
          value="{option.value}"
          fieldId="{option.name}_select" />
        <widget
          IF="option.type=#state#"
          class="\XLite\View\FormField\Select\State"
          fieldOnly=true
          fieldName="{option.name}"
          hasSelectOne=false
          value="{getStateById(option.value)}"
          fieldId="{option.name}_select" />

        </td>
      </tr>

    </tbody>

    <tr>
      <td align="right"><br /><widget class="\XLite\View\Button\Submit" label="{t(#Submit#)}" /></td>
    <tr>

  </table>

</form>
