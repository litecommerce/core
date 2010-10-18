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

<span class="address-text">

  <table width="100%" cellpadding="3" cellspacing="0">

    <tr>
      <td></td>
      <td colspan="2"></td>
    </tr>

    <tr FOREACH="getSchemaFields(),fieldName,fieldData">
      <widget class="{fieldData.class}" label="{fieldData.label}" fieldName="{fieldName}" value="{getFieldValue(fieldName)}" required="{fieldData.required}" />
    </tr>

  </table>

</span>
