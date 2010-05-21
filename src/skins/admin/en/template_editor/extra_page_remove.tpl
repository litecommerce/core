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
Are you sure you want to remove the page titled "{extraPage.title:h}" ({extraPage.page}.tpl) ?

<p>

<form action="admin.php" method="POST" name="page_remove">
<input type="hidden" name="target" value="template_editor">
<input type="hidden" name="action" value="page_remove">
<input type="hidden" name="editor" value="extra_pages">
<input type="hidden" name="page" value="{extraPage.page}">
<input type=button value=" Yes " onClick="document.page_remove.submit()" class="DialogMainButton">
&nbsp;
&nbsp;
&nbsp;
<input type=button value=" No " onClick="document.page_remove.action.value=''; document.page_remove.submit()">
</form>
