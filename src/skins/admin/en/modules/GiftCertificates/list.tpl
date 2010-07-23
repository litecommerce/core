{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Gift Certificates list page template
 *  
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2010 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @version   SVN: $Id$
 * @link      http://www.litecommerce.com/
 * @since     3.0.0
 *}

<widget class="\XLite\View\PagerOrig" name="pager" data="{giftCertificates}" />

<br />

<form action="admin.php" method="POST" name="certForm">

  <input type="hidden" name="target" value="gift_certificates" />
  <input type="hidden" name="action" value="update" />
  <input type="hidden" name="gcid" value="" />
  <input type="hidden" name="deleteStatus" value="" />

  <table border="0" IF="namedWidgets.pager.pageData">

    <tr>
    	<th>Gift certificate</th>
    	<th>Status</th>
    	<th>Rem./Amount</th>
    	<th>Issue date</th>
    	<th>Expiration date</th>
    	<th>Owner</th>
    	<th>&nbsp;</th>
    </tr>

    <tr FOREACH="namedWidgets.pager.pageData,cert">
      <td><a href="admin.php?target=gift_certificate&gcid={cert.gcid}">{cert.gcid}</a><font IF="cert.displayWarning" class="Star">&nbsp;*&nbsp;</font></td>
      <td>
        <select name="status[{cert.gcid}]">
          <option value="P" selected="{isSelected(cert,#status#,#P#)}">Pending</option>
          <option value="A" selected="{isSelected(cert,#status#,#A#)}">Active</option>
          <option value="D" selected="{isSelected(cert,#status#,#D#)}">Disabled</option>
          <option value="U" selected="{isSelected(cert,#status#,#U#)}">Used</option>
          <option value="E" selected="{isSelected(cert,#status#,#E#)}">Expired</option>
        </select>
      </td>
      <td>{price_format(cert.debit):h}/{price_format(cert.amount):h}</td>
      <td>{date_format(cert.add_date)}</td>
      <td>{date_format(cert.expirationDate)}</td>
      <td align="center">
        <span IF="cert.profile_id">{cert.profile.login}</span>
        <span IF="!cert.profile_id">N/A</span>
      </td>
      <td nowrap>
        <a href="javascript: document.certForm.action.value='delete';document.certForm.gcid.value='{cert.gcid}';document.certForm.submit()"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" />&nbsp;Delete</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="admin.php?target=add_gift_certificate&gcid={cert.gcid}"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" />&nbsp;Edit</a>
      </td>
    </tr>

    <tr>
      <td colspan="7">&nbsp;<font class="Star">&nbsp;*&nbsp;</font><i>- these certificates will expire sooner than in {config.GiftCertificates.expiration_warning_days} day(s).</i></td>
    </tr>

    <tr>
      <td colspan="7">
        <input type="submit" value="Update" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" value="Delete all used" onclick="document.certForm.action.value='delete_all';document.certForm.deleteStatus.value='U';document.certForm.submit()" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" value="Delete all expired" onclick="document.certForm.action.value='delete_all';document.certForm.deleteStatus.value='E';document.certForm.submit()" />
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type="button" value="Delete all disabled" onclick="document.certForm.action.value='delete_all';document.certForm.deleteStatus.value='D';document.certForm.submit()" />
      </td>
    </tr>

  </table>

  <span IF="!giftCertificates">No gift certificates found</span>

  <br />

</form>

<a href="admin.php?target=add_gift_certificate"><img src="images/go.gif" width="13" height="13" border="0" align="absmiddle" /> Add new certificate</a>

