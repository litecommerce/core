{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Product option group management template
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.0
 *}

{* TODO: decompose *}

<h2>{t(getHeadLabel())}</h2>

<form action="admin.php" method="post" name="update_option_group_form" class="options-group-modify">
  <input type="hidden" name="target" value="product" />
  <input type="hidden" name="action" value="update_option_group" />
  <input type="hidden" name="page" value="product_options" />
  <input type="hidden" name="language" value="{language}" />
  <input type="hidden" name="id" value="{getProductId()}" />
  <input type="hidden" name="groupId" value="{getGroupId()}" />

  <ul class="form">

    <li class="required">
      <label for="data_name">{t(#Name#)}</label>
      <input type="text" id="data_name" name="data[name]" value="{translation.getName()}" class="field-required" />
    </li>

    <li>
      <label for="data_fullname">{t(#Text#)}</label>
      <input type="text" id="data_fullname" name="data[fullname]" value="{translation.getFullname()}" />
    </li>

    <li>
      <label for="data_type">{t(#Type#)}</label>
      <select name="data[type]" id="data_type">
        <option FOREACH="getOptionGroupTypes(),type,name" value="{type}" selected="{isSelected(group.getType(),type)}">{name}</option>
      </select>
    </li>

    <li>
      <label for="data_view_type">{t(#Display type#)}</label>
      <select name="data[view_type]" id="data_view_type">
        <option FOREACH="getOptionGroupViewTypes(),type,name" value="{type}" selected="{isSelected(group.getViewType(),type)}">{name}</option>
      </select>
    </li>

    <li>
      <label for="data_orderby">{t(#Position#)}</label>
      <input type="text" id="data_orderby" name="data[orderby]" value="{group.getOrderby()}" class="orderby field-integer" />
    </li>

    <li>
      <label for="data_enabled">{t(#Enabled#)}</label>
      <input type="checkbox" id="data_enabled" name="data[enabled]" value="1" checked="{group.getEnabled()}" />
    </li>

  </ul>

  <div id="options_list">
    <h3>{t(#Group options#)}</h3>

    <table cellspacing="1" class="data-table">

      <tr>
        <th><input type="checkbox" class="column-selector" /></th>
        <th class="extender">{t(#Name#)}</th>
        <th FOREACH="getModifiersNames(),m" colspan="2">{t(m)}</th>
        <th>{t(#Pos.#)}</th>
        <th>{t(#Enabled#)}</th>
      </tr>

      <tr FOREACH="getOptions(),i,option" class="{getRowClass(i,#highlight#)}">

        <td class="center"><input type="checkbox" name="mark[]" value="{option.getOptionId()}" /></td>
        <td><input type="text" name="options[{option.getOptionId()}][name]" value="{getOptionTranslation(option,#name#)}" class="extender field-required" /></td>

        {foreach:getOptionModifiers(option),key,modifier}
          <td><input type="text" name="options[{option.getOptionId()}][modifiers][{key}][modifier]" value="{modifier.getModifier()}" class="price field-float" /></td>
          <td>
            <select name="options[{option.getOptionId()}][modifiers][{key}][modifier_type]">
              <option FOREACH="getOptionSurchargeModifierTypes(),type,name" value="{type}" selected="{isSelected(modifier.getModifierType(),type)}">{t(name)}</option>
            </select>
          </td>
        {end:}

        <td><input type="text" name="options[{option.getOptionId()}][orderby]" value="{option.getOrderby()}" class="orderby field-integer" /></td>
        <td class="center"><input type="checkbox" name="options[{option.getOptionId()}][enabled]" value="1" checked="{option.getEnabled()}" /></td>

      </tr>

      <tr>
        <td class="center">&nbsp;</td>
        <td><input type="text" name="newOption[name]" value="" class="extender" /></td>

        {foreach:getOptionModifiers(),key,modifier}
          <td><input type="text" name="newOption[modifiers][{key}][modifier]" value="{modifier.getModifier()}" class="price field-float" /></td>
          <td>
            <select name="newOption[modifiers][{key}][modifier_type]">
              <option FOREACH="getOptionSurchargeModifierTypes(),type,name" value="{type}" selected="{isSelected(modifier.getModifierType(),type)}">{t(name)}</option>
            </select>
          </td>
        {end:}

        <td><input type="text" name="newOption[orderby]" value="" class="orderby field-integer" /></td>
        <td class="center"><input type="checkbox" name="newOption[enabled]" value="1" /></td>

      </tr>

    </table>

  </div>

  <div class="buttons">
    <widget IF="!hasOptions()" class="\XLite\View\Button\Submit" label="Add new option" />
    <widget IF="hasOptions()" class="\XLite\View\Button\Submit" label="Add/update options" />
    <widget module="CDev\ProductOptions" IF="hasOptions()" class="\XLite\Module\CDev\ProductOptions\View\Button\DeleteSelectedOptions" />
  </div>

</form>

<script type="text/javascript">
//<![CDATA[
var lcViewTypeOptions = {};
{getJSViewTypeOptions():h}
//]]>
</script>
