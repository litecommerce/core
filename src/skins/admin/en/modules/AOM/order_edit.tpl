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
	var mode;
	var modes = new Array();
	var i = 0;
	modes[i++] = "products";
	modes[i++] = "profile";
	if ("{mm.activeModules.GiftCertificates}") modes[i++] = "gc";
	if ("{mm.activeModules.Promotion}") modes[i++] = "dc";
	modes[i++] = "totals";
	if ("{isCloneUpdated()&!cloneOrder.isEmpty()}") modes[i++] = "preview";

	function getName(mode) 
	{
		var name;
		switch (mode) {
			case "products" : 	name = "Ordered Products"; break;
    	    case "profile" : 	name = "Customer Information"; break;
        	case "dc" : 		name = "Discount Coupon"; break;
           	case "gc" : 		name = "Gift Certificates"; break;
           	case "totals" : 	name = "Order Totals"; break;
		}
		return name;
	}
	
	function Next()
	{
		for (var i = 0; i < modes.length; i++) 
			if (modes[i] == mode) { 
				changeMode(modes[i+1]);	
				return;
			}
	}

	function Previous()
	{
        for (var i = 0; i < modes.length; i++) 
            if (modes[i] == mode) { 
				changeMode(modes[i-1]);   
				return;
			}	
	}
	
	function changeMode(current_mode)
	{
		if (current_mode) {
			if (current_mode == "search") current_mode = "products";
			if (current_mode == "search_gc") current_mode = "gc";
			if (current_mode == "search_dc") current_mode = "dc";
		} else {
			current_mode = "products";
		}

		isNoProducts = "{isEmpty(cloneOrder.items)}";
		if (isNoProducts == "1" && current_mode == "totals") {
		    alert("The order does not contain any product items!");
			current_mode = "products";
		}

		var i = 0;
		mode = current_mode;
		var flag = false;
		for (i=0; i < modes.length; i++) {
			var element = document.getElementById(modes[i]);
			var element_label = document.getElementById(modes[i]+"_label");
			var element_line1 = document.getElementById(modes[i]+"_line1");
			var element_line2 = document.getElementById(modes[i]+"_line2");
			var element_line3 = document.getElementById(modes[i]+"_line3");
            var element_selected = document.getElementById(modes[i]+"_selected");
			if (current_mode == modes[i]) {
		        var element_prev = document.getElementById(current_mode + "_prev");
                var element_next = document.getElementById(current_mode + "_next");
				if (element_prev) element_prev.innerHTML = "Back to '" + getName(modes[i-1]) + "'";
                if (element_next) element_next.innerHTML = "Next to '" + getName(modes[i+1]) + "'";
								
				element.style.display = "";
				element_label.style.fontWeight = "bold";	
                element_label.style.textDecoration = "";
				element_selected.style.display = "";
				if (element_line1)
					element_line1.className = "HorBorderHighlighted";
                if (element_line3) 
                    element_line3.className = "HorBorder";
                if (element_line2) {
                    element_line2.className = "VerBorderHighlighted";
					if (i == 0) {
						element_line2.className = "LeftVerBorderHighlighted";
					}
				}
			} else {
				if (flag) {
        	        if (element_line1)
            	        element_line1.className = "HorBorder";
	                if (element_line3)
    	                element_line3.className = "HorBorder";
	                if (element_line2) {
    	                element_line2.className = "VerBorder";
        	            if (i == 0) {
            	            element_line2.className = "LeftVerBorder"; 
                	    }
					}
				} else {
	                if (element_line1)
    	                element_line1.className = "HorBorderHighlighted";
	                if (element_line3)
        	            element_line3.className = "HorBorderHighlighted";
    	            if (element_line2) {
	           	        element_line2.className = "VerBorder";
	                    if (i == 0) {
    	                    element_line2.className = "LeftVerBorder"; 
        	            }
					}
				}
       			element_selected.style.display = "none";
                element.style.display = "none";
                element_label.style.textDecoration = "underline";
				element_label.style.fontWeight = "";
			}
			if (current_mode == modes[i]) {
				flag = true;
			}	
		}
	}
</script>
<form name="order_form" action="admin.php" method="POST">
<input FOREACH="allparams,_name,_val" type="hidden" name="{_name}" value="{_val:r}"/>
<input type="hidden" name="action" value="clone_order">
</form>
						
