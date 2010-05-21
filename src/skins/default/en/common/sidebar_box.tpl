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
<table width="100%" cellpadding="0" cellspacing="0">

  <tr>
    <td colspan="3">

      <table width="100%" border="0" cellpadding="0" cellspacing="0" class="SidebarBoxLeftBG">
        <tr>
		  {* FIXME - must use the getHead() function instead *}
          <td class="SidebarBoxCenterBG" nowrap width="100%" align="center">&nbsp;{head:h}&nbsp;</td>
          <td><img src="images/spacer.gif" width="1" height="24" alt="" /></td>
        </tr>
      </table>

    </td>
  </tr>

  <tr>
    <td class="SidebarBorder"><img SRC="images/spacer.gif" width="1" height="1" alt="" /></td>
    <td class="SidebarBox" valign="top" width="100%">

      <table cellpadding="10" cellspacing="0" width="100%">
        <tr>
		  {* FIXME - must use the getBody() function instead *}
          <td><widget template="{dir}/body.tpl"></td>
        </tr>
      </table>
      <table cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td><img src="images/spacer.gif" width="1" height="9" alt="" /></td>
        </tr>
      </table>

	  </td>
    <td class="SidebarBorder"><img src="images/spacer.gif" width="1" height="1" alt="" /></td>
  </tr>

  <tr>
    <td colspan="3" class="SidebarBorder"><img src="images/spacer.gif" width="167" height="1" alt="" /></td>
  </tr>

</table>
<br />
