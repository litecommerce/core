{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
{if:option.isCheckbox()}
{if:option.isName(#rp_show_buynow#)}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}" onChange="UpdateBuyNow(this)">
{else:}
<input id="{option.name}" type="checkbox" name="{option.name}" onClick="this.blur()" checked="{option.isChecked()}">
{end:}
{end:}
{if:option.isText()}
<input id="{option.name}" type="text" name="{option.name}" value="{option.value}" size=10>
{end:}
{if:option.isName(#rp_template#)}
<select name="{option.name}" onChange="UpdateSettings()">
    <option value="list" selected="{option.isSelected(#list#)}">List</option>
    <option value="grid" selected="{option.isSelected(#grid#)}">Grid</option>
    <option value="table" selected="{option.isSelected(#table#)}">Table</option>
</select>
{end:}
{if:option.isName(#rp_columns#)}
<select name="{option.name}">
    <option value="1" selected="{option.isSelected(#1#)}">1</option>
    <option value="2" selected="{option.isSelected(#2#)}">2</option>
    <option value="3" selected="{option.isSelected(#3#)}">3</option>
    <option value="4" selected="{option.isSelected(#4#)}">4</option>
    <option value="5" selected="{option.isSelected(#5#)}">5</option>
</select>
{end:}
{if:option.isName(#pab_template#)}
<select name="{option.name}" onChange="UpdateSettings()">
    <option value="list" selected="{option.isSelected(#list#)}">List</option>
    <option value="grid" selected="{option.isSelected(#grid#)}">Grid</option>
    <option value="table" selected="{option.isSelected(#table#)}">Table</option>
</select>
{end:}
{if:option.isName(#pab_columns#)}
<select name="{option.name}">
    <option value="1" selected="{option.isSelected(#1#)}">1</option>
    <option value="2" selected="{option.isSelected(#2#)}">2</option>
    <option value="3" selected="{option.isSelected(#3#)}">3</option>
    <option value="4" selected="{option.isSelected(#4#)}">4</option>
    <option value="5" selected="{option.isSelected(#5#)}">5</option>
</select>
{end:}
{if:option.isName(#customer_notifications_mode#)}
<select name="{option.name}">
    <option value="3" selected="{option.isSelected(#3#)}">All notifications</option>
    <option value="2" selected="{option.isSelected(#2#)}">In-stock notifications only</option>
    <option value="1" selected="{option.isSelected(#1#)}">Price change notifications only</option>
    <option value="0" selected="{option.isSelected(#0#)}">None</option>
</select>
{end:}
{if:option.isName(#new_arrivals_type#)}
<select name="{option.name}">
    <option value="menu" selected="{option.isSelected(#box#)}">a side box</option>
    <option value="dialog" selected="{option.isSelected(#dialog#)}">the main section</option>
</select>
{end:}
