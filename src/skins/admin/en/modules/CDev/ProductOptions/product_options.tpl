{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product options management template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<div class="right-panel">
  <widget class="\XLite\View\EditorLanguageSelector" />
</div>

<form IF="getOptions()" action="admin.php" method="POST" name="update_option_groups_form">
  <input type="hidden" name="target" value="product">
  <input type="hidden" name="action" value="update_option_groups">
  <input type="hidden" name="page" value="product_options">
  <input type="hidden" name="language" value="{language}">
  <input type="hidden" name="product_id" value="{getProductId()}">

  <table cellspacing="1" class="data-table">

    <tr>
      <th><input type="checkbox" class="column-selector" /></th>
      <th class="extender">Option group</th>
      <th>Pos.</th>
      <th>Enabled</th>
    </tr>

    <tr FOREACH="getOptions(),i,group" class="{getRowClass(i,#highlight#)}">
      <td class="center"><input type="checkbox" name="mark[]" value="{group.getGroupId()}" /></td>
      <td><a href="{getOptionGroupLink(group)}">{group.getName()}</a></td>
      <td><input type="input" name="data[{group.getGroupId()}][orderby]" value="{group.getOrderby()}" class="orderby" /></td>
      <td class="center"><input type="checkbox" name="data[{group.getGroupId()}][enabled]" value="1" checked="{group.getEnabled()}" /></td>
    </tr>

  </table>

  <div class="buttons">
    <widget class="\XLite\View\Button\Submit" label="Update" />
    <widget class="\XLite\Module\CDev\ProductOptions\View\Button\DeleteSelectedGroups" />
    <widget class="\XLite\Module\CDev\ProductOptions\View\Button\AddGroup" />
  </div>

</form>

<widget IF="!getOptions()" class="\XLite\Module\CDev\ProductOptions\View\Button\AddGroup" />

<widget class="\XLite\Module\CDev\ProductOptions\View\ModifyOptionGroup" />

<widget class="\XLite\Module\CDev\ProductOptions\View\ModifyExceptions" />

