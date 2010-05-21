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
This section lists search queries performed by customers. Statistics can be ordered by strings searched (query), search count or amount of products found by the search tool.
</p>
<form name="searchStat" action="admin.php" method="get">
<input type="hidden" name="target" value="searchStat">
Order by: <select name="listOrder" onChange="document.searchStat.submit()">
	<option FOREACH="orders,order,name" selected="{order=listOrder}">{order}</option>
</select>
</form>
<br>
<table border=0 cellpadding=0 cellspacing=0>
<tr><td class="TableHead">
<table border=0 cellpadding=3 cellspacing=1>
<tr  class="TableHead">
	<th>Query</th>
	<th>Count</th>
	<th>Products found</th>
</tr>
<tr FOREACH="searchStat,ind,stat" class="{getRowClass(ind,#DialogBox#,#TableRow#)}">
	<td>{stat.query}</td>
	<td align="right">{stat.count}</td>
	<td align="right">{stat.product_count}</td>
</tr>
</table>
</td></tr>
</table>

<br>

<form action="admin.php" method="POST">
<input type="hidden" name="target" value="searchStat">
<input type="hidden" name="action" value="cleanup">
<b>Cleanup queries:</b> <select name="maxCount">
<option value="1">requested only once</option>
<option value="2">requested once or twice</option>
<option value="1000000">All</option>
</select>
<input type="submit" value="Cleanup">
</form>
