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
<p IF="read_only_access" class="ErrorMessage">WARNING! File cannot be {if:!mode=#page_edit#}created{else:}overwritten{end:}! Please check and correct file permissions.</p>
<p IF="status=#updated#" class="SuccessMessage">&gt;&gt; Page has been updated successfully &lt;&lt;</p>
<widget template="template_editor/notes.tpl">
<br />
<br />
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="update_page">
<input type="hidden" name="editor" value="extra_pages">
<input type="hidden" name="page" value="{extraPage.page}">
<input IF="{extraPage.page}" type="hidden" name="returnUrl" value="{url}&mode=page_edit&page={extraPage.page}&status=updated"/>

<table border="0" cellpadding=2 cellspacing=2 IF="{extraPage.page}" width="430">
<tr>
<td>
	<table border="0" cellpadding=0 cellspacing=0 width=100%>
    <tr>
    	<td width=50%><img src="images/doc.gif" border="0" align="top">&nbsp;<b>Page template:</b> <i>{extraPage.page}.tpl</i></td>
    	<td width=50% align=right><a href="{url}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle"> Go back</a></td>
	</tr>
	</table>
</td>
</tr>
<tr>
<td>
&nbsp;
</td>
</tr>
</table>

<b>Page title:</b><br>
{if:extraPage.page}
<input type="text" name="title" value="{extraPage.title:h}" size="80" width="150px">
{else:}
<input type="text" name="title" value="{title:h}" size="80" width="150px">
{end:}
<widget class="XLite_Validator_RequiredValidator" field="title">
<br><br>
{if:!extraPage.page}
<input type="hidden" value="1" name="new_page">
<b>Page name:</b><br>
<input type="text" name="page" value="{page}" size="80" width="150px">
<widget class="XLite_Validator_PatternValidator" field="page" pattern="/(^[a-zA-Z0-9_]+$)|(^$)/" template="template_editor/name_validator.tpl">
<br><br>
<b>Note:</b> You can leave the page name field empty. In this case it will be generated automatically. 
<p><i>Only letters, numbers and symbol "_" are allowed in a page name.</i></p>
{end:}
<b>Page text:</b><br>
{if:extraPage.page}
<textarea name="content" cols="81" rows="20">{extraPage.template.content}</textarea>
{else:}
<textarea name="content" cols="81" rows="20">{content}</textarea>
{end:}
<p>

<table border="0" cellpadding=2 cellspacing=2 width="430">
<tr>
<td align=left>
<input IF="{extraPage.page}" type="submit" value=" Update page " class="DialogMainButton" onClick="javascript: ValidateName();" />
<input IF="{!extraPage.page}" type="submit" value=" Add page " class="DialogMainButton" onClick="javascript: ValidateName();" />
</td>
<td align=right>
<input type="button" IF="{extraPage.page}" name="cancel" value="Cancel" onclick="javascript: document.location='{url}'">
</td>
</tr>
</table>

</form>
