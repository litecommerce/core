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
<script language="JavaScript">
<!--

function visibleElement(id, status)
{
    elm = document.getElementById(id);
    if (elm) {
        elm.style.display = (status) ? "" : "none";
    }
}

function TypeChanged(type)
{
	visibleElement("discount_box", (type == 2) ? false : true);
}

function SortModeAll(elm, preserve)
{
    var Elements = new Array("sort_mode_1", "sort_mode_2", "sort_mode_3");
    var i;

	if (elm.checked) {
		for(i=0; i<Elements.length; i++) {
        	elm = document.getElementById(Elements[i]);
        	if (elm) {
        		elm.checked = true;
        		elm.disabled=true;
        	}
        }
	} else {
		for(i=0; i<Elements.length; i++) {
        	elm = document.getElementById(Elements[i]);
        	if (elm) {
    			elm.disabled=false;
    			if (!preserve) {
    				elm.checked = false;
    			}
        	}
        }
	}
}

function DeleteCoupon(coupon)
{
	document.coupons_form.action.value = "delete";
	document.coupons_form.coupon_id.value = coupon;
	document.coupons_form.submit();
}

// -->
</script>

{* Discount coupons add/edit page *}
On this page you can create and manage your store coupons.

<table width="100%" border=0>
<form action="admin.php" method="GET" name="coupons_search_form">
<input type="hidden" name="target" value="DiscountCoupons">
<tr>
    <td width="100%">
    	<table cellspacing=0 cellpadding=0 border=0 width="100%">
    	<tr >
    		<td colspan=2>&nbsp;</td>
    	</tr>    	<tr>
    		<td class="SidebarTitle" align=center width=150 nowrap>Search filters</td>
    		<td width=100%>&nbsp;</td>
    	</tr>
    	<tr>
    		<td class="SidebarTitle" align=center colspan=2 height=3></td>
    	</tr>
    	</table>
    </td>
</tr>
<tr>
    <td>
    	<table cellspacing=0 cellpadding=0 border=0>
    	<tr>
        	<td>&nbsp;&nbsp;&nbsp;</td>
        	<td valign=top>
            	<table cellspacing=0 cellpadding=0 border=0>
            	<tr>
        			<td nowrap valign=middle>Show coupons with statuses:&nbsp;</td>
            		<td>
                    	<table cellspacing=0 cellpadding=0 border=0>
                    	<tr>
                    		<td><input type=checkbox name="sort_mode[0]" id="sort_mode_0" checked="{isSortSelected(#0#)}" value="1" onClick="this.blur();SortModeAll(this)"></td>
                    		<td>All</td>
        					<td>&nbsp;&nbsp;&nbsp;</td>
                    		<td><input type=checkbox name="sort_mode[1]" id="sort_mode_1" checked="{isSortSelected(#1#)}" value="1" onClick="this.blur()"></td>
                    		<td>Active</td>
        					<td>&nbsp;&nbsp;&nbsp;</td>
                    		<td><input type=checkbox name="sort_mode[2]" id="sort_mode_2" checked="{isSortSelected(#2#)}" value="1" onClick="this.blur()"></td>
                    		<td>Disabled</td>
        					<td>&nbsp;&nbsp;&nbsp;</td>
                    		<td><input type=checkbox name="sort_mode[3]" id="sort_mode_3" checked="{isSortSelected(#3#)}" value="1" onClick="this.blur()"></td>
                    		<td>Used</td>
                    	</tr>
                    	</table>
            		</td>
            	</tr>
            	</table>
        	</td>
        	<td>&nbsp;&nbsp;&nbsp;</td>
        	<td align=right><input type=submit value="Show"></td>
    	</tr>
    	</table>
    </td>
</tr>
</form>
</table>

<script language="JavaScript">
<!--

elm = document.getElementById("sort_mode_0");
if (elm) {
	SortModeAll(elm, true);
}

// -->
</script>

