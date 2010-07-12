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
<widget class="\XLite\View\Pager" data="{stat}" name="pager">
<table IF="stat" border="0" cellspacing="1" cellpadding="3">
<tr class="TableHead">
	<td>&nbsp;Product&nbsp;</td>
	<td>&nbsp;Date&nbsp;</td>
	<td>&nbsp;Headers&nbsp;</td>
</td>
<tr FOREACH="pager.pageData,stat_line">
	<td><a href="{getProductHref(stat_line.file_id)}"><u>{getProductName(stat_line.file_id)}</u></a>&nbsp;</td>
	<td nowrap>{time_format(stat_line.date)}&nbsp;</td>
	<td>{stat_line.headers}</td>
</tr>
</table>
<widget name="pager">

<span IF="!stat">No downloads found.</span>
