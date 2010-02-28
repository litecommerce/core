{* SVN $Id$ *}
If you have an existing Gift certificate you can check its current amount and status using the form below.
<br />
<br />

<font class="ErrorMessage" IF="notFound">The specified Gift certificate is not found.</font>

<form name="gccheckform" action="{buildURL(#check_gift_certificate#)}" method="GET">

  <table>
    <tr>
      <td>Gift certificate:&nbsp;</td>
      <td><input type="text" size="25" maxlength="16" name="gcid" value="{foundgc.gcid:r}" //td>
      <td><widget class="XLite_View_Button" label="Find" type="button"></td>
    </tr>
  </table>

</form>

<table IF="found">

  <tr>
    <td colspan="2">
      <hr size="1" noshade />
    </td>
  </tr>

  <tr>
    <td><strong>Gift Certificate ID:</strong></td>
    <td>{foundgc.gcid}</td>
  </tr>

  <tr>
    <td><strong>Amount:</strong></td>
    <td>{price_format(foundgc,#amount#):h}</td>
  </tr>

  <tr>
    <td><strong>Available:</strong></td>
    <td>{price_format(foundgc,#debit#):h}</td>
  </tr>

  <tr>
    <td><strong>Status:</strong></td>
    <td>
      <span IF="isSelected(foundgc,#status#,#P#)">Pending</span>
      <span IF="isSelected(foundgc,#status#,#A#)">Active</span>
      <span IF="isSelected(foundgc,#status#,#D#)">Disabled</span>
      <span IF="isSelected(foundgc,#status#,#U#)">Used</span>
    </td>
  </tr>

</table>