<p>
{if:couponsNumber=#0#}
No coupons found.
<hr>
{else:}
<b>{couponsNumber}</b> coupon{if:!couponsNumber=#1#}s are{else:} is{end:} found.
{end:}
<widget class="\XLite\View\PagerOrig" name="pager" data="{coupons}" itemsPerPage="{config.CDev.Promotion.coupons_per_page}">
<table width="100%" border="0" IF="coupons">
<form action="admin.php" method="POST" name="coupons_form">
<input type="hidden" name="target" value="DiscountCoupons">
<input type="hidden" name="action" value="update">
<input type="hidden" name="pageID" value="{pageID}">
<input type="hidden" name="sort_mode[0]" IF="{isSortSelected(#0#)}" value="1" />
<input type="hidden" name="sort_mode[1]" IF="{isSortSelected(#1#)}" value="1" />
<input type="hidden" name="sort_mode[2]" IF="{isSortSelected(#2#)}" value="1" />
<input type="hidden" name="sort_mode[3]" IF="{isSortSelected(#3#)}" value="1" />
<input type="hidden" name="coupon_id" value="">
<tr>
<th class=TableHead>Coupon</th>
<th class=TableHead>Status</th>
<th class=TableHead>Disc.</th>
<th class=TableHead>Min.</th>
<th class=TableHead>Times</th>
<th class=TableHead>Expires</th>
<th class=TableHead colspan=2>&nbsp;</th>
</tr>
<tbody FOREACH="pager.pageData,DC">
<tr>
<td>
<b><span IF="DC.expired"><s>{DC.coupon}</s></span><span IF="!DC.expired"><span IF="DC.status=#U#"><font class="ErrorMessage">{DC.coupon}</font></span><span IF="!DC.status=#U#"><span IF="DC.status=#D#"><s>{DC.coupon}</s></span><span IF="!DC.status=#D#">{DC.coupon}</span></span></span></b>
</td>
<td>
<select name="status[{DC.coupon_id}]">
<option value="A" selected="{DC.status=#A#}">Active</option>
<option value="D" selected="{DC.status=#D#}">Disabled</option>
<option value="U" selected="{DC.status=#U#}">Used</option>
</select>
</td>
<td IF="DC.type=#absolute#">{price_format(DC.discount):h}</td>
<td IF="DC.type=#percent#">{DC.discount}%</td>
<td IF="DC.type=#freeship#">Free shipping</td>
<td>{price_format(DC.minamount):h}</td>
<td align="center"><span IF="DC.timesOverused"><font class="ErrorMessage">{DC.timesUsed}/{DC.times}</font></span><span IF="!DC.timesOverused">{DC.timesUsed}/{DC.times}</span></td>
<td nowrap>
<span IF="!DC.expired">{formatDate(DC.expire):h}</span>
<span IF="DC.expired"><font class="ErrorMessage">{formatDate(DC.expire):h}</font></span>
</td>
<td align="center"><input type="button" value="&nbsp;Edit&nbsp;" onClick="location.href = 'admin.php?target=discount_coupon&coupon_id={DC.coupon_id}';"></td>
<td IF="!DC.childrenCount"><input type="button" value="Delete" onClick="DeleteCoupon('{DC.coupon_id}')"></td>
<td IF="DC.childrenCount"><input type="button" value="Delete" disabled></td>
</tr>
<tr>
<td colspan=8>
This coupon applies to 
<span IF="DC.applyTo=#product#">
<a href="admin.php?target=product&product_id={DC.product_id}"><u>{DC.product.name}</u></a> product 
</span>
<span IF="DC.applyTo=#category#">
<a href="admin.php?target=category&category_id={DC.category_id}"><u>{DC.category.name}</u></a> category
</span>
<span IF="DC.applyTo=#total#">
orders greater than {price_format(DC.minamount):h}
</span>
</td>
</tr>
<tr IF="DC.childrenCount">
<td>&nbsp;</td>
<td colspan=7>
    <table border="0" cellpadding=0 cellspacing=0>
    <tr>
    <td class="TableHead" nowrap>
    &nbsp;Coupon has {DC.childrenCount} associated order(s) &nbsp;
	<span IF="!config.CDev.Promotion.auto_expand_coupon_orders">
	{if:DC.coupon_id=children_coupon_id}
	<a href="admin.php?target=DiscountCoupons&pageID={pageID}">
		<img src="skins/admin/en/images/close_sidebar_box.gif" border="0"/>
	</a>
	{else:}
	<a href="admin.php?target=DiscountCoupons&pageID={pageID}&children_coupon_id={DC.coupon_id}">
		<img src="skins/admin/en/images/open_sidebar_box.gif" border="0"/>
	</a>
	{end:}
	</span>
    </td>
    </tr>
    <tr IF="canShowChildren(DC)">
    <td class="TableHead">
        <table border="0" cellpadding=1 cellspacing=1 width=100%>
        <tbody FOREACH="DC.children,k,child">
		<tr class="DialogBox">
        <td IF="child.order" width=50>&nbsp;<a href="admin.php?target=order&order_id={child.order_id}">#<u>{child.order_id}</u></a>&nbsp;<span IF="!child.coupon=DC.coupon">(<b>{child.coupon}</b>)</span></td>
        <td IF="!child.order" width=50>&nbsp;#{child.order_id}&nbsp;(child.coupon)</td>
        <td IF="child.order" width=90>&nbsp;<widget class="\XLite\View\StatusSelect" order="{child.order}" template="common/order_status.tpl"></td>
        <td IF="!child.order" width=90>&nbsp;n/a&nbsp;</td>
        <td nowrap IF="child.order" width=120>&nbsp;<a href="admin.php?target=order&order_id={child.order_id}">{formatTime(child.order.date)}</a>&nbsp;</td>
        <td IF="!child.order" width=120>&nbsp;n/a&nbsp;</td>
        <td nowrap IF="child.order" width=90>&nbsp;<a href="admin.php?target=order&order_id={child.order_id}">{child.order.profile.billing_title} {child.order.profile.billing_firstname} {child.order.profile.billing_lastname}</a>&nbsp;</td>
        <td IF="!child.order" width=90>&nbsp;n/a&nbsp;</td>
        <td nowrap align=right IF="child.order" width=90>&nbsp;{price_format(child.order,#total#):h}&nbsp;</td>
        <td IF="!child.order" width=90>&nbsp;n/a&nbsp;</td>
        <td nowrap align=right IF="child.order" width=50>&nbsp;<a href="admin.php?target=order&order_id={child.order_id}"><u>details</u></a>&nbsp;&gt;&gt;&nbsp;</td>
        <td IF="!child.order" width=50>&nbsp;n/a&nbsp;</td>
        </tr>
        </tbody>
        </table>
    </td>
    </tr>
    </table>
</td>
</tr>
<tr><td colspan=8 class="TableHead"></td></tr>
</tbody>
<tr>
<td colspan=8><input type="submit" value="Update" class="DialogMainButton"></td>
</tr>
</form>
</table>

<br><br>

<font class=AdminTitle>Add new coupon</font>
<form action="admin.php" method="POST" name="coupon_form">
<input type="hidden" name="target" value="DiscountCoupons">
<input type="hidden" name="action" value="add">
<input type="hidden" name="pageID" value="{pageID}">

<span IF="couponExists" class="ErrorMessage">Unable to add coupon: there is a coupon with the same #</span>

<table border="0">
<tr><td>Coupon # <font class="Star">*</font></td><td><input type="text" size="24" name="coupon" value="{coupon:r}"></td></tr>
<tr><td>Times to use</td><td><input type="text" size="8" value="1" name="times" value="{times:r}"></td></tr>
<tr><td>Status</td>
<td>
<select name="status">
<option value="A" selected="{status=#A#}">Active</option>
<option value="D" selected="{status=#D#}">Disabled</option>
</select>
</td></tr>
<tr>
<td colspan=2>
	<table border="0" cellpadding=0 cellspacing=0>
	<tr>
    <td>Coupon type</td>
	<td>&nbsp;</td>
    <td>
    <select name="type" onChange="TypeChanged(this.selectedIndex)">
    <option value="absolute" selected="{type=#absolute#}">$ off</option>
    <option value="percent" selected="{type=#percent#}">% off</option>
    <option value="freeship" selected="{type=#freeship#}">Free shipping</option>
    </select>
    </td>
    <td id="discount_box">
    	<table border="0" cellpadding=0 cellspacing=0>
    	<tr>
		<td>&nbsp;&nbsp;</td><td>Discount</td><td>&nbsp;</td><td><input type="text" size="24" name="discount" value="{discount}"></td>
        </tr>
    	</table>
    </td>
    </tr>
	</table>
</td>
</tr>
<tr><td>Expires</td>
<td>
<widget class="\XLite\View\Date" field="expire">
</td></tr>
<TR>
<TD valign="top">Apply to</TD>
<TD>
<table border="0">
<tr><td valign="top"><INPUT type="radio" name="applyTo" value="total" checked="{applyTo=#total#}"></td>
<td>order subtotal, $<br><input type="text" size="24" name="minamount" value="{minamount}"></td></tr>
<tr><td valign="top"><INPUT type="radio" name="applyTo" value="product" checked="{applyTo=#product#}"></td><td>one product<br><widget class="\XLite\View\ProductSelect" formName="coupon_form"></td></tr>
<tr><td valign="top"><INPUT type="radio" name="applyTo" value="category" checked="{applyTo=#category#}"></td><td>one category
<br><widget class="\XLite\View\CategorySelect" fieldName="category_id">
</td>
</tr>
</table>
</td></tr> </table>
<br>
<input type="submit" value="Add coupon">
<input type="hidden" name="sort_mode[0]" IF="{isSortSelected(#0#)}" value="1" />
<input type="hidden" name="sort_mode[1]" IF="{isSortSelected(#1#)}" value="1" />
<input type="hidden" name="sort_mode[2]" IF="{isSortSelected(#2#)}" value="1" />
<input type="hidden" name="sort_mode[3]" IF="{isSortSelected(#3#)}" value="1" />
</form>
