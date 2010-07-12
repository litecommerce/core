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
<table width="100%">
  <tr FOREACH="split(ecards,3),row">
    <td FOREACH="row,ecard" align="center" width="33%">

      <form IF="ecard" action="{buildURL(#gift_certificate_ecards#,#update#,_ARRAY_(#gcid#^gcid,#ecard_id#^ecard.ecard_id))}" method="POST" name="ecard_form">
        <input FOREACH="buildURLArguments(#gift_certificate_ecards#,#update#,_ARRAY_(#gcid#^gcid,#ecard_id#^ecard.ecard_id)),paramName,paramValue" type="hidden" name="{paramName}" value="{paramValue}" />

        <a href="{ecard.image.url}" target="_blank"><img src="{ecard.thumbnail.url}" alt="" /></a>
        <br />
        <widget class="\XLite\View\Button" label="Select" type="button">
        <br />&nbsp;
      </form>

    </td>
  </tr>
</table>
<widget class="\XLite\View\Button" type="button_link" href="{buildURL(#gift_certificate#,##,_ARRAY_(#gcid#^gcid))}" label="Cancel">
