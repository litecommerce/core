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
<TABLE width=100% border=0>
<form action="admin.php" method="POST">
<input type="hidden" name="target" value="intershipper">
<input type="hidden" name="action" value="update">
<TR>
<TD width=50%><B>User name:</B></TD>
<TD>
<input type="text" name="userid" size="30" value="{settings.userid:r}">
</TD>
</TR>
<TR>
<TD width=50%><B>Password:</B></TD>
<TD>
<input type="password" name="password" size="30" value="{settings.password:r}">
</TD>
</TR>
<TR>
<TD width=50%><B>Type of delivery:</B></TD>
<TD><SELECT name=delivery>
	<OPTION FOREACH="deliveries,k,v" value="{k}" selected="{isSelected(settings.delivery,k)}">{v}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD width=50%><B>Type of pickup:</B></TD>
<TD><SELECT name=pickup>
	<OPTION  FOREACH="pickups,k,v" value="{k}" selected="{isSelected(settings.pickup,k)}">{v}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD width=50%><B>Length:</B></TD>
<TD><input type=text name=length size=10 value="{settings.length}"></TD>
</TR>
<TR>
<TD width=50%><B>Width:</B></TD>
<TD><input type=text name=width size=10 value="{settings.width}"></TD>
</TR>
<TR>
<TD width=50%><B>Height:</B></TD>
<TD><input type=text name=height size=10 value="{settings.height}"></TD>
</TR>
<TR>
<TD width=50%><B>Dimensional unit code:</B></TD>
<TD><SELECT name=dunit>
	<OPTION  FOREACH="dunits,k,v" value="{k}" selected="{isSelected(settings.dunit,k)}">{v}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD width=50%><B>Package type:</B></TD>
<TD><SELECT name=packaging>
	<OPTION  FOREACH="packagings,k,v" value="{k}" selected="{isSelected(settings.packaging,k)}">{v}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD width=50%><B>Nature of Shipment Contents:</B></TD>
<TD><SELECT name=contents>
	<OPTION  FOREACH="contents_types,k,v" value="{k}" selected="{isSelected(settings.contents,k)}">{v}</OPTION>
</SELECT></TD>
</TR>
<TR>
<TD width=50%><B>Package InsuredValue in cents:</B></TD>
<TD><input type=text name=insvalue size=10 value="{settings.insvalue}"></TD>
</TR>
<TR>
<TD colspan=2><INPUT type=submit value='Apply'></TD>
</TR>
</form>
</TABLE>
