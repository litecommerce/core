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
<tr>
	<td class=TableHead colspan=3>
		<table border=0 cellpadding=0>
		<tr>
            <td nowrap>Option class name:&nbsp;</td>
            <td>
            	<input type=text name="optdata[optclass]" size=12>
                <widget class="\XLite\Module\ProductOptions\Validator\RequiredValidator" field="optdata[optclass]" action="{action}">
                &nbsp;<span style="color: #606060">should be unique for easier stock management</span>
            </td>
		</tr>
		</table>
	</td>
</tr>
<tr>
    <td class="TableRow">Option values</td>
    <td class="TableRow">Option selection text</td>
    <td class="TableRow">Pos.</td>
</tr>
<tr>
    <td rowspan=3>
    	<textarea cols=34 rows=5 name="optdata[options]">{option.options:r}</textarea>
    </td>
    <td valign=top>
        <input type=text name="optdata[opttext]" size=34>
    </td>
    <td valign=top>
		<input type=text name="optdata[orderby]" value="{inc(productOption.orderby,#10#)}" size=3>
    </td>
</tr>
<tr>
	<td class="TableRow">
		<table border=0 cellpadding=0>
		<tr>
            <td>Option selector</td>
        	<td class="TableRow" id=TextTRHead style="display: none">&nbsp;&nbsp;&nbsp;Size (symbols)</td>
        	<td class="TableRow" id=TextareaTRHead style="display: none">&nbsp;&nbsp;&nbsp;Size (cols, rows)</td>
		</tr>
		</table>
	</td>
	<td>&nbsp;</td>
</tr>
<tr>
	<td>
		<table border=0 cellpadding=0>
		<tr>
            <td>
            <select name="optdata[opttype]" onChange="javascript: editSize(this.value);">
                <option value="Text">Text</option>
                <option value="Textarea">Text Area</option>
                <option value="SelectBox" selected>Select Box</option>
                <option value="Radio button">Radio Button</option>
            </select>
            </td>
        	<td id=TextTR style="display: none">
        		<input type=text name="optdata[cols]" size=3 value="{option.cols}">
        	</td>
            <td id=TextareaTR style="display: none">
                <input type=text name="optdata[rows]" size=3 value="{option.rows}">
            </td>
		</tr>
		</table>
	</td>
	<td>&nbsp;</td>
</tr>

<tr id="PO_Notes_URL" style="display:">
    <td colspan="4">
    <a href="javascript:ShowPONotes();" class="NavigationPath" onClick="this.blur()"><b>Notes &gt;&gt;&gt;</b></a>
    </td>
</tr>
<tr id="PO_Notes" style="display: none">
    <td colspan="4" width=500>
    <b>Notes:</b><br>
	a) Each option value must be listed on a separate line.<br>
	b) Choose 'Text' or 'Text Area' option selector and leave the 'Option values' field blank in order to allow your customers to type in their specific requests or comments.<br>
	c) You can apply price and weight modifiers to individual product options. Consult ProductOptions module reference manual for additional details.
    </td>
</tr>
<tr id="PO_Examples_URL" style="display:">
    <td colspan="4">
    <a href="javascript:ShowPOExamples();" class="NavigationPath" onClick="this.blur()"><b>Examples &gt;&gt;&gt;</b></a>
    </td>
</tr>
<tr id="PO_Examples" style="display: none">
    <td colspan="4">
    <b>Product options examples:</b><br>
    <table border="1">
    <tr>
        <td class=TableHead nowrap>Option class name&nbsp;&nbsp;</td>
        <td class=TableHead nowrap>Option selection text</td>
        <td class=TableHead nowrap>Pos.</td>
        <td class=TableHead width=200>Option values</td>
    </tr>
	<tr valign=top>
        <td>Name</td>
        <td nowrap>Choose the character&nbsp;&nbsp;</td>
        <td>10</td>
        <td>
            <i>
            Simba<br>
            Nala<br>
            Kiara<br>
            Kovu<br>
            </i>
        </td>
    </tr>
	<tr valign=top>
        <td>Size</td>
        <td nowrap>Choose the cub's size&nbsp;&nbsp;</td>
        <td>10</td>
        <td>
            <i>
            Jumbo=900%;w95<br>
            Large=+20;w200%<br>
            Regular<br>
            Small=-10;w-50%<br>
            Tiny=-70%;w-4<br>
            </i>
        </td>
    </tr>
    </table>
</tr>

<widget module="ProductOptions" template="modules/ProductOptions/option_form_js.tpl">
