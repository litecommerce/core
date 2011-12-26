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

<div class="right-panel">
  <widget class="\XLite\View\EditorLanguageSelector" />
</div>

<script type="text/javascript">
//<![CDATA[
function onDeleteButton(method_id)
{
  formName = 'delete_method';
  document.forms[formName].elements['method_id'].value = method_id;
  document.forms[formName].submit();
}
//]]>
</script>

{t(#Use this section to define your store's shipping methods.#)}

<hr />

<form action="admin.php" name="delete_method" method="post">

  <input type="hidden" name="target" value="shipping_methods" />
  <input type="hidden" name="action" value="delete" />
  <input type="hidden" name="method_id" value="" />

</form>

{foreach:getShippingProcessors(),processor}
<form action="admin.php" name="shipping_method_{processor.getProcessorId()}" method="post">

  <input type="hidden" name="target" value="shipping_methods" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="method_id" value="" />

  <table cellpadding="0" cellspacing="0" width="600">

    <tr>
      <td><br />&nbsp;</td>
    </tr>

    <tr class="dialog-box">
      <td class="admin-head" colspan=5>{processor.getProcessorName()}</td>
    </tr>

    <tr IF="processor.getProcessorId()=#ups#">
      <td align="right">&nbsp;<widget module="CDev\UPSOnlineTools" template="modules/CDev/UPSOnlineTools/settings_link.tpl" /></td>
    </tr>

    <tr>
      <td>

        <table class="data-table">

          <tr>
            <th style="width:90%;">{t(#Shipping method#)}</th>
            <th>{t(#Pos.#)}</th>
            <th>{t(#Assigned classes#)}</th>
            <th class="center">
              <label for="enable_method_{processor.getProcessorId()}">
                <input class="column-selector" id="enable_method_{processor.getProcessorId()}" type="checkbox" />
                {t(#Active#)}
              </label>
            </th>
            <th valign="top">&nbsp;</th>
          </tr>

          <tr FOREACH="getProcessorMethods(processor),shipping_idx,method" class="{getRowClass(shipping_idx,#dialog-box#,#highlight#)}">

            <td>
              <input type="text" name="methods[{method.getMethodId()}][name]" size="50" value="{method.getName()}" IF="processor.isMethodNamesAdjustable()" />
              <span IF="!processor.isMethodNamesAdjustable()">{method.getName():h}</span>
            </td>

            <td><input type="text" name="methods[{method.getMethodId()}][position]" size="4" value="{method.getPosition()}" /></td>

            <td>
            <widget class="\XLite\View\FormField\Select\Classes" fieldName="{getNamePostedData(method.getMethodId(),#class_ids#,##)}" fieldOnly=true value="{method.getClasses()}" />
            </td>

            <td align="center">
              <input type="checkbox" name="methods[{method.getMethodId()}][enabled]" checked="{method.getEnabled()}" />
            </td>

            <td>
              <widget IF="processor.isMethodDeleteEnabled()" class="\XLite\View\Button\Regular" name="delete" label="Delete" jsCode="onDeleteButton('{method.getMethodId()}');" />
            </td>
          </tr>

        </table>

      </td>
    </tr>

    <tr>
      <td colspan="4">
        <br />
        <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" style="main-button" />
      </td>
    </tr>

  </table>

</form>

{end:}

<form action="admin.php" method="post">

  <input type="hidden" name="target" value="shipping_methods" />
  <input type="hidden" name="action" value="add" />

  <table cellpadding="0" cellspacing="0">

    <tr>
      <td>&nbsp;</td>
    </tr>

    <tr class="dialog-box">
      <td class="admin-title">{t(#Add shipping method#)}</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
    </tr>

    <tr>
      <td>

        <table class="data-table">

          <tr>
            <th>{t(#Shipping method#)}</th>
            <th>{t(#Pos.#)}</th>
          </tr>

          <tr class="dialog-box">
            <td>
              <input type="text" name="name" size="50" value="{name}" />
            </td>
            <td>
              <input type="text" name="position" size="4" value="{position}" />
            </td>
          </tr>

        </table>

      </td>
    </tr>

    <tr>
      <td colspan="5"><br /><widget class="\XLite\View\Button\Submit" label="{t(#Add#)}" /></td>
    </tr>

    <tr IF="!moduleArrayPointer=moduleArraySize">
      <td colspan="5"><br /><hr style="background-color: #E5EBEF; height: 2px; border: 0" /><br /></td>
    </tr>

  </table>

</form>
