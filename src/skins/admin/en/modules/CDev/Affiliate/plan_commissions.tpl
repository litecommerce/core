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
<table border=0 width="80%">

<form action="admin.php" method=POST>
<input type=hidden name=target value=plan_commissions>
<input type=hidden name=action value=basic_commission>
<input type=hidden name=plan_id value="{plan_id}">
<input type=hidden name=item_type value=B>

<tr><td colspan=4 class=AdminHead><a name=basic_rate></a>Basic commission rate</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr>
    <td colspan=4>Basic commission rate: <widget template="modules/CDev/Affiliate/rate.tpl" pc="{basicCommission}">
    </td>
</tr>
<tr><td colspan=4><input IF="foundBasicCommission" type=submit value="Update"/><input IF="!foundBasicCommission" type=submit value=" Add "/></td></tr>
</form>

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4><a href="admin.php?target=affiliate_plans"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> List all affiliate plans</b></a></td></tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tbody>
<tr><td colspan=4 class=AdminHead><HR>Commission rates on category</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr IF="!CategoryCommissions"><td colspan=4>No commission rates on category found.<BR><BR></td></tr>
<form IF="CategoryCommissions" action="admin.php" method=POST name=category_commission_form>
<input type=hidden name=target value=plan_commissions>
<input type=hidden name=action value=update_commission>
<input type=hidden name=plan_id value="{plan_id}">
<input type=hidden name=item_type value=C>

<tr class=TableHead>
    <td>&nbsp;</td>
    <td colspan=2>Category</td>
    <td>Commission rate</td>
</tr>
<tr FOREACH="CategoryCommissions,cidx,categoryCommission" width="70%" class="{getRowClass(cidx,#TableRow#,##)}">
    <td width=10><input type=checkbox name="delete_items[{categoryCommission.item_id}]"></td>
    <td width="100%" colspan=2><a href="admin.php?target=category&category_id={categoryCommission.category.category_id}"><u>{categoryCommission.category.stringPath:h}</u></a>
    </td>
    <td><widget template="modules/CDev/Affiliate/rate.tpl" pc="{categoryCommission}" itemID="[{categoryCommission.item_id}]"></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4><input type=submit name=update value=" Update ">&nbsp;<input type=submit name=delete value=" Delete "></td></tr>
<tr><td colspan=4>&nbsp;</td></tr>

</form>

<form action="admin.php" method=POST name=add_category_commission_form>
<input type=hidden name=target value=plan_commissions>
<input type=hidden name=action value=add_commission>
<input type=hidden name=plan_id value="{plan_id}">
<input type=hidden name=item_type value=C>

<tr><td colspan=4 class=AdminTitle>Add category rate</td></tr>
<tr><td colspan=4 class=AdminTitle>&nbsp;</td></tr>
<tr><td colspan=4><widget class="\XLite\View\CategorySelect" fieldName="item_id"></td></tr>
<tr><td colspan=4>Category commission rate: <widget template="modules/CDev/Affiliate/rate.tpl" pc="{xlite.factory.\XLite\Module\CDev\Affiliate\Model\PlanCommission}"></td></tr>
<tr><td colspan=4><input type=submit value=" Add "></td></tr>

</form>
</tbody>

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4><a href="admin.php?target=affiliate_plans"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> List all affiliate plans</b></a></td></tr>
<tr><td colspan=4>&nbsp;</td></tr>

<tbody>
<tr><td colspan=4 class=AdminHead><HR>Commission rates on product</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr IF="!ProductCommissions"><td colspan=4>No commission rates on product found.<BR><BR></td></tr>
<form IF="ProductCommissions" action="admin.php" method=POST name=product_commission_form>
<input type=hidden name=target value=plan_commissions>
<input type=hidden name=action value=update_commission>
<input type=hidden name=plan_id value="{plan_id}">
<input type=hidden name=item_type value=P>

<tr class=TableHead>
    <td>&nbsp;</td>
    <td>Product name</td>
    <td>Price</td>
    <td>Commission rate</td>
</tr>
<tr FOREACH="ProductCommissions,pidx,productCommission" width="70%" class="{getRowClass(pidx,#TableRow#,##)}">
    <td width=10><input type=checkbox name="delete_items[{productCommission.item_id}]"></td>
    <td width="100%"><a href="admin.php?target=product&product_id={productCommission.product.product_id}"><u>{productCommission.product.name:h}</u></a>
    </td>
    <td nowrap align=right>&nbsp;{price_format(productCommission.product,#price#):h}&nbsp;</td>
    <td><widget template="modules/CDev/Affiliate/rate.tpl" pc="{productCommission}" itemID="[{productCommission.item_id}]"></td>
</tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4><input type=submit name=update value=" Update ">&nbsp;<input type=submit name=delete value=" Delete "></td></tr>
<tr><td colspan=4>&nbsp;</td></tr>

</form>

<form action="admin.php" method=POST name=add_product_commission_form>
<input type=hidden name=target value=plan_commissions>
<input type=hidden name=action value=add_commission>
<input type=hidden name=plan_id value="{plan_id}">
<input type=hidden name=item_type value=P>

<tr><td colspan=4 class=AdminTitle>Add product rate</td></tr>
<tr><td colspan=4 class=AdminTitle>&nbsp;</td></tr>
<tr><td colspan=4>Product: <widget class="\XLite\View\ProductSelect" formName="add_product_commission_form" formField="item"></td></tr>
<tr><td colspan=4>Product commission rate: <widget template="modules/CDev/Affiliate/rate.tpl" pc="{xlite.factory.\XLite\Module\CDev\Affiliate\Model\PlanCommission}"></td></tr>
<tr><td colspan=4><input type=submit value=" Add "></td></tr>

</form>
</tbody>

<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4>&nbsp;</td></tr>
<tr><td colspan=4><a href="admin.php?target=affiliate_plans"><img src="skins/admin/en/images/go.gif" width="13" height="13" border="0" align="absmiddle"><b> List all affiliate plans</b></a></td></tr>
<tr><td colspan=4>&nbsp;</td></tr>

</table>
