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
<table width="80%" class="data-table">
<tr>
  <th width="150px">Date</th>
  <th>Logged as</th>
</tr>
<tbody IF="recentAdmins">
  <tr FOREACH="recentAdmins,recentAdmin" class="{getRowClass(idx,##,#highlight#)}">
		<td align="center">{formatTime(recentAdmin.last_login):h}</td>
		<td>{recentAdmin.login:h}</td>
	</tr>
</tbody>
</table>
