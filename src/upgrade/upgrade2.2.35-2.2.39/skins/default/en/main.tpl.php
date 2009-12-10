<?php

	$find_str = <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
EOT;
	$replace_str = <<<EOT
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<LINK IFF="xlite.FlyoutCategoriesEnabled" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
EOT;
	$replace_str = <<<EOT
<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<!-- [center] -->
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
EOT;
	$replace_str = <<<EOT
<!-- [center] -->
<widget module="GoogleCheckout" class="CGoogleAltCheckout">
<widget module="InventoryTracking" target="cart" mode="exceeding" template="common/dialog.tpl" body="modules/InventoryTracking/exceeding.tpl" head="InventoryTracking Notification">
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm">
EOT;
	$replace_str = <<<EOT
<widget target="profile" mode="register" class="CRegisterForm" template="common/dialog.tpl" head="New customer" body="register_form.tpl" name="registerForm" IF="!showAV"/>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm">
EOT;
	$replace_str = <<<EOT
<widget target="profile" mode="modify" class="CRegisterForm" template="common/dialog.tpl" head="Modify profile" body="profile.tpl" name="profileForm" IF="!showAV"/>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<!-- [/profile] }}} -->
EOT;
	$replace_str = <<<EOT
<widget target="profile" mode="delete" template="common/dialog.tpl" head="Delete profile - Confirmation" body="delete_profile.tpl">
<widget module="UPSOnlineTools" template="modules/UPSOnlineTools/main.tpl">
<!-- [/profile] }}} -->
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart">
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{!xlite.PayPalProSolution=#standard#}">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}">
EOT;
	$replace_str = <<<EOT
<widget target="checkout" mode="register,paymentMethod,details" template="common/dialog.tpl" body="checkout/checkout.tpl" head="Shopping cart" IF="!showAV"/>
<widget module="PayPalPro" target="checkout" mode="register" template="common/dialog.tpl" body="modules/PayPalPro/retrieve_profile.tpl" head="Make checkout easier with PayPal Website Pro" visible="{xlite.PayPalProExpressEnabled}">
<widget target="checkout" mode="register" class="CRegisterForm" template="common/dialog.tpl" body="register_form.tpl" head="Customer Information" name="registerForm" allowAnonymous="{config.General.enable_anon_checkout}" IF="!showAV"/>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<!-- [/checkout] }}} -->
EOT;
	$replace_str = <<<EOT
<widget target="checkout" mode="error" template="common/dialog.tpl" body="checkout/failure.tpl" head="Checkout error">
<widget module="GoogleCheckout" template="common/dialog.tpl" body="modules/GoogleCheckout/google_checkout_dialog.tpl" head="Google Checkout payment module" visible="{target=#googlecheckout#&!valid}" >
<!-- [/checkout] }}} -->
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

	$find_str = <<<EOT
</TABLE>

<TABLE width="100%" border="0" cellpadding=10 cellspacing="0">
<TR>
<TD><FONT class="Bottom">Powered by LiteCommerce:</FONT> <A href="http://www.litecommerce.com"><FONT class="Bottom"><u>ecommerce software</u></FONT></A>
</TD>
<TD align=right><FONT class="Bottom">Copyright &copy; {config.Company.start_year} {config.Company.company_name}</FONT>
</TD>
</TR>
</TABLE>

</TD>
EOT;
	$replace_str = <<<EOT
</TABLE>

<widget template="powered_by_litecommerce.tpl">

</TD>
EOT;
	$source = strReplace($find_str, $replace_str, $source, __FILE__, __LINE__);

?>