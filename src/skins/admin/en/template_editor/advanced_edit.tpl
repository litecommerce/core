{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * ____file_title____
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   GIT: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}
<table cellpadding="2" cellspacing="2">
<form action="admin.php" method="POST" name="editor_form">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="advanced_update">
<input type="hidden" name="editor" value="advanced">
<input type="hidden" name="zone" value="{zone}">
<input type="hidden" name="file" value="{file.path}">
<input type="hidden" name="node" value="{file.node}">

<tr>
<td colspan=2>
	<table cellpadding="0" cellspacing="0" width="100%">
    <tr>
    	<td style="width:50%;"><img src="images/doc.gif" align="top" alt="" />&nbsp;<b>{file.path}</b></td>
    	<td style="width:50%;" align=right>
        	<table cellpadding="0" cellspacing="0">
            <tr>
            	<td><a href="{url:h}&node={file.node}"><img src="images/go.gif" width="13" height="13" align="absmiddle" alt="" /> Go back to&nbsp;</a></td>
            	<td><a href="{url:h}&node={file.node}"><img src="images/folder.gif" align="top" alt="" />&nbsp;{file.node}</a></td>
        	</tr>
        	</table>
    	</td>
	</tr>
	</table>
</td>
</tr>

<tr IF="error">
<td colspan=2 class="error-message">WARNING! File cannot be overwritten! Please check and correct file permissions.</td>
</tr>

<tr>
<td colspan=2>
<br />
<textarea name="content" cols="120" rows="40">{file.content}</textarea>
</td>
</tr>

<tr>
<td align=left>
<widget class="\XLite\View\Button\Submit" label=" Save " style="main-button" />
</td>
<td align=right>
{if:file.node}
<widget class="\XLite\View\Button\Regular" label="Cancel" jsCode="document.location='{url}&node={file.node:u}'" />
{else:}
<widget class="\XLite\View\Button\Regular" label="Cancel" jsCode="document.location='{url}'" />
{end:}
</td>
</tr>

</form>
</table>
