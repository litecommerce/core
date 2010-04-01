<p IF="status=#updated#" class="SuccessMessage">&gt;&gt; CSS style has been updated successfully &lt;&lt;</p>

<form name="style_form" action="admin.php" method="POST">
<input type="hidden" foreach="allparams,_name,_val" name="{_name}" value="{_val}"/>
<input type="hidden" name="action" value="save">
<input type="hidden" name="style" value="{css_style(style_id)}">

<p class="TopLabel">
Tag/class {css_class(style_id)}
{if:css_comment(style_id)}
</p>
<p>
<b>Description:</b> {css_comment(style_id)}
{end:}
</p>

<table bgcolor="#808080" cellspacing="1" cellpadding="0" width="100%">
<tr><td>
	<table border="0" width="100%" cellspacing="0" cellpadding="3">
	<tr bgcolor="#CCCCCC">
		<td>
			<a href="javascript:setBold()"><img src="skins/admin/en/css_editor/images/button_bold.gif" border="1" width="21" height="20"></a>
		</td>
		<td>
			<a href="javascript:setUnderline()"><img src="skins/admin/en/css_editor/images/button_underline.gif" border="1" width="21" height="20"></a>
		</td>
		<td>
			<a href="javascript:setItalic()"><img src="skins/admin/en/css_editor/images/button_italic.gif" border="1" width="21" height="20"></a>
			</td>
		<td><img src="skins/admin/en/css_editor/images/seperator.gif"></td>
			
		<td>
			<a href="javascript:showColorPicker('foreground')"><img src="skins/admin/en/css_editor/images/color.gif" border="1"></a>
		</td>

		<td>
			<a href="javascript:showColorPicker('background')"><img src="skins/admin/en/css_editor/images/bgcolor.gif" border="1"></a>
		</td>

		<td>
			<a href="javascript:setColor('background','')"><img src="skins/admin/en/css_editor/images/delete_back.gif" border="1" width="21" height="20" alt="delete background"></a>
		</td>

		<td>
			<select id="name_select" onchange="setFontName()">
			<option>Font</option>
			<option>Default</option>
			<option value="arial">arial</option>
			<option value="verdana">verdana</option>
			<option value="tahoma">tahoma</option>
			<option value="courier">courier</option>
			<option value="georgia">georgia</option>
			</select>
		</td>
			
		<td>
			<select id="size_select" onchange="setFontSize()">
			<option>Size</option>
			<option value="">Default</option>
			<option value="8px">8</option>
			<option value="9px">9</option>
			<option value="10px">10</option>
			<option value="11px">11</option>
			<option value="12px">12</option>
			<option value="14px">14</option>
			<option value="16px">16</option>
			<option value="18px">18</option>
			<option value="20px">20</option>
			<option value="22px">22</option>
			<option value="24px">24</option>
			<option value="26px">26</option>
			<option value="28px">28</option>
			<option value="36px">36</option>
			<option value="48px">48</option>
			<option value="72px">72</option>
			</select>
		</td>
		<td width="100%">&nbsp;</td>
	</tr>	
	</table>
</td></tr>
<tr bgcolor="#FFFFFF"><td>
<table border="0" width="100%" cellspacing="3" cellpadding="5">
<tr>
	<td style="{css_style(style_id)}" id="style_preview">
	The quick brown fox jumps over the lazy dog.
	</td>
</tr>
</table>

<tr bgcolor="#CCCCCC">
<td>
<table border="0" bgcolor="#CCCCCC" width="100%" cellspacing="0" cellpadding="3">
<tr><td>&nbsp;</td></tr>
</table>
</td></tr>
</table>

<br>
<table border=0 cellpadding=0 cellspacing=0 width=100%>
<tr>
	<td><input type=button value="Save changes" onClick="javascript: onSaveClick();" class="DialogMainButton"></td>
    <td align=right><input type=button value="Return back" onClick="javascript: window.location='admin.php?target=css_edit';"></td>
</tr>
</table>

</form>
<script language="javascript">

