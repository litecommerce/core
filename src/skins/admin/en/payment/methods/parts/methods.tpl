{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Language selector
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *
 * @ListChild (list="payment.methods.body", zone="admin", weight="200")
 *}
<form action="admin.php" method="post" name="payment_methods">
  <input type="hidden" name="target" value="payment_methods" />
  <input type="hidden" name="action" value="update" />

  <table cellspacing="2" class="data-table payment-methods">

    <tr>
      <th class="switch"><img src="images/spacer.gif" alt="" /></th>
      <th>{t(#Pos.#)}</th>
      <th>{t(#Name#)}</th>
      <th>{t(#Description#)}</th>
      <th>&nbsp;</th>
    </tr>

    <tr FOREACH="getPaymentMethods(),i,method" class="{getRowClass(i,##,#highlight#)}">
      <td class="switch"><input type="checkbox" name="data[{method.getMethodId()}][enabled]" value="1" checked="{isMethodEnabled(method)}" /></td>
      <td class="pos"><input type="text" name="data[{method.getMethodId()}][orderby]" value="{method.orderby}" class="field-integer" /></td>
      <td class="name">
        <input type="text" name="data[{method.getMethodId()}][name]" value="{getMethodName(method)}" class="field-required" />
        <div IF="getModuleName(method)">
          {if:isModuleConfigurable(method)}
            {t(#added by _X_#,_ARRAY_(#name#^getModuleName(method),#url#^getModuleURL(method))):h}
          {else:}
            {t(#added by X#,_ARRAY_(#name#^getModuleName(method)))}
          {end:}
        </div>
      </td>
      <td class="description">
        <textarea name="data[{method.getMethodId()}][description]">{getMethodDescription(method)}</textarea>
        <widget class="\XLite\View\Button\SwitchButton" first="makeSmallHeightPMT" second="makeLargeHeightPMT" />
      </td>
      <td>
        <a IF="isMethodConfigurable(method)" href="{buildURL(#payment_method#,##,_ARRAY_(#method_id#^method.getMethodId()))}">{t(#Settings#)}</a>
      </td>
    </tr>

  </table>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="{t(#Update#)}" />
  </div>

</form>
