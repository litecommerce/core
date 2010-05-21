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
<p align="justify">
This section contains the store's basic templates to be edited.
<widget template="template_editor/notes.tpl">
</p>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="update_templates">

<span FOREACH="basicTemplates,temp">
<p>
<span IF="{temp.comment}"><b>{temp.comment}</b>, </span><i>template file: {temp.path}</i><br>
<span IF="!temp.isExists()" class="ErrorMessage">This file does not exist. It will be created automatically after you have edited the template and saved your modifications.<br></span>
<span IF="temp.read_only_access" class="ErrorMessage">WARNING! File cannot be overwritten! Please check and correct file permissions.<br></span>
<textarea name="template[{temp.path}]" cols="81" rows="10">{temp.content}</textarea>
<br>
<input type="submit" value=" Update templates ">
<br><br>
</span>
</form>