var cp_init_color = '';
var cp_xground    = '';

var css_text = '{addSlashes(css_style(style_id)):h}';

<!--
var __style;
if (navigator.product == 'Gecko') {
	__style = new Array();
} else {
	__style = new Object();
}	

_init_style();

function RemoveSpace(str)
{
    return (str.replace(/ /, ""));
}

function LTrim(str)
{
    while(str.charAt(0) == ' ') {
        str = str.slice(1);
    }
    return str;
}

function _updateStyle(entry, style)
{
	__style[entry.toUpperCase()] = style;
}

function setOutputStyle()
{
	var css_text = "";
	for (i in __style) {
		//css_text += i + ": " + __style[i] + "; ";
        if (__style[i] != "" && __style[i] != null) {
            css_text += i + ": " + LTrim(__style[i]) + "; ";
        }
	}
    document.style_form.style.value = css_text;
}

function _init_style()
{
	css_text = css_text.replace(/\"(\w+)\"/g, "$1");
	css_text = css_text.replace(/,{2,}/g, ",");
	css_text = css_text.replace(/\w+:\s+;/g, "");

	var elements = css_text.split(/;/g);
	for (i in elements) {
		var temp = elements[i].split(/:/g);
		if (temp[0].length > 0) {
			//__style[temp[0].toUpperCase()] = temp[1];
            __style[RemoveSpace(temp[0].toUpperCase())] = temp[1];
		}	
	}
}

function setBold()
{
	var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}	
	var currentBold = element.currentStyle.fontWeight;
	if (currentBold <= 400 || currentBold == 'normal') {
		element.style.fontWeight = "bold";
	} else {
		element.style.fontWeight = "normal";
	}
	_updateStyle("FONT-WEIGHT", element.style.fontWeight);
}

function setUnderline()
{
	var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}	
	var currentUnderline = element.currentStyle.textDecoration;
	if (currentUnderline != "underline") {
		element.style.textDecoration = "underline";
	} else {
		element.style.textDecoration = "none";
	}	
	_updateStyle("TEXT-DECORATION", element.style.textDecoration);
}

function setItalic()
{
	var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}	
	var currentItalic = element.currentStyle.fontStyle;
	if (currentItalic != "italic") {
		element.style.fontStyle = "italic";
	} else {
		element.style.fontStyle = "normal";
	}	
	_updateStyle("FONT-STYLE", element.style.fontStyle);
}

function setColor(xground, color)
{
	var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}
	css_el = "";
	if (xground == "background") {
		css_el = "BACKGROUND-COLOR";
		element.style.backgroundColor = color;
	} else {
		css_el = "COLOR";
		element.style.color = color;
	}	
	_updateStyle(css_el, color);
}

function showColorPicker(xground)
{
	if (navigator.userAgent.indexOf("Safari") != -1) {
        alert("This functionality is not available in your browser.");
        return;
    }
    var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}	
    var colorpicker = window.open("skins/admin/en/css_editor/colorpicker/colorpicker.html", "_blank", "channelmode=0, directories=0, fullscreen=0, height=345, location=0, menubar=0, resizable=0, scrollbars=0, status=0, titlebar=0, toolbar=0, width=399");
    if (xground == "background") {
		cp_init_color = element.currentStyle.backgroundColor;
	} else {
		cp_init_color = element.currentStyle.color;
	}	
	cp_xground = xground;
}

function setFontSize()
{
	var element = document.getElementById('style_preview');
	if (!element.currentStyle) {
		element.currentStyle = element.style;
	}
	var current_size = document.getElementById('size_select').value;
	element.style.fontSize = current_size;
	_updateStyle("FONT-SIZE", element.style.fontSize);
}

function setFontName()
{
	var element = document.getElementById('style_preview');
	var fontName = document.getElementById('name_select').value;
	element.style.fontFamily = fontName;
	_updateStyle("FONT-FAMILY", element.style.fontFamily);
}

function onSaveClick()
{
	setOutputStyle();
	document.style_form.submit();
}
// -->
</script>
