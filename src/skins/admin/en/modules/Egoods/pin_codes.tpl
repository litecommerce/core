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
<p align=justify>PIN codes to be sold through the &quot;{product.name:h}&quot; product</p> 

<br>
<font class="AdminHead">PIN codes datasource</font>
<br><br>
<form name="pin_dsn" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="update_pin_src">
<table border="0" cellpadding="1" cellspacing="3">
<tr>
	<td><input type="radio" name="pin_src" value="N" checked></td>
	<td>No PIN codes</td>
</tr>	
<tr>
	<td><input type="radio" name="pin_src" value="D" checked="product.pinSettings.pin_type=#D#"></td>
	<td>Database</td>
</tr>	
<tr>
	<td><input type="radio" name="pin_src" value="E" checked="product.pinSettings.pin_type=#E#"></td>
	<td>External application</td>
</tr>
</table>
<br>
<span IF="product.pinSettings.pin_type=#D#">
<font class="AdminHead">Low amount notification</font>
<br><br>
<table border="0" cellpadding="1" cellspacing="3">
<tr>
	<td>Notify the store administrator when amount of available PIN codes in the database reaches the following minimum: </td>
	<td align="left"><input type="text" name="low_available_limit" value="{product.pinSettings.low_available_limit}" size="3"></td>
</tr>
</table>
</span>
<br>
<input type="submit" value=" Update ">
</form>

<!-- Database datasource {{{ -->
<span IF="product.pinSettings.pin_type=#D#">
<br>
<table border="0" cellpadding="1" cellspacing="3">
<form name="add_new_pin_code" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="add_pincode">
	<td colspan=2>
	<font class="AdminTitle">Add new PIN code</font>
	</td>	
	<tr class="TableHead">
	<td>PIN code</td>
	<td>Status</td>
	</tr>
	<tr>
	<td><input size="32" name="new_pin"><widget class="\XLite\Validator\RequiredValidator" field="new_pin"></td>
	<td>
		<select name="new_pin_enabled">
			<option value="1" selected>Enabled</option>
			<option value="0">Disabled</option>
		</select>
	</td>	
	</tr>
	<tr>
	<td colspan=2>
	<input type="submit" value= "  Add  ">
	</td>	
	</tr>
</form>
</table>
<p><b>Note: </b>You can bulk upload PIN codes from <a href="admin.php?target=import_catalog&page=import_pin_codes"><u>import PIN codes page</u></a>.</p>

<span IF="pinCodes">
<br>
<font class="AdminHead">Available PIN codes</font><br><br>
<table border="0">
<form action="admin.php" method="get">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<tr>
	<td>Status:</td>
	<td><select name="pin_enabled">
		<option value="">All</option>
		<option value="1" selected={pin_enabled=#1#}>Enabled</option>
		<option value="0" selected={pin_enabled=#0#}>Disabled</option>
	</select></td>
	<td><input type="submit" value="Apply" /></td>
</tr>
</form>
</table>
<widget class="\XLite\View\Pager" data="{pinCodes}" name="pager" itemsPerPage="{config.Egoods.pincodes_per_page}">
<table border="0" cellpadding="1" cellspacing="3">
<form name="edit_pins" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="delete_pin_codes">
<tr class="TableHead">
	<td>&nbsp;&nbsp;</td>
	<td>&nbsp; PIN code&nbsp;</td>
	<td>&nbsp; Status &nbsp;</td>
	<td>&nbsp; Utilization &nbsp;</td>
	<td>&nbsp; Order # &nbsp;</td>
</tr>
<tr FOREACH="pager.pageData,pin_code">
	<td><input type="checkbox" name="selected_pins[]" value="{pin_code.pin_id}"></td>
	<td>&nbsp;{pin_code.pin}&nbsp;</td>
	<td>
		&nbsp;
		<span IF="pin_code.enabled">Enabled</span>
		<span IF="!pin_code.enabled"><font color="red">Disabled</font></span>
	</td>
	<td>
		&nbsp;
		<span IF="pin_code.free">Available for sale</span>
		<span IF="!pin_code.free"><font color="red">Sold</font></span>
	</td>	
    <td>
        &nbsp;<span IF="pin_code.order_id"><a href="admin.php?target=order&order_id={pin_code.order_id}"><u>{pin_code.order_id}</u></a>&nbsp;&gt;&gt;</span>
    </td>
</tr>
<tr>
	<td colspan=5>
    <table cellspacing="0" cellpadding="0" border="0" width=100%>
    <tr>
    	<td>
        <input type="button" value=" Enable " onclick="document.forms.edit_pins.action.value='enable_pin_codes'; document.forms.edit_pins.submit();">
        <input type="button" value=" Disable " onclick="document.forms.edit_pins.action.value='disable_pin_codes'; document.forms.edit_pins.submit();">
        <input type="button" value=" Free " onclick="document.forms.edit_pins.action.value='free_pin_codes'; document.forms.edit_pins.submit();">
    	</td>
    	<td align=right>
    	<input type="submit" value=" Delete ">
    	</td>
    </tr>	
    </table>
	</td>
</tr>
</form>
</table>
</span>

</span>
<!-- }}} -->

<!-- External script datasource {{{ -->
<span IF="product.pinSettings.pin_type=#E#">
<br><br>
<b>You can use following variables in the Pin codes generator command line:</b>
<br><br>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>%d&nbsp;</td>
	<td> - Current order ID</td>
</tr>	
<tr>
	<td>%m&nbsp;</td>
	<td> - Customer e-mail</td>
</tr>	
<tr>
	<td>%f&nbsp;</td>
	<td> - Customer billing first name</td>
</tr>	
<tr>
	<td>%l&nbsp;</td>
	<td> - Customer billing last name</td>
</tr>	
<tr>
	<td>%p&nbsp;</td>
	<td> - This product id</td>
</tr>	
<tr>
	<td>%n&nbsp;</td>
	<td> - Name of this product</td>
</tr>	
</table>

<form name="pin_gen_cmd_line" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val}"/>
<input type="hidden" name="target" value="product">
<input type="hidden" name="action" value="update_pin_cmd_line">
<br><br>
<font class="AdminHead">PIN codes external generator command line: </font>
<input name="gen_cmd_line" size="64" value="{product.pinSettings.gen_cmd_line}">
<br><br>
<input type="submit" value=" Update ">
</form>
</span>
<!-- }}} -->
