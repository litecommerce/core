{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Payment methods
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

 <form action="admin.php" method="post" name="payment_methods">
   <input type="hidden" name="target" value="payment_methods" />
   <input type="hidden" name="action" value="update" />

   <table cellspacing="1" class="data-table payment-methods">

     <tr>
       <th>{t(#Name#)}</th>
       <th class="extender">{t(#Special instructions#)}</th>
       <th>{t(#Pos.#)}</th>
       <th>{t(#Enabled#)}</th>
       <th>&nbsp;</th>
     </tr>

     <tr FOREACH="getPaymentMethods(),i,method" class="{getRowClass(i,##,#highlight#)}">
       <td><input type="text" name="data[{method.getMethodId()}][name]" value="{getMethodName(method)}" class="field-required" size="30" /></td>
       <td><textarea cols="100" rows="6" name="data[{method.getMethodId()}][description]">{getMethodDescription(method)}</textarea></td>
       <td><input type="text" name="data[{method.getMethodId()}][orderby]" value="{method.orderby}" class="field-integer" size="5" /></td>
       <td><input type="checkbox" name="data[{method.getMethodId()}][enabled]" value="1" checked="{isMethodEnabled(method)}" /></td>
       <td><a IF="isMethodConfigurable(method)" href="{buildURL(#payment_method#,##,_ARRAY_(#method_id#^method.getMethodId()))}">{t(#Configure#)}</a></td>
     </tr>

   </table>

   <div class="buttons">
     <widget class="\XLite\View\Button\Submit" label="Update" />
   </div>

 </form>
