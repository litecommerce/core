<p class="TabHeader">Shops</p>
<table border="0" cellspacing="2" cellpadding="0">
	<tr>
		<td>
			<table border="0" cellspacing="1" cellpadding="2">
				<tr IF="mode=##">
					<td><img src="images/modules/asp/active/shops_list.gif" border="0" alt=""></td>
					<td class="subTabSelected">List of installed shops</td>
				</tr>
				<tr IF="!mode=##">
					<td><img src="images/modules/asp/inactive/shops_list.gif" border="0" alt=""></td>
					<td class="subTabDefault"><a href="cpanel.php?target=shops"><u>List of installed shops</u></a></td>
				</tr>
			</table>
		</td>
		<td width="32">&nbsp;</td>
		<td>
			<table border="0" cellspacing="1" cellpadding="2">
				<tr IF="mode=#install#">
					<td><img src="images/modules/asp/active/shop_new.gif" border="0" alt=""></td>
					<td class="subTabSelected">Install new shop</td>
				</tr>
				<tr IF="!mode=#install#">
					<td><img src="images/modules/asp/inactive/shop_new.gif" border="0" alt=""></td>
					<td class="subTabDefault"><a href="cpanel.php?target=shops&mode=install"><u>Install new shop</u></a></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<hr>
<p>

<widget mode="" template="modules/asp/shops/shop_list.tpl">
<widget mode="configure" template="modules/asp/shops/shop_config.tpl">
<widget mode="install" template="modules/asp/shops/shop_install.tpl">
<widget mode="uninstall" template="modules/asp/shops/shop_uninstall.tpl">
