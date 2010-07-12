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
<table>
<tr IF="submode=#warning#">
<!-- Show delete profile confirmation dialog -->
<td>
<span class="Text">
Do you really want to delete your profile?
</span>
<p>
<table border=0>
<tr>
<td><widget class="\XLite\View\Button\Link" label=" Yes " location="{buildURL(#profile#,#delete#)}" /></td>
<td nowrap>&nbsp;&nbsp;&nbsp;</td>
<td><widget class="\XLite\View\Button\Link" label=" No " location="{buildURL(#profile#,##,_ARRAY_(#mode#^#delete#,#submode#^#cancelled#))}" /></td>
</tr>
</table>
</td>
</tr>

<tr IF="submode=#confirmed#">
<!-- Show deleted profile message -->
<td>
Your profile was deleted successfully.
</td>
</tr>

<tr IF="submode=#cancelled#">
<!-- Show cancel profile delete message -->
<td>
Your profile has not been deleted.
</td>
</tr>
</table>
