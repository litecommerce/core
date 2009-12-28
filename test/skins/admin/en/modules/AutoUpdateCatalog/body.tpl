<form name="generate_catalog_confirm" action="admin.php" method="POST">
<input FOREACH="dialog.allparams,name,val" type="hidden" name="{name}" value="{val:h}"/>
<input type="hidden" name="action" value="update">

<p><b>WARNING!</b> Static HTML catalog update may take a long time depending on the number of (sub)categories in your store. You can rebuild static HTML catalog from scratch later from <a href="admin.php?target=catalog"><u>Catalog :: HTML catalog </u></a> 

<p>Are you sure you want to update static HTML catalog?

<p>
<input type="submit" name="yes_btn" value="Yes">&nbsp;&nbsp;&nbsp;
<input type="button" name="no_btn" value="No" onClick="document.location='{returnUrl:h}'"/>

</form>