<table width="90%" align="center" cellpadding="0" cellspacing="0">
	<tr>	
		<td>
			<table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="products_label" href="javascript: changeMode('products')" onClick="this.blur()">Ordered products</a></td>
                </tr>
				<tr>
			        <td width="50%"></td>
        	        <td id="products_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
				</tr>
				<tr>
                    <td width="50%"></td>
					<td width="50%" id="products_line3"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
				</tr>
				<tr>
					<td colspan="2" align="center" height="10"><img id="products_selected" src="images/modules/AOM/nav_arrow.gif"></td>
				</tr>	
			</table>
		</td>
        <td>
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="profile_label" href="javascript: changeMode('profile')" onClick="this.blur()">Customer information</a></td>
                </tr>
                <tr>
                    <td id="profile_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
			       <td width="50%"></td>
                </tr>
                <tr>
                    <td width="50%" id="profile_line1"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                    <td width="50%" id="profile_line3"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="10"><img id="profile_selected" src="images/modules/AOM/nav_arrow.gif"></td>
	            </tr>   
            </table>
		</td>
        <td IF="mm.activeModules.GiftCertificates">
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="gc_label" href="javascript: changeMode('gc')" onClick="this.blur()">Gift Certificates</a></td>
                </tr>
                <tr>
                    <td id="gc_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
					<td width="50%"></td>
                </tr>
                <tr>
                    <td width="50%" id="gc_line1"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                    <td width="50%" id="gc_line3"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="10"><img id="gc_selected" src="images/modules/AOM/nav_arrow.gif" hspace="0"></td>
                </tr>   
            </table>
		</td>
        <td IF="mm.activeModules.Promotion">
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="dc_label" href="javascript: changeMode('dc')" onClick="this.blur()">Discount Coupon</a></td>
                </tr>
                <tr>
                    <td id="dc_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
			        <td width="50%"></td>
                </tr>
                <tr>
                    <td width="50%" id="dc_line1"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                    <td width="50%" id="dc_line3"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="10"><img id="dc_selected" src="images/modules/AOM/nav_arrow.gif"></td>
                </tr>
            </table>
		</td>
        <td>
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="totals_label" href="javascript: changeMode('totals')" onClick="this.blur()">Order totals</a></td>
                </tr>
                <tr>
                    <td id="totals_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
				    <td width="50%"></td>
                </tr>
                <tr>
                    <td width="50%" id="totals_line1"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                    <td IF="isCloneUpdated()&!cloneOrder.isEmpty()" width="50%" id="totals_line3"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="10"><img id="totals_selected" src="images/modules/AOM/nav_arrow.gif"></td>
	            </tr>
            </table>
		</td>
        <td IF="isCloneUpdated()&!cloneOrder.isEmpty()">
            <table border="0" width="100%" cellpadding="1" cellspacing="0">
                <tr>
                    <td colspan="2" align="center" nowrap><a class="AomMenu" id="preview_label" href="admin.php?target={target}&order_id={order_id}&page=order_preview" onClick="this.blur()">Review And Save Order<font class="Star">*</font></a></td>
                </tr>
                <tr>
                    <td id="preview_line2"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
			        <td width="50%"></td>
                </tr>
                <tr>
                    <td width="50%" id="preview_line1"><img src="images/spacer.gif" hspace="0" vspace="0"></td>
                    <td width="50%"></td>
                </tr>
                <tr>
                    <td colspan="2" align="center" height="10"><img id="preview_selected" src="images/modules/AOM/nav_arrow.gif"></td>
	            </tr>
            </table>
        </td>
	</tr>
</table>
<br>
<span id="preview" style="display:none">
</span>
<span id="products">
<widget template="modules/AOM/order_edit/products.tpl">
</span>
<span id="profile" style="display: none;">
<widget template="modules/AOM/order_edit/profile.tpl">
</span>
<span id="totals" style="display: none;">
<widget template="modules/AOM/order_edit/totals.tpl">
</span>
<span id="gc" style="display: none;">
<widget module="GiftCertificates" template="modules/AOM/order_edit/gc.tpl">
</span>
<span id="dc" style="display: none;">
<widget module="Promotion" template="modules/AOM/order_edit/dc.tpl">
</span>
<script>changeMode("{mode}");</script>
<br><br>
