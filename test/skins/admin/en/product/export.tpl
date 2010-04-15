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
<p>Use this section to export catalog data into a CSV file.
<br><br>
<b>Note:</b> Graphic content is not included in the exported CSV file, only image file names are exported for product thumbnails and images.
<hr>

<p IF="!valid">
    <font class="ErrorMessage">&gt;&gt; Error occured &lt;&lt;<br></font>
</p>

<p>
<form action="admin.php" method=POST name=data_form>
<input type="hidden" name="target" value="export_catalog">
<input type="hidden" name="action" value="export_products">
<input type="hidden" name="page" value="{page}">

<widget template="product/layout.tpl">

<table border="0">
<tr>
    <td colspan=2>
    <br>
    Delimiter:<br><widget template="common/delimiter.tpl">
    </td>
</tr>
<tr>
    <td colspan=2> <input type=submit value="Export products" class="DialogMainButton"> </td>
</tr>
</table>
</form>
