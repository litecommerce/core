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
// -->
</script>

<p class="ErrorMessage" IF="couponCodeDuplicate">&gt;&gt;&nbsp;Unable to change coupon: there is a coupon with the same coupon #&nbsp;&lt;&lt;</p>
<p class="SuccessMessage" IF="couponUpdated">&gt;&gt;&nbsp;Coupon has been updated successfully.&nbsp;&lt;&lt;</p>
<form action="admin.php" method="POST" name="coupon_form">
<input type="hidden" name="target" value="discount_coupon">
<input type="hidden" name="coupon_id" value="{coupon_id}">
<input type="hidden" name="action" value="update">

<table border="0">
<tr>
    <td>Coupon # <font class="Star">*</font></td>
    <td><input type="text" size="24" name="coupon" value="{dc.coupon:r}"></td>
</tr>
<tr>
    <td>Times to use</td>
    <td><input type="text" size="8" name="times" value="{dc.times:r}">&nbsp;(already used <b>{dc.timesUsed}</b> time(s))</td>
</tr>
<tr>
    <td>Status</td>
    <td>
        <select name="status">
            <option value="A" selected="{dc.status=#A#}">Active</option>
            <option value="D" selected="{dc.status=#D#}">Disabled</option>
            <option value="U" selected="{dc.status=#U#}">Used</option>
        </select>
    </td>
</tr>
<tr>
    <td colspan=2>
    <table border="0" cellpadding=0 cellspacing=0>
    <tr>
        <td>Coupon type</td>
        <td>&nbsp;</td>
        <td>
            <select name="type" onChange="TypeChanged(this.selectedIndex)">
                <option value="absolute" selected="{dc.type=#absolute#}">$ off</option>
                <option value="percent" selected="{dc.type=#percent#}">% off</option>
                <option value="freeship" selected="{dc.type=#freeship#}">Free shipping</option>
            </select>
        </td>
        <td id="discount_box">
            <table border="0" cellpadding=0 cellspacing=0>
            <tr>
                <td>&nbsp;&nbsp;</td>
                <td>Discount</td>
                <td>&nbsp;</td>
                <td><input type="text" size="24" name="discount" value="{dc.discount}"></td>
            </tr>
            </table>
        </td>
    </tr>
    </table>
    </td>
</tr>
<tr>
    <td>Expires</td>
    <td><widget class="XLite_View_Date" field="expire" value="{dc.expire}"></td>
</tr>
<tr>
    <td valign="top">Apply to</td>
    <td>
    <table border="0">
    <tr>
        <td valign="top"><input type="radio" name="applyTo" value="total" checked="{dc.applyTo=#total#}"></td>
        <td>order subtotal, $<br><input type="text" size="24" name="minamount" value="{dc.minamount}"></td>
    </tr>
    <tr>
        <td valign="top"><input type="radio" name="applyTo" value="product" checked="{dc.applyTo=#product#}"></td>
        <td>one product<br><widget class="XLite_View_ProductSelect" formName="coupon_form" product="{dc.product}"></td>
    </tr>
    <tr>
        <td valign="top"><input type="radio" name="applyTo" value="category" checked="{dc.applyTo=#category#}"></td>
        <td>one category <br><widget class="XLite_View_CategorySelect" fieldName="category_id" selectedCategoryId="{dc.category_id}"></td>
    </tr>
    </table>
    </td>
</tr>
</table>

<input type="submit" value=" Update ">
</form>
