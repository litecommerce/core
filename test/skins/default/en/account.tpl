{* Your account page template *}
<table border=0 cellpadding=3 cellspacing=0>
<tr>
    <td>User <b>{auth.profile.login}</b> is logged in.</td>
    <td>
        <widget class="XLite_View_Button"label="Logoff" href="cart.php?target=login&action=logoff">
    </td>
</tr>
<tr><td colspan=2><br><br></td><tr>
<tr>
    <td colspan=2>
        <h2>Account settings</h2>
        <table border=0>
        <tr>
            <td><widget class="XLite_View_Button"label="Order history" href="cart.php?target=order_list"></td>
            <td>&nbsp;&nbsp;</td>
            <!-- AFTER HISTORY -->
            <td><widget class="XLite_View_Button"label="Modify profile" href="cart.php?target=profile&mode=modify"></td>
            <td>&nbsp;&nbsp;</td>
            <!-- AFTER PROFILE -->
            <td><widget class="XLite_View_Button"label="Delete profile" href="cart.php?target=profile&mode=delete"></td>
            <td>&nbsp;&nbsp;</td>
        </tr>
        </table>
    </td>
</tr>    
</table>
