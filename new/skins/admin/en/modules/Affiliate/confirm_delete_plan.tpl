<form action="admin.php" method=POST>
<input type=hidden name=target value=affiliate_plans>
<input type=hidden name=action value=delete>
<input type=hidden name=plan_id value="{affiliatePlan.plan_id}">
<p>Are you sure you want to delete affiliate plan &quot;{affiliatePlan.title:h}&quot;?
<p>If you delete plan, assigned partners will not be able to receive commissions. 
<p><input type=submit value=" Delete ">
<input type=button value=" Cancel " onClick="document.location='admin.php?target=affiliate_plans'">
</form>
