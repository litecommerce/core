<!-- [begin] -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<HTML>

<!-- [head] -->
<HEAD>
	<script type="text/javascript">
  	  if (document.getElementById("rebuild_cache_block")) document.getElementById("rebuild_cache_block").style.display = "none";
	</script>

<TITLE>LiteCommerce online store builder
{if:target=#category#&!title=##} - {end:}
{if:target=#product#&!title=##} - {end:}
{if:target=#cart#} - Your Shopping Cart{end:}
{if:target=#help#} - Help section{end:}
{if:target=#checkout#} - Checkout{end:}
{if:target=#checkoutSuccess#} - Thank you for your order{end:}
{if:target=#main#&!page=##} - {extraPage.title:h}{end:}
{title:h}
</TITLE>
<META http-equiv="Content-Type" content="text/html; charset={charset}">
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<META IFF="!metaDescription" name="description" content="The powerful shopping cart software for web stores and e-commerce enabled stores is based on PHP / PHP4 with SQL database with highly configurable implementation based on templates.">
<META IFF="metaDescription" name="description" content="{metaDescription:r}">
<META IFF="keywords" name="keywords" content="{keywords:r}">
<LINK href="skins/default/en/style.css"  rel="stylesheet" type="text/css">
<LINK IFF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme" href="{xlite.layout.path}modules/FlyoutCategories/catalog{xlite.auth.flyoutCategoriesCacheDirectory}/{xlite.FlyoutCategoriesCssPath}" rel="stylesheet" type="text/css">
<script src="skins/drupal/en/js/common.js" type="text/javascript"></script>
</HEAD>
<!-- [/head] -->

<BODY>
<!-- [/begin] -->
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0" class="Container">
<TR>
<TD valign="top">

<TABLE width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
<TD>
<!-- [top] -->

<TABLE width="100%" border="0" cellpadding="0" cellspacing="0" class="Wallpaper">
<TR>
   <TD valign=top>

<TABLE width="100%" border="0" cellpadding="0" cellspacing="0">
<TR>
	<TD width="275" valign="top">
		<TABLE border="0" cellpadding="0" cellspacing="0">
		<TR>
			<!--TD><IMG src="images/spacer.gif" width="40" height="122" border="0" hspace="0" vspace="0" alt=""></TD-->
			<TD><IMG src="images/spacer.gif" width="40" height="96" border="0" hspace="0" vspace="0" alt=""></TD>
			<!--TD valign="top"><A href="cart.php"><IMG src="images/logo_white.gif" width="207" height="53" border="0" hspace="0" vspace="28" border="0" alt=""></A></TD-->
			<TD valign="top" style="padding-top: 26px;"><A href="cart.php"><IMG src="images/logo_white.gif" width="207" height="53" border="0" hspace="0" vspace="0" alt=""></A></TD>
		</TR>
		</TABLE>
	</TD>
	<TD valign="top">
		<TABLE border="0" cellpadding="0" cellspacing="16" align="right">
		<TR>
			<TD nowrap align="center">
			<!-- [phones] -->
			<widget template="phones.tpl">
			<!-- [/phones] -->
			</TD> 
		</TR>
		</TABLE>
	</TD>
</TR>

<TR>
<TD class="MainHeaderBG">
&nbsp;
</TD>
<TD class="MainHeaderBG">

<!-- [tabs] {{{ -->
<TABLE border="0" cellpadding="0" cellspacing="0" align="right">
<TR>
<TD>
<widget template="common/tab.tpl" label="Catalog" href="cart.php" img="sideicon_orders.gif" active="main">
</TD>
<TD>
<widget if="cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" active="cart"/>
<widget if="!cart.empty" template="common/tab.tpl" label="Your Cart" href="cart.php?target=cart" active="cart"/>
</TD>
<TD>
<widget module="WishList" visible="{auth.logged&wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon.gif" active="wishlist">
<widget module="WishList" visible="{auth.logged&!wishlist.products}" template="common/tab.tpl" label="Wish list" href="cart.php?target=wishlist" img="modules/WishList/wish_list_icon_empty.gif" active="wishlist">
</TD>
<TD>
<widget if="auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&amp;mode=account" active="profile"/>
<widget if="!auth.logged" template="common/tab.tpl" label="Your Account" href="cart.php?target=profile&amp;mode=login" active="profile"/>
</TD>
<TD>
<widget template="common/tab.tpl" label="Contact Us" href="cart.php?target=help&amp;mode=contactus" active="help"/>
</TD>
<TD>
<widget module="Affiliate" template="common/tab.tpl" label="Affiliate" href="cart.php?target=partner_login" active="partner_login"/>
</TD>
<!-- add_tabs below are reserved for future modules, DO NOT BIND -->
<TD>
<!-- [add_tab1] -->
</TD>
<TD>
<!-- [add_tab2] -->
</TD>
<TD>
<!-- [add_tab3] -->
</TD>
<!-- Add your tabs here -->
<TD><IMG src="images/tab_terminator.gif" width="1" height="42" border="0" alt=""></TD>
<!--TD><IMG src="images/spacer.gif" width="1" height="1" border="0" alt=""></TD-->
</TR>
</TABLE>
<!-- [tabs] }}} -->

</TD>
</TR>
</TABLE>

</TD>
</TR>
</TABLE>
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_flat.tpl">
<!-- [/top] -->

<!-- [main_view] -->
<TABLE border="0" align="center" cellpadding="0" cellspacing="0">
<TR>
    <TD valign="top">
    <noscript>
        <table border=0 width=500 cellpadding=2 cellspacing=0 align=center>
        <tr>
            <td align=center class=ErrorMessage nowrap>The requested action requires JavaScript.<br>Please enable Javascript in your web browser.</td>
        </tr>
        </table>
    </noscript>
	<script type="text/javascript" language="JavaScript 1.2" src="skins/default/en/js/cookie_validator.js"></script>
    </TD>
</TR>
</TABLE>
<TABLE border="0" width="100%" cellpadding="0" cellspacing="0">
<TR>
    <TD width="4"><IMG src="images/spacer.gif" width="4" height="1" alt=""></TD>
	<TD width="180" valign="top">

<!-- [left] -->

<!-- [search] -->
<widget template="search_products.tpl" />
<!-- [/search] -->

<div IF="xlite.FlyoutCategoriesEnabled">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_side.tpl">
</div>
<div IF="!xlite.FlyoutCategoriesEnabled">
<widget class="XLite_View_TopCategories" />
</div>
<widget module="Bestsellers" class="XLite_Module_Bestsellers_View_Bestsellers" />
<widget module="GiftCertificates" template="common/sidebar_box.tpl" head="Gift certificates" dir="modules/GiftCertificates/menu">
<widget module="Affiliate" template="common/sidebar_box.tpl" head="Affiliate" dir="modules/Affiliate/menu">
<widget template="common/sidebar_box.tpl" head="Help" dir="help">
<!-- [/left] -->
	<IMG src="images/spacer.gif" width="180" height="1" alt="">
    </TD>

	<TD width="15"><IMG src="images/spacer.gif" width="15" height="1" alt=""></TD>
    <TD valign="top" width="100%"><BR>

	<widget template="center.tpl" />

    </TD>
    <TD width="15"><IMG src="images/spacer.gif" width="15" height="1" alt=""></TD>
    <TD width="170" valign="top"><BR>
<!-- [right] -->
<widget module="SnsIntegration" template="modules/SnsIntegration/tracker.tpl">
<widget class="XLite_View_Minicart" />
<widget template="common/sidebar_box.tpl" dir="login" name="loginWidget" head="Authentication" IF="{!auth.isLogged()}" />
<widget template="common/sidebar_box.tpl" dir="profile" name="profileWidget" head="Your profile" IF="{auth.isLogged()}" />
<widget template="common/sidebar_box.tpl" dir="authentication" name="authenticationWidget" head="Authentication" IF="{auth.isLogged()}" />
<widget module="Newsletters" template="common/sidebar_box.tpl" dir="modules/Newsletters/menu_news" head="News">
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_NewArrivals" displayMode="menu" />
<widget module="ProductAdviser" class="XLite_Module_ProductAdviser_View_RecentlyViewed" />
<!-- [/right] -->
    </TD>
    <TD width="4"><IMG src="images/spacer.gif" width="4" height="1" alt=""></TD>
</TR>
</TABLE>

<!-- [/main_view] -->
</TD>
</TR>
</TABLE>

</TD>
</TR>

<TR><TD height="100%"><img src="images/spacer.gif" width=1 height=1 alt=""></TD></TR>

<TR>
<TD>
<!-- [bottom] -->
<TABLE width="100%" border="0" cellpadding="0" cellspacing="0">
<TR>
<TD class="BottomBorder"><IMG src="images/spacer.gif" width=1 height=1 border="0" alt=""></TD>
</TR>
<TR>
<TD class="BottomBox">

<TABLE width="70%" border="0" cellpadding=10 cellspacing="0" align="center">
<TR>
<TD colspan=2 align="center" class="BottomMenu">
Help |  <A href="cart.php?target=help&amp;mode=contactus"><FONT class="BottomMenu">Contact us</FONT></A> | <A href="cart.php?target=help&amp;mode=privacy_statement"><FONT class="BottomMenu">Privacy statement</FONT></A> | <A href="cart.php?target=help&amp;mode=terms_conditions"><FONT class="BottomMenu">Terms &amp; Conditions</FONT></A>
<widget template="pages_links.tpl">
</TD>
</TR>
</TABLE>

<widget template="powered_by_litecommerce.tpl">

</TD>
</TR>
</TABLE>
<!-- [/bottom] -->

</TD>
</TR>
</TABLE>

<!-- [end] -->
<div IF="xlite.FlyoutCategoriesEnabled&xlite.config.FlyoutCategories.scheme">
<widget module="FlyoutCategories" template="modules/FlyoutCategories/main_footer.tpl">
</div>
</body>
</HTML>
<!-- [/end] -->






