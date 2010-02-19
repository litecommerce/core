{* SVN $Id$ *}
<table width="100%">
  <tr FOREACH="split(ecards,3),row">
    <td FOREACH="row,ecard" align="center" width="33%">

      <form IF="ecard" action="{buildURL(#gift_certificate_ecards#,#update#,_ARRAY_(#gcid#^gcid,#ecard_id#^ecard.ecard_id))}" method="POST" name="ecard_form">
        <input FOREACH="buildURLArguments(#gift_certificate_ecards#,#update#,_ARRAY_(#gcid#^gcid,#ecard_id#^ecard.ecard_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

        <a href="{ecard.image.url}" target="_blank"><img src="{ecard.thumbnail.url}" alt="" /></a>
        <br />
        <widget class="XLite_View_Button" label="Select" type="button">
        <br />&nbsp;
      </form>

    </td>
  </tr>
</table>
<widget class="XLite_View_Button" type="button_link" href="{buildURL(#add_gift_certificate#,##,_ARRAY_(#gcid#^gcid))}" label="Cancel">
