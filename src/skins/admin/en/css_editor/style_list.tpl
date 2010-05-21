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

Use this tool to customize the CSS styles used at your store.

<hr />

<input type="hidden" id="_css_items_count" value="{css_items_count}" />

<table border="0" width="100%" cellspacing="0" cellpadding="0">

  <tr valign="top">
    <td bgcolor="#B2B2B3">

      <table border="0" width="100%" cellspacing="1" cellpadding="3">

        <tr bgcolor="#DDDDDD">
          <td width="50%"><b>Tag/class Name</b></td>
          <td><b>Example</b></td>
          <td width="7%" align="center"><b>Edit</b></td>	
        </tr>

        <tr FOREACH="editor.items,item" bgcolor="#FFFFFF">
          <td>
            {css_class(item)}

            <table border="0" width="100%" cellspacing="0" cellpadding="1">

              <tr>
                <td>&nbsp;</td>
                <td><FONT color=#B2B2B3>{css_comment(item)}</FONT></td>
              </tr>

            </table>

          </td>
          <td style="{css_style(item)}" id="td_{item}">Sample text</td>
          <td align="center">
            <a href="admin.php?target=css_edit&mode=edit&style_id={item}"><img src="skins/admin/en/css_editor/images/style_edit.gif" border="1" alt="Edit Style" /></a>
          </td>
        </tr>

      </table>

    </td>
  </tr>

</table>

<script language="javascript">
var size = new Array();
var css_items_count = document.getElementById("_css_items_count").value;

<!--
for (i = 0; i < css_items_count; i ++) {
	initFontSize(i);
	updateStyle(i);
}

function initFontSize(elementId)
{
	var element = document.getElementById('td_' + elementId);
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}
	size[elementId] = element.currentStyle.fontSize;
}

function updateStyle(elementId)
{
	var outputStyle = document.getElementById('output_style_' + elementId);
	var css_text = document.getElementById("td_" + elementId).style.cssText;
	css_text = css_text.replace(/\"(\w+)\"/g, "$1");
	css_text = css_text.replace(/,{2,}/g, ",");
	css_text = css_text.replace(/\w+:\s+;/g, "");
	outputStyle.value = css_text;
}

function submitForm(elementId)
{
	var current_style = document.getElementById('current_style');
	current_style.value = elementId;
	document.style_form.submit();
}

function func_restoreStyles()
{
	if (confirm("All styles will be restored from the original styles. Continue?")) {
		window.location = "admin.php?target=css_edit&action=restore_default";
	}
}
// -->
</script>

<br />
<br />

<table border=0 cellpadding=0 cellspacing=0 width=100%>

  <tr>
    <td><input type=button value="Restore original styles" onClick="javascript: func_restoreStyles()" /></td>
    <td><b>WARNING:</b> ALL CHANGES MADE TO ALL STYLES WILL BE LOST</td>
  </tr>

</table>

