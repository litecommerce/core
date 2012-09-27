{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * "Recent login" page
 *
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011-2012 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 *}

<table width="80%" class="data-table">
<tr>
  <th style="width:150px;">{t(#Date#)}</th>
  <th>{t(#Logged as#)}</th>
</tr>
{if:recentAdmins}
  <tr FOREACH="recentAdmins,recentAdmin" class="{getRowClass(idx,##,#highlight#)}">
		<td align="center">{formatTime(recentAdmin.last_login):h}</td>
		<td>{recentAdmin.login:h}</td>
	</tr>
{end:}
</table>
