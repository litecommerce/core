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

{* Your account page template *}
<table border=0 cellpadding=3 cellspacing=0>
<tr>
    <td>User <b>{auth.profile.login}</b> is logged in.</td>
    <td>
        <widget class="\XLite\View\Button\Link" label="Logoff" location="{buildURL(#login#,#logoff#)}" />
    </td>
</tr>
<tr><td colspan=2><br><br></td><tr>
<tr>
    <td colspan=2>
        <h2>Account settings</h2>
        <table border=0>
        <tr>
            <td><widget class="\XLite\View\Button\Link" label="Order history" location="{buildURL(#order_list#)}" /></td>
            <td>&nbsp;&nbsp;</td>
            <!-- AFTER HISTORY -->
            <td><widget class="\XLite\View\Button\Link" label="Modify profile" location="{buildURL(#profile#,##,_ARRAY_(#mode#^#modify#))}" /></td>
            <td>&nbsp;&nbsp;</td>
            <!-- AFTER PROFILE -->
            <td><widget class="\XLite\View\Button\Link" label="Delete profile" location="{buildURL(#profile#,##,_ARRAY_(#mode#^#delete#))}" /></td>
            <td>&nbsp;&nbsp;</td>
        </tr>
        </table>
    </td>
</tr>    
</table>
